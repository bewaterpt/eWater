<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('form_id')->unsigned();
            $table->string('type');
            $table->string('placeholder');
            $table->string('title');
            $table->string('label');
            $table->boolean('required')->default(false);
            $table->boolean('multiple')->default(false);
            $table->string('tag');
            $table->json('options')->nullable();
            $table->string('classes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_id')->references('id')->on('forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
        });

        Schema::dropIfExists('form_fields');
    }
}
