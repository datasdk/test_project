<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesPrivotsTable extends Migration
{
    public function up()
    {
        Schema::create('companies_privots', function (Blueprint $table) {

		$table->integer('id',11);
		$table->integer('companies_id',11);
		$table->integer('user_id',11);
		$table->integer('active',11)->default('1');
		$table->timestamp('updated_at')->default('current_timestamp');
		$table->timestamp('created_at')->default('current_timestamp');

        });
    }

    public function down()
    {
        Schema::dropIfExists('companies_privots');
    }
}