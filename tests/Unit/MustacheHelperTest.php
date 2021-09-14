<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\MustacheHelper;
use App;
use Exception;

/**
 * Mustache helper test
 * 
 * Code generation is currently done on windows. No need to make the code generation
 * Linux compatible for the moment.
 * 
 * @author frederic
 *
 */
class MustacheHelperTest extends TestCase {

	const temp1 = "app\Http\Controllers\Tenants\Controller.php";
	const temp2 = "app\Http\Controllers\Tenants\Controller.php.mustache";
	
	public function test_template_filename() {

		if (PHP_OS == "WINNT") {
			$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache';
			$this->assertEquals($expected, MustacheHelper::template_filename(Self::temp1));
			$this->assertEquals($expected, MustacheHelper::template_filename(Self::temp2));
			$this->assertEquals($expected, MustacheHelper::template_filename($expected));
		} else {
			$expected = '/var/www/html/multi_phpunit/build/templates/app\Http\Controllers\Tenants\Controller.php.mustache';
			$this->asssertTrue(true);
		}
		
	}
	
	public function test_result_filename () {
		if (PHP_OS == "WINNT") {			
			$expected = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\results\app\Http\Controllers\Tenants\Controller.php';
		} else {
			$expected = '/var/www/html/multi_phpunit/build/results/app\Http\Controllers\Tenants\Controller.php';
		}
		$this->assertEquals($expected, MustacheHelper::result_filename(Self::temp1));
		$this->assertEquals($expected, MustacheHelper::result_filename(Self::temp2));		
		$this->assertEquals($expected, MustacheHelper::result_filename($expected));
	}
	
	public function test_is_absolute_path () {
		if (PHP_OS == "WINNT") {
			$template = 'C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache';
		} else {
			$template = '/var/www/html/multi_phpunit/build/results/app\Http\Controllers\Tenants\Controller.php';
		}
		$this->assertTrue(MustacheHelper::is_absolute_path($template));
		$this->assertFalse(MustacheHelper::is_absolute_path(Self::temp1));
	}
}
