<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPbxIdFieldToCdrRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cdr_records', function (Blueprint $table) {
            $table->bigInteger('pbx_id')->unsigned();

            $table->foreign('pbx_id')->references('id')->on('pbx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cdr_records', function (Blueprint $table) {
            $table->dropforeign(['pbx_id']);
            $table->dropColumn('pbx_id');
        });
    }
}
