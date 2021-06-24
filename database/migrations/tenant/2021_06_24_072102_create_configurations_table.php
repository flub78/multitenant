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
		 * It is possible to use the key as primary index as it is a unique value.
		 * However it is a bad idea to use user visible keys. Modifications may have
		 * consequences.
		 * 
		 * For example it has been a nightmare to change a primary key make of a user first name
		 * initial and name when a spelling mistake has been discovered. 
		 * 
		 * to be checked, it works fine with tenants where foreign keys are modified when
		 * tenant ids are modified ...
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
