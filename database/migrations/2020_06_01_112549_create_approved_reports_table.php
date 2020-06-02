<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('report_id')->unsigned();
            $table->boolean('synced');
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('pending_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_reports');
    }
}
