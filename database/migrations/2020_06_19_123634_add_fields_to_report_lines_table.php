<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToReportLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_lines', function (Blueprint $table) {
            $table->integer('driven_km');
            $table->string('worker');
            $table->dropColumn('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_lines', function (Blueprint $table) {
            $table->dropColumn('driven_km');
            $table->dropColumn('worker');
            $table->float('unit_price');
        });
    }
}
