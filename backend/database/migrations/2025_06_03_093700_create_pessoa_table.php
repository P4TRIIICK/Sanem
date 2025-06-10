<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pessoa', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('rg')->nullable();
            $table->enum('genero', ['MASCULINO','FEMININO','OUTRO']);
            $table->enum('tipo_beneficiario', ['BENEFICIARIO','DOADOR','BENEFICIARIO_DOADOR']);
            $table->date('nascimento')->nullable();
            $table->string('email')->nullable();

            // Torna endereco_id opcional
            $table->foreignId('endereco_id')
                  ->nullable()
                  ->constrained('endereco')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            // Coluna password
            $table->string('password');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pessoa');
    }
};
