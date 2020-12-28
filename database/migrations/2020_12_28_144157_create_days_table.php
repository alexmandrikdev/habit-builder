<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('day');
            $table->unsignedBigInteger('habit_id');
            $table->foreign('habit_id')->references('id')->on('habits');
            $table->unsignedSmallInteger('silver_stars')->default(0);
            $table->unsignedSmallInteger('golden_stars')->default(0);
            $table->timestamps();

            $table->unique(['habit_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days');
    }
}
