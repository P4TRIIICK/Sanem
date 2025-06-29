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
        Schema::create('pessoa', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('rg')->nullable();
            $table->enum('genero', ['MASCULINO', 'FEMININO', 'OUTRO']);
            
            // --- LINHA CORRIGIDA ---
            // Adicionamos ->nullable() para permitir que usuários administrativos não tenham um tipo.
            $table->enum('tipo_beneficiario', ['BENEFICIARIO', 'DOADOR', 'BENEFICIARIO_DOADOR'])->nullable();
            
            $table->date('nascimento')->nullable();
            $table->string('email')->nullable();

            // Torna endereco_id opcional
            $table->foreignId('endereco_id')
                ->nullable()
                ->constrained('endereco')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            // Adicionamos a coluna de senha que estava faltando na sua imagem, 
            // necessária para o login.
            $table->string('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa');
    }
};
