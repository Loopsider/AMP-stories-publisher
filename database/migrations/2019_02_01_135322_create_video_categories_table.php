<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('video_categories', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name')->unique();
          $table->string('slug')->nullable();
          $table->string('description')->nullable();
          $table->timestamps();
      });
      Schema::table('videos', function (Blueprint $table) {
        $table->unsignedInteger('video_category_id')->nullable();
        $table->foreign('video_category_id')->references('id')->on('video_categories')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('videos', function (Blueprint $table) {
          $table->dropForeign('videos_video_category_id_foreign');
      });
      Schema::drop('video_categories');
    }
}
