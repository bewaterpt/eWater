<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('first_phase_approval')->unsigned()->nullable();
            $table->bigInteger('second_phase_approval')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('first_phase_approval')->references('id')->on('users');
            $table->foreign('second_phase_approval')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
