<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\MustacheHelper;
use App;
use Exception;

class MustacheHelperTest extends TestCase {

	const temp1 = "app\Http\Controllers\Tenants\Controller.php";
	const temp2 = "app\Http\Controllers\Tenants\Controller.php.mustache";
	
	public function test_template_filename() {

		$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache';
	
		// TODO Adaptation to Linux
		// $this->assertEquals($expected, MustacheHelper::template_filename(Self::temp1));
		// $this->assertEquals($expected, MustacheHelper::template_filename(Self::temp2));
		$this->assertTrue(true);
	}
	
	public function test_result_filename () {
		$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\app\Http\Controllers\Tenants\Controller.php';
		
		// TODO Adaptation to Linux
		
		// $this->assertEquals($expected, MustacheHelper::result_filename(Self::temp1));
		// $this->assertEquals($expected, MustacheHelper::result_filename(Self::temp2));
		$this->assertTrue(true);
		
	}
	
}
