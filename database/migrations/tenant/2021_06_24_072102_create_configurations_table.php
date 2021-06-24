<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		
		/*
		 * Laravel seems to update foreign keys when a primary key field is updated. (to be confirmed).
		 * If not, usage of primary keys visible to the user is not recommended.
		 */
		
		Schema::create ( 'configurations', function (Blueprint $table) {
			$table->primary ( 'key' );
			$table->string ( 'key' );
			$table->string ( 'value' );
			$table->timestamps ();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists ( 'configurations' );
	}

}
