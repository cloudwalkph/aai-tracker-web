<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_polls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('poll_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            // Foreign indexes
            $table->foreign('event_id')->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->foreign('poll_id')->references('id')
                ->on('polls')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_polls');
    }
}
