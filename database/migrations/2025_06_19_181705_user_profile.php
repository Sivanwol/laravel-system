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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('dateOfBirth')->nullable();
            $table->datetime('allow_preform_deliveries')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->string('about_me', 1000)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('country_region', )->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('apartment_number', 10)->nullable();
            $table->string('building_number', 10)->nullable();
            $table->smallInteger('floor_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
