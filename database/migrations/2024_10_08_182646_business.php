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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('website', 500)->nullable();
            $table->string('facebook', 500)->nullable();
            $table->string('twitter', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->string('linkedin', 500)->nullable();
            $table->string('youtube', 500)->nullable();
            $table->string('whatapp', 500)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('country', 4)->default('IL');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('timezone')->default('Asia/Jerusalem');
            $table->enum('business-size', ['1-10', '11-50', '51-200', '201-500', '501-1000', '1001-5000', '5001-10000', '10001+'])->default('11-50');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
