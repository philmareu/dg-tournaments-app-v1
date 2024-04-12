<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_tournament', function (Blueprint $table) {
            $table->unsignedInteger('tournament_id');
            $table->unsignedInteger('division_id');
            $table->unsignedTinyInteger('quantity')->nullable();
            $table->unsignedInteger('cost')->nullable();
            $table->timestamps();

            $table->primary(['division_id']);

            $table->foreign('tournament_id')->references('id')->on('tournaments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('division_tournament');
    }
}
