<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToLocationCodeInStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('streets', function (Blueprint $table) {
            $table->bigInteger('locality_code')->unsigned()->change();
            $table->foreign('locality_code')->references('locality_code')->on('localities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->dropForeign(['locality_code']);
        });
    }
}
