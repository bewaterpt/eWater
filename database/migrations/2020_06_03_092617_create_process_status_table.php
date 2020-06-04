<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_status', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('process_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('previous_status')->unsigned();
            $table->bigInteger('failover_role')->unsigned();
            $table->bigInteger('failover_user')->unsigned();
            $table->longText('comment');
            $table->timestamp('concluded_at');
            $table->timestamps();

            $table->foreign('process_id')->references('id')->on('reports');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('previous_status')->references('id')->on('process_status');
            $table->foreign('failover_role')->references('id')->on('roles');
            $table->foreign('failover_user')->references('id')->on('users');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_statuses');
    }
}
