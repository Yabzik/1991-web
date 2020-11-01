<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('index')->nullable();
            $table->date('date');
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->string('faculty');
            $table->tinyInteger('course');
            $table->string('speciality');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('seen')->default(0);
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
        Schema::dropIfExists('events');
    }
}
