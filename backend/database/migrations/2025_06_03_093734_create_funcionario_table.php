<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionarioTable extends Migration
{
    public function up()
    {
        Schema::create('funcionario', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->enum('nivel_acesso', ['ADMINISTRADOR', 'CONSULTOR']);
            $table->decimal('salario', 10, 2)->nullable();
            $table->date('data_contratacao')->nullable();
            $table->foreign('id')
                  ->references('id')->on('pessoa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('funcionario', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('funcionario');
    }
}
