<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliver_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('deliver_pickup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->string('country_code', 10)->nullable();
            $table->string('country_region', 100)->nullable();
            $table->string('city');
            $table->string('address');
            $table->string('zip_code');
            $table->string('apartment_number');
            $table->string('building_number');
            $table->string('building_code')->nullable();
            $table->smallInteger('floor_number');
            $table->boolean('has_elevator')->default(0);
            $table->text('additional_notes')->nullable();
            $table->smallInteger('total_packages')->default(1);
            $table->smallInteger('total_pallets')->default(0);
            $table->datetime('pickup_date');
            $table->smallInteger('from_hour');
            $table->smallInteger('to_hour');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_canceled')->default(0);
            $table->boolean('is_reported')->default(0);
            $table->index(['deliver_id', 'country_code', 'country_region', 'city']);
        });

        Schema::create('deliver_has_deliver_pickup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->unsignedBigInteger('deliver_pickup_id');
            $table->timestamps();
            $table->foreignId('deliver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('deliver_pickup_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('deliver_destination', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->string('country_code', 10)->nullable();
            $table->string('country_region', 100)->nullable();
            $table->string('city');
            $table->string('address');
            $table->string('zip_code');
            $table->string('apartment_number');
            $table->string('building_number');
            $table->string('building_code')->nullable();
            $table->smallInteger('floor_number');
            $table->boolean('has_elevator')->default(0);
            $table->text('additional_notes')->nullable();
            $table->smallInteger('total_packages')->default(1);
            $table->smallInteger('total_pallets')->default(0);
            $table->datetime('delivery_date');
            $table->smallInteger('from_hour');
            $table->smallInteger('to_hour');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_canceled')->default(0);
            $table->boolean('is_reported')->default(0);
            $table->index(['deliver_id', 'country_code', 'country_region', 'city']);
        });

        Schema::create('deliver_has_deliver_destination', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->unsignedBigInteger('deliver_destination_id');
            $table->timestamps();
            $table->foreignId('deliver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('deliver_destination_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('deliver_pickup_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->unsignedBigInteger('deliver_pickup_id');
            $table->string('image');
            $table->boolean('is_preview')->default(0);
            $table->boolean('is_validated_pickup')->default(0);
            $table->timestamps();
            $table->foreignId('deliver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('deliver_pickup_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('deliver_destination_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_id');
            $table->unsignedBigInteger('deliver_destination_id');
            // report id will added later
            $table->string('image');
            $table->boolean('is_reported')->default(0);
            $table->boolean('is_validated_destination')->default(0);
            $table->timestamps();
            $table->foreignId('deliver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('deliver_destination_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('deliver_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('deliver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('own_user_id');
            $table->foreign('own_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('deliver_category_id');
            $table->foreignId('deliver_category_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('deliver_winner_user_id')->nullable();
            $table->foreign('deliver_winner_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('slug', 100);
            $table->string('title', 500);
            $table->string('short_description', 1000);
            $table->enum('required_driver_license', ['A', 'A1', 'A2', 'B', 'C', 'C1', 'D', 'D1', 'C+E'])->nullable();
            $table->smallInteger('total_packages')->default(1);
            $table->smallInteger('total_pallets')->default(0);
            $table->boolean('required_cooling')->default(false);
            $table->text('description');
            $table->smallInteger('total_pickups')->default(1);
            $table->smallInteger('total_deliveries')->default(1);
            $table->smallInteger('total_views')->default(0);
            $table->smallInteger('total_offers')->default(0);
            $table->smallInteger('total_accepted_offers')->default(0);
            $table->smallInteger('total_rejected_offers')->default(0);
            $table->smallInteger('total_completed_offers')->default(0);
            $table->smallInteger('total_canceled_offers')->default(0);
            $table->smallInteger('total_reviews')->default(0);
            $table->smallInteger('total_reports')->default(0);

            $table->unsignedInteger('deliver_status_id')->default(1);
            $table->foreign('deliver_status_id')->references('id')->on('deliver_status')->onDelete('cascade');
            $table->integer('price')->default(0);
            $table->boolean('is_negotiable')->default(1);
            $table->boolean('is_targeted_offer')->default(0); //  favorite
            $table->boolean('is_required_cooling')->default(0);
            $table->boolean('is_some_cargo_breakable')->default(0);
            $table->boolean('is_required_manual_work')->default(0);
            $table->boolean('is_required_physical_workers')->default(0);
            $table->boolean('is_required_assembles_workers')->default(0);
            $table->boolean('is_hourly')->default(0);
            $table->boolean('is_payment_bank')->default(0);
            $table->boolean('is_payment_cash')->default(0);
            $table->boolean('is_payment_check')->default(0);
            $table->boolean('is_payment_bit')->default(0);
            $table->boolean('is_payment_paypal')->default(0);
            $table->boolean('is_payment_paybox')->default(0);
            $table->string('currency', 10)->default('nis');
            $table->string('country_code', 10)->nullable();
            $table->timestamps();
            $table->foreignId('deliver_status_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('deliver_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('deliver_id')->nullable();
            $table->foreign('deliver_id')->references('id')->on('deliver')->onDelete('cascade');
            $table->unsignedInteger('by_user_id')->nullable();
            $table->foreign('by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_pickup_id')->nullable();
            $table->foreign('deliver_pickup_id')->references('id')->on('deliver_pickup')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_destination_id')->nullable();
            $table->foreign('deliver_destination_id')->references('id')->on('deliver_destination')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_pickup_images_id')->nullable();
            $table->foreign('deliver_pickup_images_id')->references('id')->on('deliver_pickup_images')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_destination_images_id')->nullable();
            $table->foreign('deliver_destination_images_id')->references('id')->on('deliver_destination_images')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_status_id')->nullable();
            $table->foreign('deliver_status_id')->references('id')->on('deliver_status')->onDelete('cascade');
            $table->enum('report_type', ['pickup_complete', 'destination_complete', 'destination_no_delivery', 'destination_not_found', 'pickup_not_found', 'road_delay', 'road_accident', 'pickup_not_found', 'pickup_recipient_not_location', 'other'])->default('other');
            $table->text('report_description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['deliver_id', 'by_user_id', 'deliver_pickup_id', 'deliver_destination_id', 'deliver_status_id', 'report_type']);
        });

        Schema::table('deliver_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('deliver_id')->nullable();
            $table->unsignedInteger('by_user_id')->nullable();
            $table->foreign('deliver_id')->references('id')->on('deliver')->onDelete('cascade');
            $table->unsignedInteger('to_user_id')->nullable();
            $table->foreign('by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('to_user_id', ['pending', 'accepted', 'rejected', 'canceled', 'completed'])->default('pending');
            $table->integer('price')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('deliver_rates', function (Blueprint $table) {
            $table->unsignedBigInteger('deliver_id')->nullable();
            $table->foreign('deliver_id')->references('id')->on('deliver')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_pickup_id')->nullable();
            $table->foreign('deliver_pickup_id')->references('id')->on('deliver_pickup')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_destination_id')->nullable();
            $table->foreign('deliver_destination_id')->references('id')->on('deliver_destination')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_pickup_images_id')->nullable();
            $table->foreign('deliver_pickup_images_id')->references('id')->on('deliver_pickup_images')->onDelete('cascade');
            $table->unsignedBigInteger('deliver_destination_images_id')->nullable();
            $table->foreign('deliver_destination_images_id')->references('id')->on('deliver_destination_images')->onDelete('cascade');
            $table->unsignedInteger('rate')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->primary(['deliver_id', 'deliver_pickup_id', 'deliver_destination_id']);
        });

        Schema::table('deliver_messages', function (Blueprint $table): void {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('deliver_id')->nullable();
            $table->foreign('deliver_id')->references('id')->on('deliver')->onDelete('cascade');
            $table->integer('from_user_id')->nullable();
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('to_user_id')->nullable();
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->text('message');
            $table->boolean('is_pin')->default(0);
            $table->boolean('is_system')->default(0);
            $table->boolean('is_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliver');
        Schema::dropIfExists('deliver_status');
        Schema::dropIfExists('deliver_pickup');
        Schema::dropIfExists('deliver_has_deliver_pickup');
        Schema::dropIfExists('deliver_destination');
        Schema::dropIfExists('deliver_has_deliver_destination');
        Schema::dropIfExists('deliver_pickup_images');
        Schema::dropIfExists('deliver_destination_images');
        Schema::dropIfExists('deliver_categories');
        Schema::dropIfExists('deliver_reports');
        Schema::dropIfExists('deliver_offers');
        Schema::dropIfExists('deliver_rates');
        Schema::dropIfExists('deliver_messages');
    }
};
