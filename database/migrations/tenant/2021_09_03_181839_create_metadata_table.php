<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author frederic
 *
 * @SuppressWarnings("PMD.ShortMethodName")
 */
class CreateMetadataTable extends Migration {
	
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up() {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->string('table');
            $table->string('field');
            $table->string('subtype')->nullable();
            $table->string('options')->nullable();
            $table->boolean('foreign_key')->nullable();
            $table->string('target_table')->nullable();
            $table->string('target_field')->nullable();
            $table->timestamps();

            $table->unique(['table', 'field'], 'unique_table_field');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('metadata');
    }
}
