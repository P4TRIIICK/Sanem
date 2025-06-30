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
        Schema::create('doacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoa');
            $table->date('data_doacao');
            $table->time('instante');
            
            // CORREÇÃO APLICADA AQUI:
            // Usar ENUM para definir os valores permitidos e corrigir o erro de 'Data truncated'.
            $table->enum('status_doacao', ['RECEBIDO', 'EM_ANALISE', 'APROVADO', 'RECUSADO']);
            $table->enum('status_entrega', ['PENDENTE', 'ENTREGUE', 'CANCELADO']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao');
    }
};
