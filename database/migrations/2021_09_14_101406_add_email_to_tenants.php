<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up() // NOPMD
    {
        Schema::table('tenants', function (Blueprint $table) {
        	$table->string('email')->unique()->nullable();
        	$table->string('db_name')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
        	$table->dropColumn('email');
        	$table->dropColumn('db_name');
        });
    }
}
