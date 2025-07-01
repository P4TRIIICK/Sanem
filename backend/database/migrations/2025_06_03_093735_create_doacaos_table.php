<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doacoes', function (Blueprint $table) {
            $table->id();
            // Chave estrangeira para o beneficiário (a pessoa que recebe)
            $table->foreignId('pessoa_id')->constrained('pessoa');
            // Chave estrangeira para o funcionário (quem registou a doação)
            $table->foreignId('funcionario_id')->constrained('pessoa');
            $table->date('data_doacao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doacoes');
    }
};
