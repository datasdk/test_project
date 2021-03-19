<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutingTable extends Migration
{
    public function up()
    {
        Schema::create('routing', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('slug',1000);
		$table->string('redirect',300)->nullable()->default('NULL');
		$table->string('view',100);
		$table->integer('ref_id',11);
		$table->string('model',100);
		$table->integer('active',11);

        });
    }

    public function down()
    {
        Schema::dropIfExists('routing');
    }
}