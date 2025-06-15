<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelefoneTable extends Migration
{
    public function up()
    {
        Schema::create('telefone', function (Blueprint $table) {
            $table->unsignedBigInteger('pessoa_id');
            $table->string('numero', 20);
            $table->primary(['pessoa_id', 'numero']);
            $table->index('pessoa_id');
            $table->foreign('pessoa_id')
                  ->references('id')->on('pessoa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('telefone', function (Blueprint $table) {
            $table->dropForeign(['pessoa_id']);
            $table->dropIndex(['pessoa_id']);
        });
        Schema::dropIfExists('telefone');
    }
}
