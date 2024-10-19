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
        Schema::create('user_delivery', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->enum('current_driver_license', ['A', 'A1', 'A2', 'B','B1', 'C', 'C1', 'D', 'D1', 'D2', 'D3', 'C+E', 'F','R'])->nullable();
            $table->datetime('driver_license_issue_date')->nullable();
            $table->datetime('driver_license_expiry_date')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->string('about_my_service', 1000)->nullable();
            $table->string('youtube', 500)->nullable();
            $table->string('whatapp', 500)->nullable();
            $table->string('website', 500)->nullable();
            $table->string('facebook', 500)->nullable();
            $table->string('twitter', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->boolean('allow_physical_work')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_delivery');
    }
};
