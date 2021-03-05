<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressInterruptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('street_interruption', function (Blueprint $table) {
            $table->unsignedBigInteger('street_id');
            $table->unsignedBigInteger('interruption_id');

            $table->foreign('street_id')->references('id')->on('streets');
            $table->foreign('interruption_id')->references('id')->on('interruptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('street_interruption', function (Blueprint $table) {
            $table->dropForeign(['street_id']);
            $table->dropForeign(['interruption_id']);
        });
        Schema::dropIfExists('street_interruption');
    }
}
