<?php

/**
 * Test cases:
 *
 * Nominal: CRUD testing
 *
 * Error test case:
 *      store of incorrect data is rejected
 * 		delete a non existing configuration
 * 
 * From the Laravel documentation:
 * Unexpected behavior may occur if multiple 
 * requests are executed within a single test method.
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Configuration;

class ConfigurationControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		// create save the instance in database, make just creates a new instance
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}


	public function test_index_page() {
		$this->be ( $this->user );
		
		// configuration list
		$url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText(__('configuration.title'));
		$response->assertSeeText(__('configuration.key'));
		$response->assertSeeText(__('configuration.value'));
		$response->assertSeeText(__('configuration.add') );
		$response->assertSeeText(__('navbar.tenant'));
		$response->assertSeeText(tenant('id'));
		
		// $response->dump();
	}
	
	public function test_create_page() {
		$this->be ( $this->user );
				
		// create a configuration, display the creation form
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/create' ;
		$response = $this->get ( $url );
		$response->assertStatus ( 200 );
		$response->assertSeeText(__('configuration.new'));
	}
	
	public function test_store() {
		// Post a creation request
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$value = $configuration->value;
		
		$count = Configuration::count ();

		$this->withoutMiddleware();
		
		$url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		$elt = ["key" => $key, "value" => $value, '_token' => csrf_token()];
		$response = $this->post ( $url, $elt );
		$response->assertStatus ( 302 );
		
		if (session('errors')) {
			$this->assertTrue(session('errors'), "session has no errors");
		}
		
		// $response->dumpHeaders();
		// $response->dumpSession();
		// $response->dump();
		
		$new_count = Configuration::count ();
		$expected = $count + 1;
		$this->assertEquals ( $expected, $new_count, "configuration created, actual=$new_count, expected=$expected" );		
	}
	
	public function test_store_incorrect_value() {
		// Post a creation request
		$configuration = Configuration::factory()->make();
		$key = "bad_key";
		$value = $configuration->value;
		
		$count = Configuration::count ();
		
		$this->withoutMiddleware();
		
		$url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		$elt = ["key" => $key, "value" => $value, '_token' => csrf_token()];
		$response = $this->post ( $url, $elt );
		$response->assertStatus ( 302 );
		
		if (session('errors')) {
			$this->assertTrue(true, "session has errors");
		} else {
			$this->assertTrue(false, "session has no errors");
		}
		
		// $response->dumpHeaders();
		// $response->dumpSession();
		// $response->dump();
		
		$new_count = Configuration::count ();
		$expected = $count;
		$this->assertEquals ( $expected, $new_count, "configuration not created, actual=$new_count, expected=$expected" );
	}
	
	public function test_show_page() {
		$this->be ( $this->user );
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$configuration->save();
				
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/' . $key ;

		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
	}

	public function test_edit_page() {
		$this->be ( $this->user );
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$configuration->save();
				
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/' . $key .'/edit';
		
		$response = $this->get ( $url);
		$response->assertStatus ( 200 );
		$response->assertSeeText('Edit configuration');		
		// $response->dump();
	}

	public function test_update() {
		$this->be ( $this->user );
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$value = $configuration->value;
		$new_value = "new value";
		
		$this->assertNotEquals($value, $new_value);
		$configuration->save();
		
		$this->withoutMiddleware();
		
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/' . $key;
		$elt = ["key" => $key, "value" => $new_value, '_token' => csrf_token()];
		
		$response = $this->put ( $url, $elt);
		$response->assertStatus ( 302 );
		
		$back = Configuration::where('key', $key)->first();
		
		$this->assertEquals($new_value, $back->value);
		
		$back->delete();
	}
	
	public function test_delete() {
		$this->be ( $this->user );
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$configuration->save();
		
		$count = Configuration::count ();
		
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/' . $key;
		
		$response = $this->delete ( $url);
		$response->assertStatus ( 302 );
		
		$new_count = Configuration::count ();
		$expected = $count - 1;
		$this->assertEquals ( $expected, $new_count, "configuration deleted, actual=$new_count, expected=$expected" );
		
		// $response->dump();
	}
	
}
