<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\CodeGenerator as CG;
use App\Models\Tenants\Metadata;
use App\Helpers\MetadataHelper as Meta;
use App\Helpers\CgFactory;

class CodeGeneratorTest extends TestCase {

	protected $cg;

    function Setup(): void {
		parent::setUp();
        $this->cg = CgFactory::instance();
    }
	
	public function test_array_to_string() {
        $this->assertEquals('[]', $this->cg->array_to_string([]));
        $this->assertEquals('["one", "two", "three"]', $this->cg->array_to_string(['one', 'two', 'three']));
    }
	
	public function test_field_label() {
		$label = $this->cg->field_label("calendar_events", "start");
		$this->assertEquals('<label class="form-label" for="start">{{__("calendar_event.start")}}</label>', $label);
	}
	
	public function test_dusk() {
		$dusk = CG::dusk('users', 'users');
		$this->assertEquals('edit_{{ $users->name }}', $dusk);
	}
	
	public function test_field_input_create() {
		$input = $this->cg->field_input_create("calendar_events", "start_date");
		// echo $input;
		$this->assertNotEquals('', $input);
	}
	
	public function test_metadata() {
		$meta = CG::metadata("users");
		$this->assertEquals("users", $meta['table']);
		$this->assertEquals("User", $meta['class_name']);
		$this->assertEquals("user", $meta['element']);
	}

	public function test_metadata_with_derived_fields() {
	    $meta = CG::metadata("code_gen_types");
	    $this->assertEquals("code_gen_types", $meta['table']);
	    $this->assertEquals("CodeGenType", $meta['class_name']);
	    $this->assertEquals("code_gen_type", $meta['element']);
	}
	
	
	public function test_enumerate () {
		$table = "configurations";
		$field = "key";
		$elt = ['table' => $table, 'field' => $field, "subtype" => "enumerate",
				"options" => '{"values":["app.locale", "app.timezone", "browser.locale"]}'];
		$metadata = Metadata::factory()->make($elt);
		$metadata->save();
		
		$input = $this->cg->field_input_create($table, $field);
		$this->assertNotEquals('', $input);
		
		$rules = $this->cg->field_rule_create($table, $field);
		
		$metadata->delete();
	}
	
	public function test_form_field_list() {
		$table = "code_gen_types";
		$list = CG::form_field_list($table);
		$this->assertTrue(count($list) > 10);
		$this->assertTrue(count($list) < 20);
		$this->assertTrue(is_array($list[0]));
		foreach (["name", "display", "label"] as $key) {
			$this->assertTrue(array_key_exists($key, $list[0]));
		}
	}
	
	public function test_factory_field_list() {
		$table = "code_gen_types";
		$list = CG::factory_field_list($table);
		$this->assertTrue(count($list) > 10);
		$this->assertTrue(count($list) < 20);
		$this->assertTrue(is_array($list[0]));
		foreach (["name", "display", "label"] as $key) {
			$this->assertTrue(array_key_exists($key, $list[0]));
		}
	}
	
	public function test_select_list() {
		$sel = CG::select_list("code_gen_types");
		$this->assertEquals(1, count($sel));
	
		$sel = CG::select_list("roles");
		$this->assertEquals(0, count($sel));
	}
	
	public function test_fillable() {
		$this->assertTrue(true);
		
		$fillable = Meta::fillable_names("code_gen_types");
		
		$this->assertNotContains("\"qualifications_boxes\"", explode(", ", $fillable));
		$this->assertContains("\"qualifications\"", explode(", ", $fillable));
	}
	
	public function test_to_camel_case() {
	    $str = "";
	    $res = CG::toCamelCase($str);
	    $this->assertEquals("", $res);
	    
	    $str = "start_date";
	    $res = CG::toCamelCase($str);
	    $this->assertEquals("StartDate", $res);
	}
	
	public function test_lot_of_values() {
	    $this->assertFalse(CG::lot_of_values("code_gen_types", "black_and_white"));
	    $this->assertFalse(CG::lot_of_values("code_gen_types", "color_name"));
	    
	    $this->assertTrue(CG::lot_of_values("code_gen_types", "name"));
	    $this->assertTrue(CG::lot_of_values("code_gen_types", "phone"));
	    $this->assertTrue(CG::lot_of_values("code_gen_types", "price"));
	    $this->assertTrue(CG::lot_of_values("code_gen_types", "name"));
	}
	
}
