<?php

/**
 * Test the artisan mustache commands nominal and negative test cases
 *
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\TenantHelper;
use Symfony\Component\Console\Exception\RuntimeException;

class ArtisanMustacheTest extends TenantTestCase {

	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
	}

	function __destruct() {
		$this->user->delete ();
	}
		
	public function test_setup() {
		$this->assertTrue(is_dir($this->backup_dirpath));	
	}
	

	public function test_mustache_info() {
		$exitCode = Artisan::call("mustache:info users");
		$this->assertEquals($exitCode, 0, "No error on mustache:info");
	}

	public function test_mustache_info_unknown_table() {
		$exitCode = Artisan::call("mustache:info unknown_table");
		$this->assertEquals($exitCode, 1, "Error on mustache:info unknown_table");
	}
	
	// ############################################################################## // 
	
	
	public function test_mustache_generate_users_create_directory () {
		$dir = getcwd() . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'results';
		$dir1 = $dir . DIRECTORY_SEPARATOR . 'test';
		$dir2 = $dir1 . DIRECTORY_SEPARATOR . 'testing';
		$file = $dir2 . DIRECTORY_SEPARATOR . 'UserModel.php';
		
		if (file_exists($file)) unlink($file);
		$this->assertFileDoesNotExist($file);
		
		if (is_dir($dir2)) rmdir($dir2);
		$this->assertFileDoesNotExist($dir2);
		
		if (is_dir($dir1)) rmdir($dir1);
		$this->assertFileDoesNotExist($dir1);
		
		$exitCode = Artisan::call("mustache:generate users Model.php.mustache test/testing/UserModel.php");
		$this->assertEquals($exitCode, 0, "No errors");
		
		$this->assertFileExists($file);
		$this->assertFileExists($dir1);
		$this->assertFileExists($dir2);
	}
	
	public function test_mustache_generate_not_enought_parameters() {
		try {
			$exitCode = Artisan::call("mustache:generate");
			$this->assertTrue(false, "Exception not raised");
		} catch (RuntimeException $e) {
			$this->assertTrue(true, "Expected exception raised");
		}
	}
		
	public function test_mustache_generate_unknown_table() {
		$exitCode = Artisan::call("mustache:generate unknown_table Model.php.mustache app/Models/UserModel.php");
		$this->assertEquals($exitCode, 1, "Error");
	}
	
	public function test_mustache_generate_users_unknown_model() {
		$exitCode = Artisan::call("mustache:generate users Modelnotfound.php.mustache app/Models/UserModel.php");
		$this->assertEquals($exitCode, 1, "Template not found");
	}
	
	public function test_mustache_generate_users() {
		$exitCode = Artisan::call("mustache:generate users Model.php.mustache app/Models/UserModel.php");
		$this->assertEquals($exitCode, 0, "No errors");
	}
	
	public function test_mustache_generate_users_create_view() {
		$exitCode = Artisan::call("mustache:generate users resources/views/tenants/create_view.blade.php.mustache resources/views/users/create.blade.php");
		$this->assertEquals($exitCode, 0, "No errors");
	}

	public function test_mustache_generate_users_index_view() {
		$exitCode = Artisan::call("mustache:generate --verbose users resources/views/tenants/index.blade.php.mustache resources/views/users/index.blade.php");
		$this->assertEquals($exitCode, 0, "No errors");
	}
	
}
