<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByAndUpdatedByFieldsToInterruptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interruptions', function (Blueprint $table) {
            // $table->bigInteger('created_by')->unsigned()->nullable()->default(null);
            $table->bigInteger('updated_by')->unsigned()->nullable()->default(null);

            // $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interruptions', function (Blueprint $table) {
            // $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            // $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
