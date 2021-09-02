<?php

namespace tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schema;

class SchemaModelTest extends TestCase
{
    // Clean up the database
    use RefreshDatabase;
            
    public function test_table_information() {    	
    	$result = Schema::tableInformation("configurations");   	
     	$this->assertTrue(count($result) > 0);
    }
    
    public function test_field_list() {
    	$list = Schema::fieldList("configurations");
    	$this->assertTrue(count($list) > 0);
    	$this->assertEquals(['key', 'value', 'created_at', 'updated_at'], $list);
    }
    
    public function test_column_information() {
    	$info = Schema::columnInformation("configurations", "key");
    	var_dump($info);
    	$this->assertNotNull($info);	
    }
}
