<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\BladeHelper as Blade;

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
		$this->AssertEquals('', Blade::image('', '', ''));
		$this->AssertNotEquals('', Blade::image("img.png", "code_gen_type", "picture"));
	}
	
	public function test_download() {
		$this->AssertEquals('', Blade::download('', '', ''));
		$this->AssertNotEquals('', Blade::download("img.pdf", "code_gen_type", "attachment"));
	}
	
}
