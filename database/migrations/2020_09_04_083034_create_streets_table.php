<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('district_code')->unsigned();
            $table->bigInteger('municipality_code')->unsigned();
            $table->integer('locality_code');
            $table->string('locality_name');
            $table->integer('artery_code')->nullable();
            $table->string('artery_type')->nullable();
            $table->string('primary_preposition')->nullable();
            $table->string('artery_title')->nullable();
            $table->string('secondary_preposition')->nullable();
            $table->string('artery_designation')->nullable();
            $table->string('section')->nullable();
            $table->string('door_number')->nullable();
            $table->string('client_name')->nullable();
            $table->string('postal_code', 4);
            $table->string('postal_code_extension', 3);
            $table->string('postal_designation');
            $table->timestamps();

            $table->foreign('district_code')->references('district_code')->on('districts');
            $table->foreign('municipality_code')->references('municipality_code')->on('municipalities');

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
            $table->dropForeign(['district_code']);
            $table->dropForeign(['municipality_code']);
        });

        Schema::dropIfExists('streets');
    }
}
