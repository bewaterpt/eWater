<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesIdColumnAndRearangePrimaryKeyOnStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->bigInteger('municipality_id')->unsigned();
            $table->dropForeign(['municipality_code']);
            $table->dropColumn('municipality_code');
            $table->foreign('municipality_id')->references('id')->on('municipalities');
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
            $table->dropForeign(['municipality_code']);
            $table->foreign('municipality_code')->references('municipality_code')->on('municipalities');
            $table->dropColumn('municipality_id');
            $table->bigInteger('municipality_code')->unsigned();
        });
    }
}
