<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiarioTable extends Migration
{
    public function up()
    {
        Schema::create('beneficiario', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->integer('limite')->nullable();
            $table->string('cartao_benef', 100)->nullable();
            $table->enum('status_conta', ['CONTA_APROVADA', 'CONTA_NEGADA', 'CONTA_EM_ANALISE']);
            $table->foreign('id')
                  ->references('id')->on('pessoa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('beneficiario', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('beneficiario');
    }
}
