<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('answers_id')->unsigned();
            $table->foreign('answers_id')->references('id')->on('answers');
            $table->integer('tickets_id')->unsigned();
            $table->foreign('tickets_id')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answers_tickets');
    }
}
