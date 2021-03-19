<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->bigInteger('user_id',20)->unsigned()->nullable()->default('NULL');
		$table->string('name');
		$table->string('secret',100)->nullable()->default('NULL');
		$table->string('provider')->nullable()->default('NULL');
		$table->text('redirect');
		$table->tinyInteger('personal_access_client',1);
		$table->tinyInteger('password_client',1);
		$table->tinyInteger('revoked',1);
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}