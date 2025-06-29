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
        Schema::create('endereco', function (Blueprint $table) {
            $table->id();
            $table->string('logradouro'); // Obrigatório
            $table->string('numero', 50)->nullable(); // Opcional
            $table->string('complemento', 255)->nullable(); // Opcional
            $table->string('bairro'); // Obrigatório
            $table->string('cep', 20)->nullable(); // Opcional
            
            // Chave estrangeira para cidade, usando a sintaxe moderna 'foreignId'
            $table->foreignId('cidade_id')
                  ->constrained('cidade')
                  ->onDelete('restrict') // Impede que uma cidade seja apagada se tiver endereços
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endereco');
    }
};
