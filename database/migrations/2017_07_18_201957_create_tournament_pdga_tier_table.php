<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentPdgaTierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdga_tier_tournament', function (Blueprint $table) {
            $table->unsignedInteger('tournament_id');
            $table->unsignedInteger('pdga_tier_id');
            $table->timestamps();

            $table->primary(['tournament_id', 'pdga_tier_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pdga_tier_id')->references('id')->on('pdga_tiers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdga_tier_tournament');
    }
}
