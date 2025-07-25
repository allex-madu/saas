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
            $table->foreignId('backery_id')->nullable()->constrained('bakeries')->cascadeOnDelete();
            $table->boolean('is_super_admin')->default(false)->after('backery_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['backery_id']);
            $table->dropColumn(['backery_id', 'is_super_admin']);
        });
    }
};
