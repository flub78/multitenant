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
		
		$this->assertEquals("color", Meta::subtype('calendar_events', 'textColor'));
		
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

		$this->assertEquals("varchar", Meta::type('calendar_events', 'textColor'));

		$this->assertEquals("datetime", Meta::type('code_gen_types', 'takeoff'));
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
	

	public function test_metada_from_table_overwrite_comments () {
		
		// Let's use a field for which metadata are defined in comments
		$this->assertEquals("password_with_confirmation", Meta::subtype('users', 'password'));
		
		// create an entry in the metadata table
		$metadata = Metadata::factory()->make(['table' => "users", 'field' => "password", "subtype" => "zorglub"]);
		$metadata->save();   
		
		// check that it is overwriting
		$this->assertEquals("zorglub", Meta::subtype('users', 'password'));
		
		// delete the metadata table element
		$metadata->delete();
		
		// Check that we are back to comment value
		$this->assertEquals("password_with_confirmation", Meta::subtype('users', 'password'));
	}
	
	public function test_metadata_enumerate() {
		$this->assertEquals("", Meta::subtype('configurations', 'key'));
		
		$elt = ['table' => "configurations", 'field' => "key", "subtype" => "enumerate",
				"options" => '{"values":["app.locale", "app.timezone", "browser.locale"]}'];
		$metadata = Metadata::factory()->make($elt);
		$metadata->save();
		
		$this->assertEquals("enumerate", Meta::subtype('configurations', 'key'));
		
		$metadata->delete();
		
		$options = Meta::field_metadata("calendar_events", "allDay");
		$this->assertEquals('checkbox', $options['subtype']);
		
		$elt = ['table' => "calendar_events", 'field' => "allDay", "subtype" => "enumerate",
				"options" => '{"values":["app.locale", "app.timezone", "browser.locale"]}'];
		$metadata = Metadata::factory()->make($elt);
		$metadata->save();
		
		$options = Meta::field_metadata("calendar_events", "allDay");
		$this->assertEquals("enumerate", Meta::subtype('calendar_events', 'allDay'));
		$this->assertEquals('app.locale', $options['values'][0]);
		
		$metadata->delete();
	}
	
	/**
	 * Just trying to figure out the json_encode/decode limitations and constraints
	 */
	public function test_json_decode() {
		$str = '';
		$json = json_decode($str, true);
		$this->assertNull($json, "json_decode of an empty string returns null");

		$str = '{"values":["app.locale", "app.timezone", "browser.locale"]}';
		$json = json_decode($str, true);
		$this->assertTrue(array_key_exists('values', $json));
		$this->assertEquals('app.timezone', $json['values'][1]);
		
		$str = '{"values":["app.locale", "app.timezone", "browser.locale"],"rules_to_add":["regex:/\w+\.\w+(\.\w+)*/", "Rule::in($this->valid_configs)"]}';
		$str = '{"values":["app.locale", "app.timezone", "browser.locale"]';
		$str .= ', "rules_to_add": "rules"';
		$str .= '}';
		$json = json_decode($str, true);
		$this->assertNotNull($json, "json_decode($str) not null");
				
		$str = '{"values":["app.locale", "app.timezone", "browser.locale"]';
		$str .= ', "rules_to_add": ["rule1", "rule2"]';
		$str .= '}';
		$json = json_decode($str, true);
		$this->assertNotNull($json, "json_decode($str) not null");

		$str = '{"values":["app.locale", "app.timezone", "browser.locale"]';
		$str .= ', "rules_to_add": ["rule1", "Rule::in($this->valid_configs)"]';
		$str .= '}';
		$json = json_decode($str, true);
		// var_dump($str);
		// var_dump($json);
		$this->assertNotNull($json, "json_decode($str) not null");

		$j = [
				"values" => ["app.locale", "app.timezone", "browser.locale"],
				"rules_to_add" => ["rule1", "rule2"]
		];
		$str = json_encode($j);
		// var_dump($str);
	
		$j = [
				"values" => ["app.locale", "app.timezone", "browser.locale"],
				"rules_to_add" => ['regex:/\w+\.\w+(\.\w+)*/', 'Rule::in($this->valid_configs)']
		];
		$str = json_encode($j);
		// var_dump($str);
		$json = json_decode($str, true);
		// var_dump($json);
		$this->assertNotNull($json, "json_decode($str) not null");
		
	}
	
	public function test_datetime() {
		$table = "code_gen_types";
		$field = "takeoff";
		$this->assertEquals("datetime", Meta::type($table, $field));
		
		$table = "code_gen_types";
		$field = "birthday";
		$this->assertEquals("date", Meta::type($table, $field));
		
		$table = "code_gen_types";
		$field = "tea_time";
		$this->assertEquals("time", Meta::type($table, $field));
	}
	
}
