<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {

		$table->string('id',100);
		$table->bigInteger('user_id',20)->unsigned()->nullable()->default('NULL');
		$table->bigInteger('client_id',20)->unsigned();
		$table->string('name')->nullable()->default('NULL');
		$table->text('scopes')->nullable()->default('NULL');
		$table->tinyInteger('revoked',1);
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');
		$table->datetime('expires_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
}