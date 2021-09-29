<?php

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\Metadata;
use Illuminate\Database\QueryException;
use Exception;

/**
 * UserRole model unit test
 * 
 * @author frederic
 *
 */
class MetadataModelTest extends TenantTestCase

{
	
    /**
     * Test element creation, read, update and delete
     * Given the database server is on
     * Given the schema exists in database
     * When creating an element
     * Then it is stored in database, it can be read, updated and deleted
     */
    public function testCRUD () {
    	        
        $initial_count = Metadata::count();
        
        // Create
        $metadata1 = Metadata::create(['table' => "users", 'field' => "email", "subtype" => "email"]);
        
        
        // and a second
        $metadata2 = Metadata::factory()->make(['table' => "user_roles", 'field' => "role_id", "subtype" => "foreign_key"]);
        $metadata2->save();   // set $role to null
        
        $count = Metadata::count();
        $this->assertTrue($count == $initial_count + 2, "Two new elements in the table");
        $this->assertDatabaseCount('metadata',  $initial_count + 2);
                        
        # Read
        $stored = Metadata::where(['table' => "users", "field" => "email"])->first();
        $this->assertNotNull($stored, "It is possible to retrieve the metadata");
        
        $this->assertEquals("users", $stored->table, "Checks the element fetched from the database");
        $this->assertEquals("email", $stored->field, "Checks the element fetched from the database");
        $this->assertEquals("email", $stored->subtype, "Checks the element fetched from the database");
        
        // Update
        $new_subtype = "phone";
        $stored->subtype = $new_subtype;
        
        $id = $stored->id;
        
        $stored->update();
        
        $back = Metadata::find($id);
        $this->assertEquals($new_subtype, $back->subtype, "After update");
        
        // Delete
        $stored->delete();   
        $this->assertDeleted($stored);
        $count = Metadata::count();
        $this->assertTrue($count == $initial_count + 1, "One less elements in the table");
    }
    
    
    public function test_deleting_non_existing_element () {
    	$initial_count = Metadata::count();
    	
    	$metadata = Metadata::factory()->make();
    	$metadata->id = 999999999;
    	$metadata->delete();
    	
    	$count = Metadata::count();
    	$this->assertTrue($count == $initial_count, "No changes in database");
    }
            
    public function test_duplicate_are_rejected () {
    	// Create
   		Metadata::create(['table' => "users", 'field' => "email", "subtype" => "email"]);
   		$this->expectException(QueryException::class);
   		Metadata::create(['table' => "users", 'field' => "email", "subtype" => "email"]);
    }
    
    public function test_incorrect_table_or_field_are_rejected () {
    	$this->expectException(Exception::class);
    	Metadata::create(['table' => "unknown", 'field' => "email", "subtype" => "email"]);
     }
    
    public function test_missing_table_or_field_are_rejected () {
    	$this->expectException(Exception::class);
    	Metadata::create(['table' => "users", "subtype" => "email"]);
    }
    
    public function test_model_checks_on_update () {
    	$meta1 = Metadata::create(['table' => "users", 'field' => "email", "subtype" => "email"]);
    	$meta2 = Metadata::create(['table' => "users", 'field' => "name"]);
    	
    	$stored = Metadata::where(['table' => "users", "field" => "email"])->first();

    	$this->expectException(Exception::class);
    	$stored->table = "unknown_table";
    	$stored->update();
 
    	$this->expectException(Exception::class, 'Exception raised on duplicate');
    	$stored->table = "users";
    	$stored->field = "name";
    	$stored->update();   	
     }
    
    public function test_full_name () {
    	$meta1 = Metadata::create(['table' => "users", 'field' => "email", "subtype" => "email"]);
    	
    	$this->assertEquals('metadata users.email',  $meta1->full_name);
    	$this->assertEquals('metadata users.email',  $meta1->short_name);
    }
    
    public function test_attributes () {
    	$table = "users";
    	$field = "email";
    	$subtype = "email";
    	$options = "size=12, readonly=true";
    	$foreign_key = 1;
    	
    	$meta = Metadata::create(
    			['table' => $table,
    			'field' => $field,
    			"subtype" => $subtype,
    			"options" => $options,
    			"foreign_key" => $foreign_key
    			]);
    	
    	$this->assertEquals($table,  $meta->table);
    	$this->assertEquals($field,  $meta->field);
    	$this->assertEquals($subtype,  $meta->subtype);
    }
}
