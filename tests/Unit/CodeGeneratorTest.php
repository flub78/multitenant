<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\CodeGenerator as CG;
use App\Models\Tenants\Metadata;



class CodeGeneratorTest extends TestCase {
	
	public function test_field_label() {
		$label = CG::field_label("calendar_events", "start_date");
		$this->assertEquals('<label for="start_date">{{__("calendar_event.start_date")}}</label>', $label);
	}
	
	public function test_dusk() {
		$dusk = CG::dusk('users', 'users');
		$this->assertEquals('edit_{{ $users->name }}', $dusk);
	}
	
	public function test_field_input_create() {
		$input = CG::field_input_create("calendar_events", "start_date");
		// echo $input;
		$this->assertNotEquals('', $input);
	}
	
	public function test_metadata() {
		$meta = CG::metadata("users");
		$this->assertEquals("users", $meta['table']);
		$this->assertEquals("User", $meta['class_name']);
		$this->assertEquals("user", $meta['element']);
	}
	
	public function test_enumerate () {
		$table = "configurations";
		$field = "key";
		$elt = ['table' => $table, 'field' => $field, "subtype" => "enumerate",
				"options" => '{"values":["app.locale", "app.timezone", "browser.locale"]}'];
		$metadata = Metadata::factory()->make($elt);
		$metadata->save();
		
		$input = CG::field_input_create($table, $field);
		$this->assertNotEquals('', $input);
		var_dump($input);
		
		$rules = CG::field_rule_create($table, $field);
		var_dump($rules);
		
		$metadata->delete();
		
	}
}
