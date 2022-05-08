<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\Profile;

/**
 * Unit test for Profile model
 
 * @author frederic
 *
 */
class ProfileModelTest extends TenantTestCase {
        
   /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Profile::count();
        
        // Create an element
        $profile1 = Profile::factory()->create();
        $this->assertNotNull($profile1);
        $latest = Profile::latest()->first();
        $id = $latest->id;
        
        // and a second
        Profile::factory()->create();
        
         // a third to generate values for updates
        $profile3 = Profile::factory()->make();
        $this->assertNotNull($profile3);
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            $this->assertNotEquals($latest->$field, $profile3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(Profile::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('profiles',  $initial_count + 2);
                        
        # Read
        $stored = Profile::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the profile after creation");
        
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            if ($field != "id")
                $stored->$field = $profile3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Profile::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the profile after update");

        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $profile3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Profile::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            if ($field != "id")
                $this->assertDatabaseMissing('profiles', [$field => $profile3->$field]);
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
    	$initial_count = Profile::count();
    	
    	$profile = Profile::factory()->make();
    	$profile->id = "999999999";
    	$profile->delete();
    	
    	$this->assertTrue(Profile::count() == $initial_count, "No changes in database");
    }
    

    public function test_birthday_mutators() {
        $elt = Profile::factory()->create();
        
        // By default the lang is en
        $en_date_regexp = '/(\d{2})\-(\d{2})\-(\d{4})/i';   
        $this->assertMatchesRegularExpression($en_date_regexp, $elt->birthday);
        
        // switch to French
        $this->set_lang("fr");

        // and check that the dates are now in French format
        $fr_date_regexp = '/(\d{2})\/(\d{2})\/(\d{4})/i';
        $this->assertMatchesRegularExpression($fr_date_regexp, $elt->birthday);
            
    }
}
