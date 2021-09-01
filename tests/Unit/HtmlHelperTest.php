<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\HtmlHelper;
# use function App\Helpers\HtmlHelper\h1;
# use function App\Helpers\HtmlHelper\p;
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
		$res = HtmlHelper::h1($str);
		# $res = h1($str);
		$this->assertEquals("<H1>Hello world</H1>", $res);
	}
	public function testP() {
		$str = "Hello";
		$res = HtmlHelper::p($str);
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
		
		$s1 = HtmlHelper::selector($l1);
		
		$attrs = ['name' => 'vpmacid', 'id' => 'vpmacid'];
		
		$this->assertEquals("<select>\n</select>", $s1);
		
		$s2 = HtmlHelper::selector($l1, $with_null=false, $selected="",  $attrs=$attrs);
		$this->assertEquals('<select name="vpmacid" id="vpmacid">' ."\n</select>", $s2);
		
		$s3 = HtmlHelper::selector($l2, $with_null=false, $selected="F-CGKS", $attrs=$attrs);
		// echo "\n$s3   \n";
		
		$s4 = HtmlHelper::selector($l2, $with_null=true, $selected="F-CGKS", $attrs=$attrs);
		// echo "\n$s4   \n";
	}
}
