<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_items', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_lancamento')->nullable();
            $table->integer('codigo_artigo');
            $table->integer('numero_obra');
            $table->decimal('preco_unitario', 9, 2);
            $table->integer('quantidade');
            $table->date('data_documento');
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_reports');
    }
}
