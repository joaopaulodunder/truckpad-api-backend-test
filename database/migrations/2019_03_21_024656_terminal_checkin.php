<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TerminalCheckin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_terminal_checkins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id')->unsigned();
            $table->integer('truck_type_id')->unsigned();
            $table->integer('source_address')->unsigned();
            $table->integer('destiny_address')->unsigned();
            $table->enum('loaded', ['YES', 'NO']);
            $table->foreign('driver_id')->references('id')->on('tb_drivers');
            $table->foreign('truck_type_id')->references('id')->on('tb_truck_types');

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
        Schema::dropIfExists('tb_terminal_checkins');
    }
}
