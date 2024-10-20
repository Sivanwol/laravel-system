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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('vehicle_type', ['e-bicycle', 'bike', 'car', 'van', 'small-truck', 'truck', 'sami-truck', 'bus', 'mini-bus', 'other'])->default('car');
            $table->string('other_vehicle_type', 100)->nullable();
            $table->string('license_plate', 40)->unique();
            $table->boolean('is_manual')->default(false);
            $table->boolean('is_electric')->default(false);
            $table->integer('max_km_per_run')->unsigned();
            $table->integer('max_weight')->unsigned();
            $table->boolean('has_cooling')->default(false);
            $table->timestamps();
        });

        Schema::create('vehicle_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('description', 500)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'repair', 'other'])->default('active');
            $table->string('other_status', 100)->nullable();
            $table->integer('milage')->nullable();
            $table->dateTime('last_inspection')->nullable();
            $table->dateTime('last_service')->nullable();
            $table->timestamps();
        });

        Schema::create('business_has_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->unsignedInteger('business_id');
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->integer('milage')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'repair', 'other'])->default('active');
            $table->string('other_status', 100)->nullable();
            $table->dateTime('last_inspection')->nullable();
            $table->dateTime('last_service')->nullable();
            $table->timestamps();
            $table->unique(['vehicle_id', 'business_id']);
        });


        Schema::create('delivery_has_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('milage')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'repair', 'other'])->default('active');
            $table->string('other_status', 100)->nullable();
            $table->dateTime('last_inspection')->nullable();
            $table->dateTime('last_service')->nullable();
            $table->timestamps();
            $table->unique(['vehicle_id', 'user_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_history');
        Schema::dropIfExists('business_vehicles');
        Schema::dropIfExists('delivery_vehicles');
        Schema::dropIfExists('vehicles');
    }
};
