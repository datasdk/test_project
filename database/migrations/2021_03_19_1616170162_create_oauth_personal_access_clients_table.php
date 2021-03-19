<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    public function up()
    {
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {

		$table->bigInteger('id',20)->unsigned();
		$table->bigInteger('client_id',20)->unsigned();
		$table->timestamp('created_at')->nullable()->default('NULL');
		$table->timestamp('updated_at')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_personal_access_clients');
    }
}