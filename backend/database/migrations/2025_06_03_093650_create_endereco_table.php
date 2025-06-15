<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecoTable extends Migration
{
    public function up()
    {
        Schema::create('endereco', function (Blueprint $table) {
            $table->id();
            $table->string('logradouro', 255);
            $table->string('numero', 50);
            $table->string('complemento', 255)->nullable();
            $table->string('bairro', 255)->nullable();
            $table->string('cep', 20)->nullable();
            $table->unsignedBigInteger('cidade_id');
            $table->index('cidade_id');
            $table->foreign('cidade_id')
                  ->references('id')->on('cidade')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('endereco', function (Blueprint $table) {
            $table->dropForeign(['cidade_id']);
            $table->dropIndex(['cidade_id']);
        });
        Schema::dropIfExists('endereco');
    }
}
