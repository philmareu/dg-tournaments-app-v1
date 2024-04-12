<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFlagsTableAddType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->unsignedInteger('flag_type_id');
            $table->dateTime('review_on')->default(DB::raw("NOW()"));
            $table->foreign('flag_type_id')->references('id')->on('flag_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->dropForeign('flags_flag_type_id_foreign');
            $table->dropColumn('review_on');
            $table->dropColumn('flag_type_id');
        });
    }
}
