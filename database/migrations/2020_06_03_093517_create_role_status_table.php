<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_status', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_status', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::dropIfExists('role_status');
    }
}
