<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerivedIdColumnAndRearangePrimaryKeyOnMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->dropForeign(['municipality_code']);
        });

        Schema::table('municipalities', function (Blueprint $table) {
            $table->dropPrimary('municipality_code');
            $table->bigInteger('id')->unsigned()->primary();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('municipalities', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->dropColumn('id');
            $table->bigInteger('municipality_code')->unsigned()->primary()->change();
        });
    }
}
