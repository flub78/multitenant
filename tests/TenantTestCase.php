<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Tenant;
use App\Helpers\TenantHelper;
use App\Helpers\DirHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Tenants\Configuration;
use Illuminate\Support\Facades\App;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;


/**
 * Test for tenants
 * 
 * @author frederic
 *
 */
abstract class TenantTestCase extends BaseTestCase {

	protected $tenancy = true;

	public function setUp(): void {
		parent::setUp();

		if ($this->tenancy) {
			$this->initializeTenancy();
		}
		// $tenant = (tenant('id')) ? tenant('id') : "";
		// $backup_dir = TenantHelper::backup_dirpath($tenant);
		// $backup_msg = is_dir($backup_dir) ? $backup_dir : " no backup dir";
		// echo "test class $tenant = " . get_class($this) . " backup=$backup_msg\n";		
	}

	public function tearDown(): void {

		if ($this->tenancy) {
			// delete the test tenant storage
			DirHelper::rrmdir($this->storage);

			// delete the test tenant database		
			$db_name = "tenant" . $this->tenant_id;;
			$sql = "DROP DATABASE IF EXISTS `$db_name`;";

			// Logically the database can be destroyed after test execution
			// However for forensic it is better to delete it before the test if it exists
			// DB::statement($sql);	

			DirHelper::rrmdir($this->storage);
		}
		parent::tearDown();
	}

	public function domain($tenant_id = "") {
		$parsed = parse_url(env('APP_URL'));
		if ($tenant_id)
			return $tenant_id . '.' . $parsed['host'];
		else
			return $parsed['host'];
	}

	public function initializeTenancy() {
		$tenant_id = "test";
		$domain = $this->domain($tenant_id);

		// Cleanup tenant from previous execution.
		// tenant and storage database must not exist when the test tenant is recreated

		// Reset the domain and tenant database
		DB::statement("SET foreign_key_checks=0");
		DB::statement("DELETE FROM domains;");
		Tenant::truncate();
		DB::statement("SET foreign_key_checks=1");

		// delete the testtenant database
		$db_name = "tenant" . $tenant_id;
		$sql = "DROP DATABASE IF EXISTS `$db_name`;";
		DB::statement($sql);

		// Create a fresh tenant context
		$tenant = Tenant::create(['id' => 'test', 'domain' => $domain]);
		$tenant->domains()->create(['domain' => $domain]);

		tenancy()->initialize($tenant);

		$tenant_id = tenant('id');
		$this->tenant_id = $tenant_id;
		$this->storage = storage_path();  // storage_path is only valid once the tenant created
		$this->backup_dirpath = TenantHelper::backup_dirpath($tenant_id);
		if (!is_dir($this->backup_dirpath)) {
			mkdir($this->backup_dirpath, 0777, true);
		}
	}


	// use CreatesApplication;

	public function createApplication() {
		$app = require __DIR__ . '/../bootstrap/app.php';

		//Load .env.testing environment
		$app->loadEnvironmentFrom('.env.testing');

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	/**
	 * Set the current language
	 * 
	 * @param String $lang
	 */
	public function set_lang(String $lang): void {
		$cfg = Configuration::where('key', 'app.locale')->first();
		if ($cfg) {
			$cfg->value = $lang;
			$cfg->update();
		} else {
			Configuration::factory()->create([
				'key' => 'app.locale',
				'value' => $lang
			]);
		}

		/*
		 *  When LocaleTenancyBootstrapper is executed, it set locale according to the app.locale value in database.
		 *  But the locale is not set while running the tests
		 *
		 *  The database app.locale cannot be set in the test constuctor (too early tenant context are not established)
		 *  And when app.locale is set in test setUp, it's too late, the bootstrapper has already been executed
		 *
		 *  So the local is set again here, before the tests
		 */
		App::setLocale($lang);
	}

	/**
	 * return an url to access a tenant application
	 * 
	 * @param String $sub_url
	 * @return string
	 */
	protected function tenant_url(String $sub_url) {
		$domain =  URL::to('/') . '/' . $sub_url;
		$parsed = parse_url($domain);
		$tenant = tenant("id");
		$query = array_key_exists('query', $parsed) ? $parsed['query'] : '';

		if (str_starts_with($parsed['host'], $tenant . '.')) {
			$host = $parsed['host'];
		} else {
			$host = $tenant . '.' . $parsed['host'];
		}
		$url = $parsed['scheme'] . '://' . $host . $parsed['path'];

		if ($query) {
			$url = $url . '?' . $query;
		}

		return $url;
	}

	/**
	 * Check a tenant get URL
	 * 
	 * @param unknown $user
	 * @param unknown $sub_url
	 * @param array $see_list
	 */
	public function get_tenant_url($user, $sub_url, $see_list = []) {
		$this->be($user);

		$url = $this->tenant_url($sub_url);
		$response = $this->get($url);
		$response->assertStatus(200);

		foreach ($see_list as $see) {
			$response->assertSeeText($see);
		}
		return $response;
	}

	/**
	 * Check a tenant post (or put) method
	 * 
	 * @param unknown $user
	 * @param unknown $sub_url
	 * @param array $elt
	 * @param boolean $errors_expected
	 * @param string $method = post or put
	 */
	public function post_tenant_url($user, $sub_url, $see_list = [], $elt = [], $errors_expected = false, $method = 'post', $errors = []) {
		$this->be($user);
		$this->withoutMiddleware();

		$url = $this->tenant_url($sub_url);

		$response = $this->followingRedirects()->$method($url, $elt);
		$response->assertStatus(200);

		/*
			$response->dumpHeaders();
			$response->dumpSession();
			$response->dump();
		*/

		if ($errors_expected) {
			$response->assertSessionHasErrors($errors);
		} else {
			$response->assertSessionHasNoErrors();
		}
		foreach ($see_list as $see) {
			$response->assertSeeText($see);
		}
		return $response;
	}

	/**
	 * Check tenant put method
	 * @param unknown $user
	 * @param unknown $sub_url
	 * @param array $elt
	 * @param boolean $errors_expected
	 */
	public function put_tenant_url($user, $sub_url, $see_list = [], $elt = [], $errors_expected = false) {
		$this->post_tenant_url($user, $sub_url, $see_list, $elt, $errors_expected, $method = 'put');
	}

	/**
	 * Check tenant delete URL
	 * 
	 * @param unknown $user
	 * @param unknown $sub_url
	 */
	public function delete_tenant_url($user, $sub_url, $see_list = []) {
		$this->be($this->user);
		$url = $this->tenant_url($sub_url);

		$response = $this->followingRedirects()->delete($url);
		$response->assertStatus(200);
		foreach ($see_list as $see) {
			$response->assertSeeText($see);
		}
	}

	/**
	 * Check tenant patch URL
	 * @param unknown $user
	 * @param unknown $sub_url
	 * @param array $elt
	 */
	public function patch_tenant_url($user, $sub_url, $elt = []) {
		$this->be($user);
		$this->withoutMiddleware();

		$url = $this->tenant_url($sub_url);

		$response = $this->patch($url, $elt);
		$response->assertSessionHasNoErrors();
		// $response->dumpSession();

		$response->assertStatus(302);
		return $response;
	}

	/**
	 * Assert a number of occurences of a substring inside a string, the search is delimited 
	 * by two substring.
	 * 
	 * This method can be convenient to test a number of occurences inside an html page.
	 * 
	 * @param unknown $needle
	 * @param unknown $haystack
	 * @param unknown $expected
	 * @param unknown $from
	 * @param unknown $to
	 */
	public function assertOccurencesInString($needle, $haystack, $expected, $from = "", $to = "") {

		$str = $haystack;
		// echo "assertOccurencesInString(needle=$needle, haystack=$haystack, expected=$expected, from=$from, to=$to)\n";
		$start_i = ($from) ? strpos($haystack, $from) : 0;

		$start_i = strpos($haystack, $from);
		// echo "start = $start_i\n";
		$haystack = substr($haystack, $start_i);
		$stop_i = ($to) ? strpos($haystack, $to) : null;
		$haystack = substr($haystack, 0, $stop_i);
		// echo "\$haystack = $haystack\n";
		$count = substr_count($haystack, $needle);
		$this->assertEquals($expected, $count, "$expected occurences of $needle in $str between $from and $to");
	}
}
