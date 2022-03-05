<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Test on all code generation data types.
 * @author frederic
 *
 */
class CodeGenerationTypes extends Migration
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
    		
    		$table->string('phone')
    			->unique()
    			->comment('{"subtype": "phone"}');
    		
    		$table->text('description')->nullable(true);
    			    		
    		$table->year("year_of_birth")->comment('{"min": "1900", "max":"2099"}');
    		
    		$table->float("weight")->comment('{"min": "3.0", "max":"300.0"}');

    		$table->date("birthday")->comment('{}');

    		$table->time("tea_time")->default("17:00:00")->comment('{}');
    		
    		$table->datetime("takeoff")->comment('{"subtype" : "datetime_with_date_and_time"}');
    		
    		$table ->decimal('price',  $precision = 8, $scale = 2);
    		
    		$table ->double('big_price',  $precision = 11, $scale = 2)->comment('{"subtype" : "currency"}');
    		
    		$table->bigInteger("qualifications")
    		->comment('{"subtype": "bitfield", 
			"values":["redactor","student","pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]}');
    		
    		$table->string('picture')->comment('{"subtype" : "picture"}');

    		$table->string('attachment')->comment('{"subtype" : "attachement", "file_types" : ["txt", "pdf", "jpg"]}');
    		
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
