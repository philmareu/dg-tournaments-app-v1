<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('posted_at');
            $table->boolean('published');
            $table->string('title');
            $table->string('slug');
            $table->text('summary');
            $table->unsignedInteger('image_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('author_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('post_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
