<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->text('description');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->dateTime('reminder_time')->nullable();
            $table->boolean('multiple_answers');
            $table->boolean('show_voters')->default(false);
            $table->integer('min')->default(0);
            $table->integer('max')->default(0);
            $table->integer('status')->default(0);  // 0 - not finished, 1 - success, 2 - failure
            $table->integer('initiator_id')->unsigned();
            $table->foreign('initiator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votings');
    }
}
