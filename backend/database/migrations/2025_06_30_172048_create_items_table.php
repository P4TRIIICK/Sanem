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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nome_item');
            $table->unsignedInteger('quantidade');
            $table->string('categoria_principal');
            $table->text('descricao')->nullable();
            $table->string('foto_path')->nullable();
            
            // Coluna JSON para guardar os detalhes específicos da categoria
            $table->json('detalhes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
