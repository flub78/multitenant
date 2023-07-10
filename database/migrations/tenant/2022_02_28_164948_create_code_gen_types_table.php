<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the code_gen_types table
 
 * @author frederic
 *
 */
class CreateCodeGenTypesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortMethodName")
     */
    public function up() {
    
    	Schema::create('code_gen_types', function (Blueprint $table) {
    		$table->id();
    		
    		$table->string('name')->unique()->comment('{"in_filter":"yes"}');
    		
    		$table->string('phone')->nullable()
    			->unique()
    			->comment('{"subtype": "phone"}');
    		
    		$table->text('description')->nullable(true)->comment('{"in_filter":"yes"}');
    			    		
    		// MySql years are in 1901 .. 2155
    		$table->year("year_of_birth")->nullable()->comment('{"min": "1901", "max":"2099", "in_filter":"yes"}');
    		
    		$table->float("weight")->nullable()
			->default(75.0)
			->comment('{"min": "3.0", "max":"300.0"}');

    		$table->date("birthday")->nullable()->comment('{"in_filter":"yes"}');

    		$table->time("tea_time")->nullable()->default("17:00:00")->comment('{"in_filter":"yes"}');
    		
    		$table->datetime("takeoff")->nullable()->comment('{"subtype" : "datetime","in_filter":"yes"}');
    		
    		$table ->decimal('price',  $precision = 8, $scale = 2)->nullable()->comment('{"subtype" : "currency","in_filter":"yes"}');
    		
    		$table ->double('big_price',  $precision = 11, $scale = 2)->nullable()->comment('{"subtype" : "currency"}');
    		
    		$table->bigInteger("qualifications")->nullable()
    		->comment('{"subtype": "bitfield", 
			"values":["redactor","student","pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]}');
    		
    		$table->boolean('black_and_white')->default(false)->comment('{"subtype": "checkbox"}');
    		
    		$table->string("color_name")->nullable()
    		->comment('{"subtype": "enumerate",
			"values":["blue","red","green", "white", "black"]}');
    		
    		$table->string('picture')->nullable()->comment('{"subtype" : "picture"}');

    		$table->string('attachment')->nullable()->comment('{"subtype" : "file", "file_types" : ["txt", "pdf", "jpg"]}');
    		
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::dropIfExists('code_gen_types');
    }
}
