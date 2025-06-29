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
        Schema::create('funcionario', function (Blueprint $table) {
            // A chave primária 'id' também é a chave estrangeira para a tabela 'pessoa'.
            $table->unsignedBigInteger('id')->primary();
            
            // Definição da chave estrangeira de forma mais fluente
            $table->foreign('id')->references('id')->on('pessoa')->onDelete('cascade');

            // Campos específicos do funcionário
            $table->enum('nivel_acesso', ['ADMINISTRADOR', 'CONSULTOR']);
            $table->decimal('salario', 10, 2)->nullable();
            $table->date('data_contratacao')->nullable();

            // Timestamps não são necessários, como definido no seu modelo.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionario');
    }
};
