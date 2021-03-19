<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration
{
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->string('uuid');
		$table->text('connection');
		$table->text('queue');
		;
		;
		$table->timestamp('failed_at')->default('current_timestamp');

        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}