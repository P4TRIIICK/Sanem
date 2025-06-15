<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDoacaoTable extends Migration
{
    public function up()
    {
        Schema::create('item_doacao', function (Blueprint $table) {
            $table->unsignedBigInteger('doacao_id');
            $table->unsignedBigInteger('produto_id');
            $table->integer('quantidade');
            $table->primary(['doacao_id', 'produto_id']);
            $table->index('doacao_id');
            $table->index('produto_id');
            $table->foreign('doacao_id')
                  ->references('id')->on('doacao')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('produto_id')
                  ->references('id')->on('produto')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('item_doacao', function (Blueprint $table) {
            $table->dropForeign(['doacao_id']);
            $table->dropForeign(['produto_id']);
            $table->dropIndex(['doacao_id']);
            $table->dropIndex(['produto_id']);
        });
        Schema::dropIfExists('item_doacao');
    }
}
