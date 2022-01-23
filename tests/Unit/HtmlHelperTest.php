<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\HtmlHelper as HH;
# use function App\Helpers\HtmlHelper\p;
use function App\Helpers\HtmlHelper\h1 as h1;
use App;
use Exception;

class HtmlHelperTest extends TestCase {

	
	/**
	 * h1
	 *
	 * @return void
	 */
	public function testH1() {
		$str = "Hello world";
		$res = HH::h1($str);
		# $res = h1($str);
		$this->assertEquals("<H1>Hello world</H1>", $res);
	}
	public function testP() {
		$str = "Hello";
		$res = HH::p($str);
		$this->assertEquals("<p>Hello</p>", $res);
	}
	
	public function testSelector() {
		
		$l1 = [];
	
		$l2 = [
				['id' => "F-CDYO", 'name' => "ASK13 - F-CDYO - (CJ)"],
				['id' => "F-CJRG", 'name' => "Ask21 - F-CJRG - (RG)"],
				['id' => "F-CERP", 'name' => "Asw20 - F-CERP - (UP)"],
				['id' => "F-CGKS", 'name' => "Asw20 - F-CGKS - (WE)"],
				['id' => "F-CFXR", 'name' => "xPégase - F-CFXR - (B114)"],				
		];
		
		$s1 = HH::selector($l1);
		
		$attrs = ['name' => 'vpmacid', 'id' => 'vpmacid'];
		
		$this->assertEquals("<select>\n</select>", $s1);
		
		$s2 = HH::selector($l1, $with_null=false, $selected="",  $attrs=$attrs);
		$this->assertEquals('<select name="vpmacid" id="vpmacid">' ."\n</select>", $s2);
		
		$s3 = HH::selector($l2, $with_null=false, $selected="F-CGKS", $attrs=$attrs);
		// echo "\n$s3   \n";
		
		$s4 = HH::selector($l2, $with_null=true, $selected="F-CGKS", $attrs=$attrs);
		// echo "\n$s4   \n";
		
		// array with an incorrect format
		$this->expectException(Exception::class);
		
		$l3 = ["app.locale", "app.timezone", "app.currency"];
		$s3 = HH::selector($l3);
	}
	
	public function testSelect() {
		
		$l1 = [];
		
		$l2 = [
				"F-CDYO" => "ASK13 - F-CDYO - (CJ)",
				"F-CJRG" => "Ask21 - F-CJRG - (RG)",
				"F-CERP" => "Asw20 - F-CERP - (UP)",
				"F-CGKS" => "Asw20 - F-CGKS - (WE)",
				"F-CFXR" => "xPégase - F-CFXR - (B114)",
		];
		
		$l3 = ["app.locale", "app.timezone", "app.currency"];
		
		$s1 = HH::select();
		$this->assertMatchesRegularExpression("/select/", $s1);
		$this->assertDoesNotMatchRegularExpression("/option/", $s1);
						
		$attrs = ['name' => 'vpmacid', 'id' => 'vpmacid'];		
		$s2 = HH::select($l1, $with_null=false, $selected="",  $attrs=$attrs);
		
		$this->assertMatchesRegularExpression('/name="vpmacid"/', $s2);
		$this->assertMatchesRegularExpression('/id="vpmacid"/', $s2);
				
		$s3 = HH::select($l2, $with_null=false, $selected="F-CGKS", $attrs=$attrs);
		$this->assertMatchesRegularExpression("/select/", $s3);
		$this->assertMatchesRegularExpression("/option/", $s3);
		$this->assertMatchesRegularExpression('/selected="selected"/', $s3);
		$this->assertMatchesRegularExpression('/Asw20 - F-CERP - \(UP\)/', $s3);
		
		$s4 = HH::select($l3, $with_null=true, $selected="app.timezone", $attrs=$attrs);
		$this->assertMatchesRegularExpression("/select/", $s4);
		$this->assertMatchesRegularExpression("/option/", $s4);
		$this->assertMatchesRegularExpression('/selected="selected"/', $s4);
		$this->assertMatchesRegularExpression('/<option value=""><\/option>/', $s4);
		$this->assertMatchesRegularExpression('/<option value="app.currency">app.currency<\/option>/', $s4);
		$this->assertMatchesRegularExpression('/<option value="app.timezone" selected="selected">app.timezone<\/option>/', $s4);
	}
	
}
