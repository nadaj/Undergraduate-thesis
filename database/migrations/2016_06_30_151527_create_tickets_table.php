<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('votings_id')->unsigned();
            $table->foreign('votings_id')->references('id')->on('votings');
            $table->integer('answers_id')->unsigned()->nullable();
            $table->foreign('answers_id')->references('id')->on('answers');
            $table->string('nonce', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tickets');
    }
}
