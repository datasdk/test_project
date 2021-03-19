<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasRolesTable extends Migration
{
    public function up()
    {
        Schema::create('model_has_roles', function (Blueprint $table) {

		$table->bigInteger('role_id',20)->unsigned();
		$table->string('model_type');
		$table->bigInteger('model_id',20)->unsigned();

        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
}