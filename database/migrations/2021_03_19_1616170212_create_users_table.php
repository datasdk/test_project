<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->string('username',100)->default('');
		$table->string('firstname');
		$table->string('lastname',100);
		$table->string('phone',100)->default('');
		$table->string('email');
		$table->timestamp('email_verified_at')->nullable()->default('NULL');
		$table->string('password');
		$table->string('remember_token',100)->nullable()->default('NULL');
		$table->timestamp('lastLoggedIn')->default('current_timestamp');
		$table->timestamp('userSince')->default('current_timestamp');
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}