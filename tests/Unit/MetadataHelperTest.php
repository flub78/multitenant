<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\MetadataHelper as Meta;
use App\Models\Tenants\Metadata;


class MetadataHelperTest extends TestCase {

	
	public function testClassName() {
		$table = "users";
		$this->assertEquals("User", Meta::class_name($table));		
		$this->assertEquals("UserRole", Meta::class_name("user_roles"));
	}
	
	public function test_case_conversion() {
		$str = "test_class_name";
		$this->assertEquals("testClassName", Meta::underscoreToCamelCase($str));
		$this->assertEquals("TestClassName", Meta::underscoreToCamelCase($str, true));
	}
	
	public function test_element() {
		$this->assertEquals("user", Meta::element("users"));
	}
	
	public function test_derived_fields () {
		$meta_password = Metadata::factory()->create(['table' => 'users', 'field' => 'password', 'subtype' => 'password_with_confirmation']);
		
		$fields = Meta::derived_fields("users", "name");
		$this->assertEquals(1, count($fields));
		
		$fields = Meta::derived_fields("users", "created_at");
		$this->assertEquals(0, count($fields));
		
		$fields = Meta::derived_fields("users", "password");
		// $this->assertEquals(2, count($fields));
		// $this->assertEquals(["password", "password_confirm"], $fields);
		
		$fields = Meta::derived_fields("unknow_table", "password");
		
		$meta_password->delete();
		
	}
	
	public function test_metadata() {
		$meta = Meta::metadata("users");
		var_dump($meta);
		
		$this->assertTrue(true);
	}
	
}
