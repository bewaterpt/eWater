<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullStreetDesignationColumnToStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->string('full_street_designation')->nullable();
            $table->index('full_street_designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->dropIndex(['full_street_designation']);
            $table->dropColumn('full_street_designation');
        });
    }
}
