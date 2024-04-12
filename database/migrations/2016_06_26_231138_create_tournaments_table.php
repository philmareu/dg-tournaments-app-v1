<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('country');
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->date('start');
            $table->date('end');
            $table->string('authorization_email')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('format_id');
            $table->unsignedInteger('poster_id')->nullable();
            $table->string('paypal')->nullable();
            $table->text('description')->nullable();
            $table->string('timezone')->default('America/Chicago');
            $table->unsignedInteger('stripe_account_id')->nullable();
            $table->unsignedInteger('data_source_id')->nullable();
            $table->string('data_source_tournament_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('format_id')->references('id')->on('formats')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('data_source_id')->references('id')->on('data_sources')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('poster_id')->references('id')->on('uploads')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
