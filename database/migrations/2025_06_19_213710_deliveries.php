<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliver_status', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('deliver_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('deliver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedSmallInteger('deliver_category_id');
            $table->foreign('deliver_category_id')->references('id')->on('deliver_categories')->cascadeOnDelete();
            $table->unsignedBigInteger('deliver_winner_user_id')->nullable();
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

            $table->integer('offer_price')->default(0);
            $table->boolean('is_negotiable')->default(1);
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

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliver');
        Schema::dropIfExists('deliver_categories');
        Schema::dropIfExists('deliver_status');
    }
};
