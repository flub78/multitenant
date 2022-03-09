<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\CodeGenType;

/**
 * Unit test for CodeGenType model
 
 * @author frederic
 *
 */
class CodeGenTypeModelTest extends TenantTestCase {
	
        	
	/**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = CodeGenType::count();
       
        // Create an element
        $code_gen_type1 = CodeGenType::factory()->create();
        $this->assertNotNull($code_gen_type1);
        $latest = CodeGenType::latest()->first();
        $id = $latest->id;
        
        // and a second
        CodeGenType::factory()->create();
        
         // a third to generate values for updates
        $code_gen_type3 = CodeGenType::factory()->make();
        $this->assertNotNull($code_gen_type3);
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertNotEquals($latest->$field, $code_gen_type3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(CodeGenType::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('code_gen_types',  $initial_count + 2);
                        
        # Read
        $stored = CodeGenType::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the code_gen_type after creation");
        
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $stored->$field = $code_gen_type3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = CodeGenType::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the code_gen_type after update");

        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $code_gen_type3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(CodeGenType::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $this->assertDatabaseMissing('code_gen_types', [$field => $code_gen_type3->$field]);
        }
    }
    
    
    /**
     * Test delete
     * Given the database server is on
     * And the schema exists in database
     * When deleting a non exiting element
     * Then nothing change in database
     */
    public function test_deleting_non_existing_element () {
    	$initial_count = CodeGenType::count();
    	
    	$code_gen_type = CodeGenType::factory()->make();
    	$code_gen_type->id = "999999999";
    	$code_gen_type->delete();
    	
    	$this->assertTrue(CodeGenType::count() == $initial_count, "No changes in database");
    }
    
    public function test_birthday_mutators() {
    	$cgt = CodeGenType::factory()->create();
    	
    	// By default the lang is en
    	$en_date_regexp = '/(\d{2})\-(\d{2})\-(\d{4})/i'; 	
    	$this->assertMatchesRegularExpression($en_date_regexp, $cgt->birthday);
    	
    	// switch to French
    	$this->set_lang("fr");

    	// and check that the dates are now in French format
    	$fr_date_regexp = '/(\d{2})\/(\d{2})\/(\d{4})/i';
    	$this->assertMatchesRegularExpression($fr_date_regexp, $cgt->birthday);	  	
    }
    
    public function test_takeoff_mutators() {
    	$cgt = CodeGenType::factory()->create();
    	    	
    	// By default the lang is en
    	$en_date_regexp = '/(\d{2})\-(\d{2})\-(\d{4})\s(\d{2})\:(\d{2})/i';
    	$this->assertMatchesRegularExpression($en_date_regexp, $cgt->takeoff);
    	
    	// switch to French
    	$this->set_lang("fr");
    	
    	// and check that the dates are now in French format
    	$fr_date_regexp = '/(\d{2})\/(\d{2})\/(\d{4})\s(\d{2})\:(\d{2})/i';
    	$this->assertMatchesRegularExpression($fr_date_regexp, $cgt->takeoff);
    }
    
    public function test_derived_attributes() {
    	// create an object with a well known takeoff datetime
    	$date = "07-30-2022";
    	$time = "13:14";
    	$datetime = "$date $time";
    	$cgt = CodeGenType::factory()->create(["takeoff" => $datetime]);
    	
    	// check the accessors
    	$this->assertEquals($datetime, $cgt->takeoff);
    	$this->assertEquals($date, $cgt->takeoff_date);
    	$this->assertEquals($time, $cgt->takeoff_time);
    	
    	// change the date
    	$new_date = "07-31-2022";
    	$cgt->takeoff_date = $new_date;
    	// check that the date has been changed
    	$this->assertEquals("$new_date $time", $cgt->takeoff);
    	$this->assertEquals($new_date, $cgt->takeoff_date);
    	$this->assertEquals($time, $cgt->takeoff_time);
    	
    	// change the time
    	$new_time = "15:16";
    	$cgt->takeoff_time = $new_time;
    	// check that the time has been change
    	$this->assertEquals("$new_date $new_time", $cgt->takeoff);
    	$this->assertEquals($new_date, $cgt->takeoff_date);
    	$this->assertEquals($new_time, $cgt->takeoff_time);

    	// Switch to French
    	$this->set_lang("fr");
    	
    	// the current date in French
    	$fr_date = "31/07/2022";
    	
    	// check that I have the correct values in the correct local
    	$this->assertEquals("$fr_date $new_time", $cgt->takeoff);
    	$this->assertEquals($fr_date, $cgt->takeoff_date);
    	$this->assertEquals($new_time, $cgt->takeoff_time);
    	
    	// change the date providing a French date
    	$new_fr_date = "14/07/2022";
    	$cgt->takeoff_date = $new_fr_date; 
    	
    	$this->assertEquals("$new_fr_date $new_time", $cgt->takeoff);
    	$this->assertEquals($new_fr_date, $cgt->takeoff_date);
    	$this->assertEquals($new_time, $cgt->takeoff_time);
    	
    	// echo "\ntakeoff = " . $cgt->takeoff . "\n";
    	// echo "takeoff_date = " . $cgt->takeoff_date . "\n";
    	// echo "takeoff_time = " . $cgt->takeoff_time . "\n";
    }
    
}
