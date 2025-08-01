<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela pivô bakery_user.
     * Cada usuário pode estar associado a uma ou mais padarias.
     */
    public function up(): void
    {
        Schema::create('bakery_user', function (Blueprint $table) {
            $table->id();

            // Relacionamento com usuários
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Relacionamento com padarias
            $table->foreignId('bakery_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Papel do usuário dentro da padaria (ex: admin, gerente, caixa)
            $table->string('role')->default('admin');

            $table->timestamps();

            // Evita duplicidade de vínculos
            $table->unique(['user_id', 'bakery_id']);
        });
    }

    /**
     * Remove a tabela bakery_user.
     */
    public function down(): void
    {
        Schema::dropIfExists('bakery_user');
    }
};
