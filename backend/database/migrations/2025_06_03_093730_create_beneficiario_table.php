<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id();

            // Relacionamento com a tabela 'pessoa'
            $table->foreignId('pessoa_id')->constrained('pessoa')->onDelete('cascade');

            // Novo: Identificador único para uso futuro
            $table->uuid('identificador_unico')->unique();

            // Novo: Renda familiar
            $table->decimal('renda', 10, 2)->nullable();

            // Novo: Caminho para a foto armazenada
            $table->string('foto_path')->nullable();

            // Novo: Status do auxílio
            $table->string('status')->default('EM_ANALISE'); // Ex: EM_ANALISE, APROVADO, NEGADO

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiarios');
    }
};
