<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->bigInteger('municipality_code')->unsigned()->primary();
            $table->bigInteger('district_code')->unsigned();
            $table->timestamps();

            $table->foreign('district_code')->references('district_code')->on('districts');
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
            $table->dropForeign(['district_code']);
        });

        Schema::dropIfExists('municipalities');
    }
}
