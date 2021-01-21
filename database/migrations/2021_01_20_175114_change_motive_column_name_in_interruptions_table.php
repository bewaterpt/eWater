<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMotiveColumnNameInInterruptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interruptions', function (Blueprint $table) {
            $table->dropForeign(['motive']);
            $table->renameColumn('motive', 'motive_id');
            $table->foreign('motive_id')->references('id')->on('interruption_motives');
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
            $table->dropForeign(['motive_id']);
            $table->renameColumn('motive_id', 'motive');
            $table->foreign('motive')->references('id')->on('interruption_motives');
        });
    }
}
