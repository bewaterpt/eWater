<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('municipalities',function(Blueprint $table){
            $table->dropForeign(['district_code']);
            $table->dropColumn('district_code');
        });

        Schema::table('streets',function(Blueprint $table){
            $table->dropForeign(['district_code']);
            $table->dropColumn('district_code');
        });

        Schema::drop('districts');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('districts', function(Blueprint $table){
            $table->bigInteger('district_code')->unsigned()->primary();
            $table->string('designation');
        });

        Schema::table('streets',function(Blueprint $table){
            $table->bigInteger('district_code');
            $table->foreign('district_code')->references('district_code')->on('districts');
        });

        Schema::table('municipalities',function(Blueprint $table){
            $table->bigInteger('district_code')->unsigned();
            $table->foreign('district_code')->references('district_code')->on('districts');
        });

    }
}
