<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdColumnAndDropMunicipalityCodePrimaryKeyInMunicipalities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('streets', function (Blueprint $table) {
    //         $table->dropForeign(['municipality_code']);
    //     });
    //     Schema::table('localities', function (Blueprint $table) {
    //         $table->dropForeign(['municipality_code']);
    //     });

    //     Schema::table('municipalities', function (Blueprint $table) {
    //         $table->dropPrSimary('municipality_code');
    //     });

    //     Schema::table('streets', function (Blueprint $table) {
    //         $table->bigInteger('municipality_id')->unsigned();
    //         $table->foreign('municipality_id')->references('id')->on('municipalities');
    //     });
    //     Schema::table('localities', function (Blueprint $table) {
    //         $table->bigInteger('municipality_id')->unsigned();
    //         $table->foreign('municipality_id')->references('id')->on('municipalities');
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::table('streets', function (Blueprint $table) {
    //         $table->dropForeign(['municipality_code']);
    //     });
    //     Schema::table('localities', function (Blueprint $table) {
    //         $table->dropForeign(['municipality_code']);
    //     });

    //     Schema::table('municipalities', function (Blueprint $table) {
    //         $table->dropPrimary('id');
    //         $table->dropColumn('id');
    //         $table->bigInteger('municipality_code')->unsigned()->primary()->change();
    //     });

    //     Schema::table('streets', function (Blueprint $table) {
    //         $table->foreign('municipality_code')->references('municipality_code')->on('municipalities');
    //     });
    //     Schema::table('localities', function (Blueprint $table) {
    //         $table->foreign('municipality_code')->references('municipality_code')->on('municipalities');
    //     });
    // }
}