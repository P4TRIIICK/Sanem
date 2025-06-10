<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('categoria_produto', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('produto_id');
            $table->primary(['categoria_id', 'produto_id']);
            $table->index('categoria_id');
            $table->index('produto_id');
            $table->foreign('categoria_id')
                  ->references('id')->on('categoria')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('produto_id')
                  ->references('id')->on('produto')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('categoria_produto', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['produto_id']);
            $table->dropIndex(['categoria_id']);
            $table->dropIndex(['produto_id']);
        });
        Schema::dropIfExists('categoria_produto');
    }
}
