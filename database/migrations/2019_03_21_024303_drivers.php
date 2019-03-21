<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Drivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('age')->unsigned();
            $table->date(' birth');
            $table->string('cpf')->unique();
            $table->enum('sex', ['M', 'F']);
            $table->boolean('owner_vehicle');
            $table->enum('cnh_type', ['A', 'B', 'C', 'D', 'E']);
            $table->integer('id_address')->unsigned();
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
        Schema::dropIfExists('tb_drivers');
    }
}
