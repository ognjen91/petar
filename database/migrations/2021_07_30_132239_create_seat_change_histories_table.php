<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatChangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_change_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('excursion_id');
            $table->foreign('excursion_id')->references('id')->on('excursions')->onDelete('cascade');
            $table->unsignedBigInteger('seats_old_value');
            $table->unsignedBigInteger('seats_new_value');
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
        Schema::dropIfExists('seat_change_histories');
    }
}
