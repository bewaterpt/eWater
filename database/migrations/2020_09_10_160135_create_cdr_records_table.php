<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCdrRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * callid,timestart,callfrom,callto,callduraction,talkduraction,srctrunkname,dsttrunkname,status,type,pincode,recording,didnumber,sn
     * 1589299528.4,2020-05-12 17:05:28,966024422,110,42,34,MEO,,ANSWERED,Inbound,,,,369392534749
     */
    public function up()
    {
        Schema::create('cdr_records', function (Blueprint $table) {
            $table->id();
            $table->string('callid');
            $table->timestamp('timestart');
            $table->string('callfrom', 15);
            $table->string('callto', 15);
            $table->smallInteger('callduration');
            $table->smallInteger('talkduration');
            $table->smallInteger('waitduration');
            $table->string('srctrunkname')->nullable();
            $table->string('dsttrunkname')->nullable();
            $table->string('status');
            $table->string('type');
            $table->string('pincode')->nullable();
            $table->string('recording')->nullable();
            $table->string('didnumber', 15)->nullable();
            $table->string('sn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cdr_records');
    }
}
