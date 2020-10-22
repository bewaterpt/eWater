<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('form_field_id')->unsigned();
            $table->json('data');
            $table->string('object_class');
            $table->integer('object_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('form_field_id')->references('id')->on('form_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_data', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['form_field_id']);
        });

        Schema::dropIfExists('form_data');
    }
}
