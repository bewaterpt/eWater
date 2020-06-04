<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_number')->nullable();
            $table->integer('article_id');
            $table->integer('work_number');
            $table->decimal('unit_price', 9, 2);
            $table->integer('quantity');
            $table->timestamp('entry_date');
            $table->bigInteger('report_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_reports');
    }
}
