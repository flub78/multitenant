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
	const temp3 = 'translation.php';
	# C:\Users\frede\Dropbox\xampp\htdocs\multitenant\build\templates\app\Http\Controllers
	const win_root = 'C:\Users\frede\Dropbox\xampp\htdocs\multitenant';
	
	public function test_template_filename() {

	    # This test does not pass under eclipse as the getcwd does not return the same thing that when the
	    # test is run from command line. 
	    # TODO: refactor the template_filename function to find the templates another way
	    
		if (PHP_OS == "WINNT") {
		    $expected = strtolower(Self::win_root . '\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache');
		    $this->assertEquals($expected, strtolower(MustacheHelper::template_filename(Self::temp1)));
		    $this->assertEquals($expected, strtolower(MustacheHelper::template_filename(Self::temp2)));
						
		    $expected = strtolower(Self::win_root . '\build\templates\translation.php.mustache');
		    $this->assertEquals($expected, strtolower(MustacheHelper::template_filename(Self::temp3)));
			
			$this->expectException(Exception::class);
			$this->assertEquals($expected, MustacheHelper::template_filename("zorglub"));
			
		} else {
			// code generation is not supported on Linux
			$expected = '/var/www/html/multi_phpunit/build/templates/app\Http\Controllers\Tenants\Controller.php.mustache';
			$this->assertTrue(true);
		}
	}
	
	public function test_result_filename () {
		if (PHP_OS == "WINNT") {			
		    $expected = strtolower(Self::win_root . '\build\results\app\Http\Controllers\Tenants\Controller.php');
		} else {
			$this->assertTrue(true);
			return;  // Curently code generation is not supported on Linux
			$expected = '/var/www/html/multi_phpunit/build/results/app\Http\Controllers\Tenants\RoleController.php';
		}
		$this->assertEquals($expected, strtolower(MustacheHelper::result_filename(Self::temp1)));
		$this->assertEquals($expected, strtolower(MustacheHelper::result_filename(Self::temp2)));		
		$this->assertEquals($expected, strtolower(MustacheHelper::result_filename($expected)));
	}
	
	public function test_is_absolute_path () {
		if (PHP_OS == "WINNT") {
		    $template = Self::win_root . '\build\templates\app\Http\Controllers\Tenants\Controller.php.mustache';
		} else {
			$template = '/var/www/html/multi_phpunit/build/results/app\Http\Controllers\Tenants\Controller.php';
		}
		$this->assertTrue(MustacheHelper::is_absolute_path($template));
		$this->assertFalse(MustacheHelper::is_absolute_path(Self::temp1));
	}
	
	public function test_template_file () {
		$templates = [ "controller","model","request","index","create","edit","english","french", "translation"];
		foreach ($templates as $template) {
			$str = MustacheHelper::template_file('roles', $template);
			// echo "\ntemplate $template = $str";
			$this->assertNotEquals("", $str);
		}
	}
	
	public function test_result_file () {
		$templates = [ "controller","model","request","index","create","edit","english","french", "test_model", "test_controller"];
		foreach ($templates as $template) {
			$str = MustacheHelper::result_file('roles', $template);
			// echo "\nresult $template = $str";
			$this->assertNotEquals("", $str);
		}
	}
	
	public function test_translation_result_file () {

		if (PHP_OS == "WINNT") {
		    $template = strtolower(MustacheHelper::translation_result_file('role', 'fr'));
		    $expected = strtolower(Self::win_root . '\build\results\resources\lang\fr\role.php');
			$this->assertEquals($expected, $template);
			
			$template = strtolower(MustacheHelper::translation_result_file('configuration', 'en'));
			$expected = strtolower(Self::win_root . '\build\results\resources\lang\en\configuration.php');
			$this->assertEquals($expected, $template);
			
			
			$template = strtolower(MustacheHelper::translation_result_file('role', 'fr', true));
			$expected = strtolower(Self::win_root . '\resources\lang\fr\role.php');
			$this->assertEquals($expected, $template);
			
		} else {
			// code generation not supported on Linux
			$this->assertTrue(true);
		}
	}
	
	public function test_source_language_file () {
		if (PHP_OS == "WINNT") {
			$source = MustacheHelper::source_language_file("configuration");
			$expected = 'resources\lang\en\configuration.php';
			$this->assertEquals($expected, $source);
		} else {
			// code generation not supported on Linux
			$this->assertTrue(true);
		}
	}
	
	public function test_migration_name() {
		if (PHP_OS == "WINNT") {
			
			// Look for an existing migration
			$filename = MustacheHelper::migration_name("calendar_events");
			$expected = 'database\migrations\tenant\2021_06_18_165312_create_calendar_events_table.php';
			$this->assertEquals($expected, $filename);
			
			// non existing migration
			$filename = MustacheHelper::migration_name("non_existing_elements", true);			
			$pattern = '/(\d{4}_\d{2}_\d{2}_\d{6}_create_)(\w+)(_table\.php)/';
			$this->assertMatchesRegularExpression($pattern, $filename);
			
		} else {
			// code generation not supported on Linux
			$this->assertTrue(true);
		}
	}
}
