<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatorioTable extends Migration
{
    public function up()
    {
        Schema::create('relatorio', function (Blueprint $table) {
            $table->id();
            $table->date('data_relatorio');
            $table->string('formato', 50)->nullable();
            $table->enum('tipo_relatorio', ['DOACOES_RECEBIDAS', 'DOACOES_DISTRIBUIDAS', 'DOACOES_TODAS']);
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('funcionario_id');
            $table->index('funcionario_id');
            $table->foreign('funcionario_id')
                  ->references('id')->on('funcionario')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('relatorio', function (Blueprint $table) {
            $table->dropForeign(['funcionario_id']);
            $table->dropIndex(['funcionario_id']);
        });
        Schema::dropIfExists('relatorio');
    }
}
