<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCidadeTable extends Migration
{
    public function up()
    {
        Schema::create('cidade', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->unsignedBigInteger('estado_id');
            $table->index('estado_id');
            $table->foreign('estado_id')
                  ->references('id')->on('estado')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('cidade', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropIndex(['estado_id']);
        });
        Schema::dropIfExists('cidade');
    }
}
