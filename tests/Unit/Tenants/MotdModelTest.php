<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\Motd;

/**
 * Unit test for Motd model
 
 * @author frederic
 *
 */
class MotdModelTest extends TenantTestCase {
        
   /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Motd::count();
        
        // Create an element
        $motd1 = Motd::factory()->create();
        $this->assertNotNull($motd1);
        $latest = Motd::latest()->first();
        $id = $latest->id;
        
        // and a second
        Motd::factory()->create();
        
         // a third to generate values for updates
        $motd3 = Motd::factory()->make();
        $this->assertNotNull($motd3);
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            $this->assertNotEquals($latest->$field, $motd3->$field, "different $field between two random element");
        }
 
        $this->assertTrue(Motd::count() == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('motds',  $initial_count + 2);
                        
        # Read
        $stored = Motd::where(['id' => $id])->first();      
        $this->assertNotNull($stored, "It is possible to retrieve the motd after creation");
        
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            $this->assertEquals($latest->$field, $stored->$field, "Checks the element $field fetched from the database");
        }
        
        // Update
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id")
                $stored->$field = $motd3->$field;
        }
        
        $stored->update();
        
        // get it back
        $back = Motd::where('id', $id)->first();
        $this->assertNotNull($back, "It is possible to retrieve the motd after update");

        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id") {
                $this->assertEquals($back->$field, $motd3->$field, "$field after update");
            }
        }
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $this->assertTrue(Motd::count() == $initial_count + 1, "One less elements in the table");
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "id")
                $this->assertDatabaseMissing('motds', [$field => $motd3->$field]);
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
    	$initial_count = Motd::count();
    	
    	$motd = Motd::factory()->make();
    	$motd->id = "999999999";
    	$motd->delete();
    	
    	$this->assertTrue(Motd::count() == $initial_count, "No changes in database");
    }
    

    public function test_publication_date_mutators() {
        $elt = Motd::factory()->create();
        
        // By default the lang is en
        $en_date_regexp = '/(\d{2})\-(\d{2})\-(\d{4})/i';   
        $this->assertMatchesRegularExpression($en_date_regexp, $elt->publication_date);
        
        // switch to French
        $this->set_lang("fr");

        // and check that the dates are now in French format
        $fr_date_regexp = '/(\d{2})\/(\d{2})\/(\d{4})/i';
        $this->assertMatchesRegularExpression($fr_date_regexp, $elt->publication_date);
            
    }

    public function test_end_date_mutators() {
        $elt = Motd::factory()->create();
        
        // By default the lang is en
        $en_date_regexp = '/(\d{2})\-(\d{2})\-(\d{4})/i';   
        $this->assertMatchesRegularExpression($en_date_regexp, $elt->end_date);
        
        // switch to French
        $this->set_lang("fr");

        // and check that the dates are now in French format
        $fr_date_regexp = '/(\d{2})\/(\d{2})\/(\d{4})/i';
        $this->assertMatchesRegularExpression($fr_date_regexp, $elt->end_date);
            
    }
    
    public function test_currents() {
        $elt = Motd::factory()->create();
        
        $initial_count = Motd::count();
        $elt = Motd::factory()->create();
        
        $this->assertTrue(Motd::count() == $initial_count + 1, "No changes in database");
        
        $elt->delete();
        $this->assertEquals(1, Motd::count(), "Back to 1");
        
        $currents = Motd::currents();
        $initial_currents = count($currents); 
        
        $elt1 = ['title' => 'in the past',
            'message' => 'Humanity has landed on the moon',
            'publication_date' => '07-20-1969',          //  m-d-Y English local format
            'end_date' => '12-31-1969'
        ]; 
        $elt = Motd::factory()->create($elt1);
        $this->assertEquals(2, Motd::count(), "After creation");
       
        $elt2 = ['title' => 'in the future',
            'message' => 'Humanity has landed on mars',
            'publication_date' => '07-20-2039',          //  m-d-Y English local format
            'end_date' => '12-31-2069'
        ];
        $elt = Motd::factory()->create($elt2);
        $this->assertEquals(3, Motd::count(), "Another one");
        
        // $el1 and $el2 should not be returned, today is out of range
        $this->assertEquals($initial_currents, count(Motd::currents()), "No display");
        
        // $el3 and $elt4 should be returned until 2069
        $elt3 = ['title' => 'Active',
            'message' => 'Humanity has landed on the moon again',
            'publication_date' => '07-20-1969',          //  m-d-Y English local format
            'end_date' => '12-31-2069'
        ];
        $elt = Motd::factory()->create($elt3);
        
        // It is mandatory to specify null or the factory will use a random
        // not null value
        $elt4 = ['title' => 'Another Active',
            'message' => 'Humanity has not yet landed on mars',
            'publication_date' => '07-20-1969',          //  m-d-Y English local format
            'end_date' => null
        ];
        $elt = Motd::factory()->create($elt4);
        $this->assertEquals($initial_currents + 2, count(Motd::currents()), "To be displayed");
        
    }
}
