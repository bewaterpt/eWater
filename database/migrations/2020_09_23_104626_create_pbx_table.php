<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('protocol');
            $table->string('url');
            $table->integer('port');
            $table->string('api_base_uri');
            $table->string('username');
            $table->string('password');
            $table->bigInteger('delegation_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('delegation_id')->references('id')->on('delegations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pbx', function (Blueprint $table) {
            $table->dropForeign(['delegation_id']);
        });
        Schema::dropIfExists('pbx');
    }
}
