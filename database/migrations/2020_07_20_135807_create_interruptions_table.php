<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterruptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interruptions', function (Blueprint $table) {
            $table->id();
            $table->integer('work_id');
            $table->timestamp('start_date');
            $table->timestamp('reinstatement_date')->nullable();
            $table->boolean('scheduled')->default(0);
            $table->bigInteger('delegation_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('delegation_id')->references('id')->on('delegations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interruptions');
    }
}
