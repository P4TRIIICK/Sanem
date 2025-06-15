<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoacaoTable extends Migration
{
    public function up()
    {
        Schema::create('doacao', function (Blueprint $table) {
            $table->id();
            $table->dateTime('instante');
            $table->enum('status_doacao', ['DOACAO_APTA', 'DOACAO_NEGADA']);
            $table->unsignedBigInteger('pessoa_id');
            $table->index('pessoa_id');
            $table->foreign('pessoa_id')
                  ->references('id')->on('pessoa')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->dropForeign(['pessoa_id']);
            $table->dropIndex(['pessoa_id']);
        });
        Schema::dropIfExists('doacao');
    }
}
