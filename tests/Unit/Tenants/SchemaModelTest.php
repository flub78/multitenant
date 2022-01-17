<?php

namespace tests\Unit\Tenants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schema;
use Illuminate\Database\QueryException;

class SchemaModelTest extends TestCase
{
    // Clean up the database
    use RefreshDatabase;

    public function test_table_list() {
    	$result = Schema::tableList();
    	$this->assertTrue(count($result) > 0);
    	$this->assertTrue(in_array('users', $result));
    	$this->assertTrue(in_array('roles', $result));
    }
    
    public function test_table_exists () {
    	$this->assertTrue(Schema::tableExists('users'));	
    	$this->assertTrue(Schema::tableExists('user_roles'));
    	$this->assertFalse(Schema::tableExists('unknow_table'));
    }
    
    public function test_table_information() {    	
    	$result = Schema::tableInformation("configurations");
     	$this->assertTrue(count($result) > 3);
     	$this->assertEquals("key", $result[0]->Field);
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
    
    public function test_field_list_exists() {
    	$this->assertTrue(Schema::fieldExists('users', 'email'));
    	$this->assertTrue(Schema::fieldExists('user_roles', 'user_id'));
    	$this->assertFalse(Schema::fieldExists('users', 'unknown_field'));
    	$this->assertFalse(Schema::fieldExists('unknown', 'unknown_field'));
    }
    
    public function test_field_list_unknomw_table() {
    	$list = Schema::fieldList("unknown_table");
    	$this->assertNull($list);
    }
      
    public function test_column_information() {
    	$info = Schema::columnInformation("configurations", "key");
    	$this->assertNotNull($info);
    	
    	$this->assertTrue(Schema::required('configurations', 'key'));
    	$this->assertFalse(Schema::required('roles', 'description'));
    	$required = Schema::required('configurations', 'unknown');
    	$this->assertFalse($required); // non existing are not required
    	    	
    	$info = Schema::columnInformation("users", "password");
    	
    	$comment_str = '{"subtype": "password_with_confirmation", "fillable":"yes", "inTable":"no", "inForm":"yes"}';
    	$this->assertEquals($info->Comment, $comment_str);
    	
    	$comment = Schema::columnComment("users", "password");
    	$this->assertEquals($comment, $comment_str);
    	
    	$info = Schema::columnInformation("configurations", "unknown_key");
    	$this->assertNull($info);
    	
    	$comment = Schema::columnComment("users", "unknown_field");
    	$this->assertEquals('', $comment);
    }

    public function test_column_metadata() {
    	$meta = Schema::columnMetadata("users", "password");
    	$this->assertNotNull($meta);
    	$this->assertEquals('password_with_confirmation', $meta['subtype']);
    	$this->assertEquals('no', $meta['inTable']);
    	
    	$meta = Schema::columnMetadata("users", "email");
    	$this->assertEquals('email', $meta['subtype']);
    	
    	$meta = Schema::columnMetadata("users", "active");
    	$this->assertEquals('checkbox', $meta['subtype']);
    	
    	// empty comment
    	$meta = Schema::columnMetadata("users", "name");
    	$this->assertNull($meta);

    	// unknown table
    	$meta = Schema::columnMetadata("unknow_table", "name");
    	$this->assertNull($meta);
    	
    	// unknown field
    	$meta = Schema::columnMetadata("users", "unknown_field");
    	$this->assertNull($meta);
    	
    	// field with non json comment "Name for the role"
    	$meta = Schema::columnMetadata("roles", "name");
    	$this->assertNull($meta);
    	
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
    	$this->assertTrue(count($types) > 5);
    	$this->assertContains('varchar(255)', $types);
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
    			foreach (['Field', 'Type', 'Null', 'Key', 'Default', 'Extra', 'Comment'] as $attr) {
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
        
    	/*
    	 * With some versions of MySql field sizes are no lonqer included in the DATA_TYPE or COLUMN_TYPE
    	 * max size of the field is in NUMERIC_PRECISION or CHARACTER_MAXIMUM_LENGTH
    	 */
    	$type = Schema::columnType('user_roles', 'user_id');
    	$this->assertTrue('bigint(20) unsigned' == $type ||  'bigint unsigned' == $type);
    	
    	$this->assertEquals(1, Schema::columnSize('users', 'active'));
    	$this->assertEquals(255, Schema::columnSize('users', 'email'));
    	
    	// Bigint has no size in datatype with new versions of MySql
    	// $this->assertEquals(20, Schema::columnSize('user_roles', 'user_id'));	
    	
    	$this->assertEquals(0, Schema::columnSize('user_roles', 'created_at'));
    	
    	$type = Schema::columnType('unknown_table', 'email');
    	$this->assertEquals('', $type);

    	$type = Schema::columnType('users', 'unknown_field');
    	$this->assertEquals('', $type);

    	$this->assertEquals(0, Schema::columnSize('user_roles', 'unknown'));
    	$this->assertEquals(0, Schema::columnSize('unknown', 'unknown'));
    	
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
    	$txt = $this->dumpIndex(Schema::indexList('users'));
    	$txt = $this->dumpIndex(Schema::indexList('user_roles'));
    	// echo "\nindexes : \n$txt";
    	$txt = $this->dumpIndex(Schema::indexList('configurations'));
    	$this->assertNotEquals("", $txt);
    	
    	$il = Schema::indexList('unknown');
    	$this->assertNull($il);
    }
    
    public function test_basic_type() {
    	// tinyint(1)
    	$this->assertEquals('tinyint', Schema::basicType('users', 'active'));
    	
    	// varchar(255)
    	$this->assertEquals('varchar', Schema::basicType('users', 'email'));
    	
    	// bigint(20) unsigned
    	$this->assertEquals('bigint', Schema::basicType('user_roles', 'user_id'));

    	$this->assertEquals('', Schema::basicType('user_roles', 'unknown_field'));
    	$this->assertEquals('', Schema::basicType('unknown_table', 'unknown_field'));
    	
    }

    public function test_integer_type() {
    	// tinyint(1)
    	$this->assertTrue(Schema::integerType('users', 'active'));
    	
    	// varchar(255)
    	$this->assertFalse(Schema::integerType('users', 'email'));
    	
    	// bigint(20) unsigned
    	$this->assertTrue(Schema::integerType('user_roles', 'user_id'));
    	
    	$this->assertFalse(Schema::integerType('unknown', 'email'));
    	$this->assertFalse(Schema::integerType('users', 'unknown'));
    	
    }

    public function test_unsigned_type() {
    	// tinyint(1)
    	$this->assertFalse(Schema::unsignedType('users', 'active'));
    	
    	// varchar(255)
    	$this->assertFalse(Schema::unsignedType('users', 'email'));
    	
    	// bigint(20) unsigned
    	$this->assertTrue(Schema::unsignedType('user_roles', 'user_id'));
    
    	$this->assertFalse(Schema::unsignedType('users', 'unknown'));
    	$this->assertFalse(Schema::unsignedType('unknown', 'unknown'));
    }
    
    public function test_primary_index() {
    	$this->assertEquals('id', Schema::primaryIndex('users'));
    	$this->assertEquals('id', Schema::primaryIndex('user_roles'));
    	$this->assertEquals('key', Schema::primaryIndex('configurations'));
    	
    	// $this->expectException(QueryException::class);
    	$this->assertEquals('', Schema::primaryIndex('non_existing_table'));
    }
    
    public function test_index_info() {
    	$ii = Schema::indexInfo('user_roles', 'user_id');
    	$this->assertEquals("user_roles", $ii->Table);
    	$this->assertEquals("unique_combination", $ii->Key_name);
    	$this->assertEquals("user_id", $ii->Column_name);
    	$this->assertEquals("BTREE", $ii->Index_type);
    	// echo "\nindexInfo\n";
    	// var_dump(Schema::indexInfo('user_roles', 'user_id'));
    	
    	$ii2 = Schema::indexInfo('roles', 'name');

    	$ii = Schema::indexInfo('roles', 'unknown');
    	$this->assertNull($ii);
    	
    	$ii = Schema::indexInfo('unknown', 'unknown');
    	$this->assertNull($ii);
    }
    
    public function test_foreign_key() {
    	$fk = Schema::foreignKey('user_roles', 'user_id');
    	$this->assertEquals('users', $fk->REFERENCED_TABLE_NAME);
    	$this->assertEquals('id', $fk->REFERENCED_COLUMN_NAME);
    	
    	$this->assertEquals(Schema::foreignKeyReferencedTable('user_roles', 'user_id'), $fk->REFERENCED_TABLE_NAME);
    	$this->assertEquals(Schema::foreignKeyReferencedColumn('user_roles', 'user_id'), $fk->REFERENCED_COLUMN_NAME);
    	
    	$this->assertNull(Schema::foreignKey('unknown_table', 'user_id'));
    	$this->assertNull(Schema::foreignKey('user_roles', 'unknown_column'));
    	
    	$this->assertNull(Schema::foreignKeyReferencedTable('unknown_table', 'user_id'));
    	$this->assertNull(Schema::foreignKeyReferencedColumn('unknown_table', 'user_id'));

    	$this->assertNull(Schema::foreignKeyReferencedTable('user_roles', 'unknown_column'));
    	$this->assertNull(Schema::foreignKeyReferencedColumn('user_roles', 'unknown_column'));  	
    }
    
    public function test_unique () {
    	$this->assertFalse(Schema::unique('users', 'name'));
    	$this->assertTrue(Schema::unique('users', 'email'));
    	
    	// below it is the key + value which is unique
    	$this->assertTrue(Schema::unique('configurations', 'key'));
    	$this->assertFalse(Schema::unique('configurations', 'value'));
    	
    	$this->assertFalse(Schema::unique('configurations', 'unknownfield'));
    }
}
