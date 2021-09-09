<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\MustacheHelper;
use App;
use Exception;

class MustacheHelperTest extends TestCase {

	const temp1 = "app\Http\Controllers\Tenants\Controller.php";
	const temp2 = "app\Http\Controllers\Tenants\Controller.php.mustache";
	
	public function ttest_template_filename() {

		$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache';
	
		$this->assertEquals($expected, MustacheHelper::template_filename(Self::temp1));
		$this->assertEquals($expected, MustacheHelper::template_filename(Self::temp2));
	}
	
	public function ttest_result_filename () {
		$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\app\Http\Controllers\Tenants\Controller.php';
		
		$this->assertEquals($expected, MustacheHelper::result_filename(Self::temp1));
		$this->assertEquals($expected, MustacheHelper::result_filename(Self::temp2));
		
	}
	
}
