<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {

		$table->integer('id',11);
		$table->string('name',100);
		$table->string('vat',100);
		$table->string('address',100);
		$table->string('houseNo',100);
		$table->string('zipcode',100);
		$table->string('city',100);
		$table->string('country',100);
		$table->string('phone',100);
		$table->integer('active',11)->default('1');
		$table->timestamp('updated_at')->default('current_timestamp');
		$table->timestamp('created_at')->default('current_timestamp');

        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}