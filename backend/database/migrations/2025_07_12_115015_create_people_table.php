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
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120);
            $table->string('nickname', 120)->nullable();
            $table->string('nif', 21)->unique()->nullable();
            $table->string('address', 255)->nullable();
            $table->string('reference', 300)->nullable();
            $table->bigInteger('number')->nullable(); 
            $table->string('zip_code', 20)->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email', 90)->nullable();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
