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
		
		// class_name is just a string conversion no control
		$this->assertEquals("UnknowTable", Meta::class_name('unknow_table'));		
		
		$table = '[/$?]';
		$this->assertEquals($table, Meta::class_name($table));
		
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
		$where = ['table' => 'users', 'field' => 'password', 'subtype' => 'password_with_confirmation'];
		$meta_password = Metadata::where($where)->first();
		if (! $meta_password) {
			$meta_password = Metadata::factory($where)->create();
		}
		
		$fields = Meta::derived_fields("users", "name");
		$this->assertEquals(1, count($fields));
		
		$fields = Meta::derived_fields("users", "created_at");
		$this->assertEquals(0, count($fields));
		
		$fields = Meta::derived_fields("users", "password");
		$this->assertEquals(2, count($fields));
		$this->assertEquals(["password", "password_confirmation"], $fields);
		
		$fields = Meta::derived_fields("unknow_table", "password");
		
		$meta_password->delete();
		
	}
	
	public function test_metadata() {
		$meta = Meta::metadata("users");
		// var_dump($meta);	
		$this->assertTrue(true);  // todo remove
	}
	
	public function test_inForm() {
		$this->assertTrue(Meta::inForm('users', 'name'));  
		
		$this->assertTrue(Meta::inForm('users', 'password'));
		
		$this->assertFalse(Meta::inForm('users', 'id'));
		$this->assertFalse(Meta::inForm('users', 'email_verified_at'));
		$this->assertFalse(Meta::inForm('users', 'created_at'));
		
		$this->assertTrue(Meta::inForm('unknow_table', 'password'));
		$this->assertTrue(Meta::inForm('users', 'unknown_field'));
	}
	
	public function test_inTable() {
		$this->assertTrue(Meta::inTable('users', 'name'));   
		
		$this->assertFalse(Meta::inTable('users', 'password'));
		$this->assertFalse(Meta::inTable('users', 'password_confirmation'));	
		
		$this->assertFalse(Meta::inTable('users', 'id'));      
		$this->assertFalse(Meta::inTable('users', 'email_verified_at'));
		$this->assertFalse(Meta::inTable('users', 'created_at'));
		
		$this->assertTrue(Meta::inTable('unknow_table', 'password'));
		$this->assertTrue(Meta::inTable('users', 'unknown_field'));
	}

	public function test_subtype() {
		
		$this->assertEquals("password_with_confirmation", Meta::subtype('users', 'password'));
		
		$this->assertEquals("email", Meta::subtype('users', 'email'));
		$this->assertEquals("checkbox", Meta::subtype('users', 'active'));
		$this->assertEquals("checkbox", Meta::subtype('users', 'admin'));

		$this->assertEquals("", Meta::subtype('users', 'name'));  // unknown subtype
		
		$this->assertEquals("password_confirmation", Meta::subtype('users', 'password_confirmation'));
		
		$this->assertEquals("datetime_date", Meta::subtype('calendar_events', 'start_date'));
		$this->assertEquals("datetime_time", Meta::subtype('calendar_events', 'end_time'));
	}
	
	public function test_type() {
		
		$this->assertEquals("varchar", Meta::type('users', 'password'));
		
		$this->assertEquals("varchar", Meta::type('users', 'email'));
		$this->assertEquals("tinyint", Meta::type('users', 'active'));
		$this->assertEquals("tinyint", Meta::type('users', 'admin'));
		
		$this->assertEquals("varchar", Meta::type('users', 'name'));
		
		$this->assertEquals("datetime", Meta::type('calendar_events', 'start'));
		$this->assertEquals("datetime", Meta::type('calendar_events', 'end'));

		// types of derived fields
		$this->assertEquals("password", Meta::type('users', 'password_confirmation'));
		$this->assertEquals("date", Meta::type('calendar_events', 'start_date'));
		$this->assertEquals("time", Meta::type('calendar_events', 'end_time'));
	}
	
	public function test_fillable_fields() {
		
		$fillable = Meta::fillable_fields('users');
		
		// var_dump($fillable); exit;
		
		$this->assertEquals(["name", "email", "password", "password_confirmation", "admin", "active"], $fillable);
	}
	
	public function test_fillable_names() {
		$fillable_names = Meta::fillable_names('users');
		$this->assertEquals('"name", "email", "password", "password_confirmation", "admin", "active"', $fillable_names);
		
		$fillable_names = Meta::fillable_names('unknow_table');
		$this->assertEquals('', $fillable_names);
	}
	
	public function test_dusk() {
		$dusk = Meta::dusk('users', 'users');
		$this->assertEquals('edit_{{ $users->name }}', $dusk);
	}
}
