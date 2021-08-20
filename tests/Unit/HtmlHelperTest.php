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
	
}
