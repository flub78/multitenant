<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\BladeHelper as Blade;
use Illuminate\Support\Facades\URL;

class BladeHelperTest extends TestCase {

	
	public function testSelector() {
		
		$l1 = [];
	
		$l2 = [
				['id' => "F-CDYO", 'name' => "ASK13 - F-CDYO - (CJ)"],
				['id' => "F-CJRG", 'name' => "Ask21 - F-CJRG - (RG)"],
				['id' => "F-CERP", 'name' => "Asw20 - F-CERP - (UP)"],
				['id' => "F-CGKS", 'name' => "Asw20 - F-CGKS - (WE)"],
				['id' => "F-CFXR", 'name' => "xPégase - F-CFXR - (B114)"],				
		];
		
		$s1 = Blade::selector('vpmacid', $l1);
		
		$attrs = ['name' => 'vpmacid', 'id' => 'vpmacid'];
		
		$this->assertEquals('<select class="form-select" name="vpmacid" id="vpmacid">' . "\n" . '</select>', $s1);
		
		$s2 = Blade::selector('vpmacid', $l1, $selected="",  $attrs=$attrs);
		$this->assertEquals('<select name="vpmacid" id="vpmacid" class="form-select">' ."\n</select>", $s2);
		
		$s3 = Blade::selector('vpmacid', $l2, $selected="F-CGKS");
		// echo "\n$s3   \n";
		
		$s4 = Blade::selector_with_null('vpmacid', $l2, $selected="F-CGKS");
		// echo "\n$s4   \n";
	}	
	
	public function test_image() {
		$this->AssertEquals('', Blade::picture('', '', '', ''));
		$this->AssertNotEquals('', Blade::picture("code_gen_type.picture", "34", "picture", "xxx.png", "picture"));
	}
	
	public function test_download() {
		$this->AssertEquals('', Blade::download('', '', '', ''));
		$this->AssertNotEquals('', Blade::download("code_gen_type.picture", "34", "attachment", "xxx.pdf", "attachment"));
	}
	
	public function test_route() {
		$url =  URL::to('/') . '/code_gen_type/picture/32/picture';
		$this->assertEquals($url, route("code_gen_type.picture", ['id' => "32", "field" => "picture"]));
	}
	
	public function test_float() {
		$this->assertNotEquals("", Blade::float(0.0));
		$this->assertEquals("", Blade::float(null));
		// $this->assertIsFloat(Blade::float(0.0));			// weird but they fail ...
		// $this->assertIsFloat(Blade::float(100.0));
		// $this->assertIsFloat(Blade::float(-1000.0));
	}
	
	public function test_currency() {
		$this->assertNotEquals("", Blade::float(0.0));
	}
	
	public function test_upload_name() {
		$un = Blade::upload_name("", "");
		$this->assertEquals(18, strlen($un));
	
		$un = Blade::upload_name("file3.pdf", "roles_name");
		$this->assertEquals(31, strlen($un));
	}
	
	public function test_enumerate() {
		$en = Blade::enumerate("roles_name", "");
		$this->assertEquals("", $en);

		$en = Blade::enumerate("roles_name", "red");
		$this->assertEquals("roles_name.red", $en);		
	}
	
}
