<?php

namespace tests\Unit\Tenants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schema;

class SchemaModelTest extends TestCase
{
    // Clean up the database
    use RefreshDatabase;

    public function test_table_list() {
    	$result = Schema::tableList();
    	$this->assertTrue(count($result) > 0);
    	$this->assertTrue(in_array('users', $result));
    }
    
    public function test_table_information() {    	
    	$result = Schema::tableInformation("configurations");   	
     	$this->assertTrue(count($result) > 0);
    }
    
    public function test_table_unknow_table() {
    	$result = Schema::tableInformation("unknown_table");
    	$this->assertNull($result);
    }
    
    public function test_field_list() {
    	$list = Schema::fieldList("configurations");
    	$this->assertTrue(count($list) > 0);
    	$this->assertEquals(['key', 'value', 'created_at', 'updated_at'], $list);
    }
    
    public function test_field_list_unknomw_table() {
    	$list = Schema::fieldList("unknown_table");
    	$this->assertNull($list);
    }
      
    public function test_column_information() {
    	$info = Schema::columnInformation("configurations", "key");
    	$this->assertNotNull($info);	
    }

    public function test_column_information_unknown_table() {
    	$info = Schema::columnInformation("unknown_table", "key");
    	$this->assertNull($info);
    }

    public function test_column_information_unknown_field() {
    	$info = Schema::columnInformation("configurations", "unknown_field");
    	$this->assertNull($info);
    }
    
    public function test_existing_types() {
    	$types = Schema::existingTypes();
    	// echo "\n" . implode("\n", $types);
    	$this->assertTrue(count($types) > 5);
    }
    
    public function test_all() {
    	$cnt = 0;
    	$tables = Schema::tableList();
    	$txt= "\n";
    	foreach ($tables as $table) {
    		$txt .= $table . " : \n";
    		$fields = Schema::fieldList($table);
    		foreach ($fields as $field) {
    			$txt .= "\t$field :\n";
    			$info = Schema::columnInformation($table, $field);
    			foreach (['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'] as $attr) {
    				$txt .= "\t\t$attr=" . $info->$attr . "\n";
    				$cnt++;
    			}    	
    		}
    	}
    	$this->assertTrue($cnt > 10);
    	// echo $txt;
    }
    
    public function test_attributes() {
    	$this->assertEquals('timestamp', Schema::columnType('users', 'created_at'));
    	$this->assertEquals('tinyint(1)', Schema::columnType('users', 'active'));
    	$this->assertEquals('varchar(255)', Schema::columnType('users', 'email'));
    
    	$this->assertEquals('bigint(20) unsigned', Schema::columnType('user_roles', 'user_id'));
    	
    	$this->assertEquals(1, Schema::columnSize('users', 'active'));
    	$this->assertEquals(255, Schema::columnSize('users', 'email'));
    	
    	$this->assertEquals(20, Schema::columnSize('user_roles', 'user_id'));	
    	
    	$this->assertEquals(0, Schema::columnSize('user_roles', 'created_at'));
    }
    

    private function dumpIndex($indexes) {
    	$txt = "\n";
    	foreach ($indexes as $idx) {
    		$txt .= "\n";
    		foreach (['Table', 'Non_unique', 'Key_name', 'Seq_in_index', 'Column_name', 'Collation', 'Cardinality',
    				'Sub_part', 'Packed', 'Null', 'Index_type', 'Comment', 'Index_comment'
    		] as $attr) {
    			$txt .= "\t" . $attr . " - " . $idx->$attr . "\n";
    		}
    	}
    	return $txt;
    }
    
    public function test_indexes() {
    	$this->dumpIndex(Schema::indexList('users'));
    	$this->dumpIndex(Schema::indexList('user_roles'));
    	$this->dumpIndex(Schema::indexList('configurations'));
    	$this->assertTrue(true);
    }
}
