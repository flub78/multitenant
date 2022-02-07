<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author frederic
 *
 * @SuppressWarnings("PMD.ShortMethodName")
 */
class CreateRolesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortVariable")
     */
	public function up() {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment("Name for the role");
            $table->string('description')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('roles');
    }
}
