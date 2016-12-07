<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLocationAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_location_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('user_id');
            $table->integer('event_id');
            $table->integer('event_location_id');
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('contact_number');
            $table->timestamp('hit_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_location_answers');
    }
}
