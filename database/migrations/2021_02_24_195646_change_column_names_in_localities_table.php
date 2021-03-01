<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNamesInLocalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('localities', function (Blueprint $table) {
            $table->integer('locality_code')->change();
            $table->renameColumn('locality_code', 'code');
            $table->renameColumn('locality_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('localities', function (Blueprint $table) {
            $table->unsignedBigInteger('code')->change();
            $table->renameColumn('code', 'locality_code');
            $table->renameColumn('name', 'locality_name');
        });
    }
}
