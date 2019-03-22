<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Adresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street');
            $table->integer('number');
            $table->string('neighborhood');
            $table->string('state');
            $table->string('city');
            $table->string('cep');
            $table->string('latitude', 100, 8);
            $table->string('longitude', 100, 8);
            $table->timestamps();

//            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
