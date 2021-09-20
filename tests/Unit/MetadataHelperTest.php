<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\MetadataHelper as Meta;

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
}
