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
            $table->string('source_latitude', 10, 8);
            $table->string('source_longitude', 10, 8);
            $table->string('destiny_latitude', 10, 8);
            $table->string('destiny_longitude', 10, 8);
            $table->boolean('loaded');

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
