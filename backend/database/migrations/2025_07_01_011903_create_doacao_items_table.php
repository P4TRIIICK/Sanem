<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doacao_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doacao_id')->constrained('doacoes')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->unsignedInteger('quantidade_doada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doacao_item');
    }
};
