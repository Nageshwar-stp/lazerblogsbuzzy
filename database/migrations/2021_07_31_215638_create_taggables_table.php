<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->unsignedInteger('tag_id')->index();
            $table->unsignedInteger('taggable_id')->index();
            $table->string('taggable_type', 80)->index();
            $table->unsignedInteger('user_id')->index()->nullable();

            $table->unique(['tag_id', 'taggable_id', 'user_id', 'taggable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taggables');
    }
}
