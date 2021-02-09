<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function(Blueprint $table){
            $table->dropForeign(['failure_type_id']);
        });

        Schema::drop('materials');

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('category_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('materials');

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->bigInteger('failure_type_id')->unsigned();
            $table->foreign('failure_type_id')->references('id')->on('failure_types');
            $table->timestamps();
        });
    }
}
