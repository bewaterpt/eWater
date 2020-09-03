<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffectedAreaAndCoordinatesColumnsToInterruptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interruptions', function (Blueprint $table) {
            $table->string('affected_area');
            $table->string('coordinates');
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
            $table->dropColumn('affected_area');
            $table->dropColumn('coordinates');
        });
    }
}
