<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempusers', function (Blueprint $table) {
            $table->increments('tempuser_id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_initiator');
            $table->integer('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('department_id')->on('departments');
            $table->integer('title_id')->unsigned()->nullable();
            $table->foreign('title_id')->references('title_id')->on('titles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tempusers');
    }
}
