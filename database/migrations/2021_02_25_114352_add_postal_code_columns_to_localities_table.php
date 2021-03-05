<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostalCodeColumnsToLocalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('localities', function (Blueprint $table) {
            $table->integer('postal_code_number');
            $table->integer('postal_code_extension');
        });

        DB::raw(
            'UPDATE localities 
                SET postal_code_number = (SELECT postal_code_number FROM streets WHERE streets.locality_id = localities.id), 
                postal_code_extension = (SELECT postal_code_extension FROM streets WHERE streets.locality_id = localities.id)'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('localities', function (Blueprint $table) {
            $table->dropColumn(['postal_code_number', 'postal_code_extension']);
        });
    }
}
