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
         Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('person_id')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->date('verified_date')->nullable();
            $table->foreignId('person_id')->unique()->constrained('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
            $table->dropColumn('is_active'); 
            $table->dropColumn('is_verified');
            $table->dropColumn('verified_date');
        });
    }
};
