<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\CodeGenerator as CG;


class CodeGeneratorTest extends TestCase {
	
	public function test_field_label() {
		$label = CG::field_label("calendar_events", "start_date");
		$this->assertEquals('<label for="start_date">{{__("calendar_event.start_date")}}</label>', $label);
	}
	
	public function test_field_input_create() {
		$input = CG::field_input_create("calendar_events", "start_date");
		echo $input;
		$this->assertNotEquals('', $input);
	}
	
	public function test_metadata() {
		$meta = CG::metadata("users");
		$this->assertEquals("users", $meta['table']);
		$this->assertEquals("User", $meta['class_name']);
		$this->assertEquals("user", $meta['element']);
	}
	
}
