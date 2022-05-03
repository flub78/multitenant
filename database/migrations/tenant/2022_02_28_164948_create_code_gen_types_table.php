<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Test on all code generation data types.
 * @author frederic
 *
 */
class CreateCodeGenTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortMethodName")
     */
    public function up()
    {
    	Schema::create('code_gen_types', function (Blueprint $table) {
    		$table->id();
    		
    		$table->string('name')->unique()->comment("User name");
    		
    		$table->string('phone')->nullable()
    			->unique()
    			->comment('{"subtype": "phone"}');
    		
    		$table->text('description')->nullable(true);
    			    		
    		// MySql years are in 1901 .. 2155
    		$table->year("year_of_birth")->nullable()->comment('{"min": "1901", "max":"2099"}');
    		
    		$table->float("weight")->nullable()->comment('{"min": "3.0", "max":"300.0"}');

    		$table->date("birthday")->nullable()->comment('{}');

    		$table->time("tea_time")->nullable()->default("17:00:00")->comment('{}');
    		
    		$table->datetime("takeoff")->nullable()->comment('{"subtype" : "datetime_with_date_and_time"}');
    		
    		$table ->decimal('price',  $precision = 8, $scale = 2)->nullable();
    		
    		$table ->double('big_price',  $precision = 11, $scale = 2)->nullable()->comment('{"subtype" : "currency"}');
    		
    		$table->bigInteger("qualifications")->nullable()
    		->comment('{"subtype": "bitfield", 
			"values":["redactor","student","pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]}');
    		
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
