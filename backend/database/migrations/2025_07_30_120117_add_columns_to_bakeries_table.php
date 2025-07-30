<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('bakeries', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('phone')->nullable()->after('nif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('phone');
            $table->timestamp('trial_until')->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('bakeries', function (Blueprint $table) {
            $table->dropColumn(['slug', 'phone', 'created_by', 'trial_until']);
        });
    }

};
