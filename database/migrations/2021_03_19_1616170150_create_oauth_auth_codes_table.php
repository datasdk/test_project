<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAuthCodesTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_auth_codes', function (Blueprint $table) {

		$table->string('id',100);
		$table->bigInteger('user_id',20)->unsigned();
		$table->bigInteger('client_id',20)->unsigned();
		$table->text('scopes')->nullable()->default('NULL');
		$table->tinyInteger('revoked',1);
		$table->datetime('expires_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_auth_codes');
    }
}