<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelFulltextTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_fulltext', function (Blueprint $table) {

		$table->integer('id',10)->unsigned();
		$table->integer('indexable_id',11);
		$table->string('indexable_type');
		$table->text('indexed_title');
		$table->text('indexed_content');
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_fulltext');
    }
}