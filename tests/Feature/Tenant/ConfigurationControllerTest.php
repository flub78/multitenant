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
 * 
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
		$response->assertSeeText('Tenant Configuration');
		$response->assertSeeText('Key');
		$response->assertSeeText('Value');
		$response->assertSeeText(__('general.create') . ' configuration');
		$response->assertSeeText('tenant=');
		$response->assertSeeText(tenant('id'));
		
		// $response->dump();
	}
	
	public function test_create_page() {
		$this->be ( $this->user );
				
		// create a configuration, display the creation form
		$url = 'http://' . tenant('id'). '.tenants.com/configuration/create' ;
		$response = $this->get ( $url );
		$response->assertStatus ( 200 );
		$response->assertSeeText('New configuration');
		$response->assertSeeText('Add a new configuration entry');
	}
	
	public function ttest_store() {
		// Post a creation request
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$value = $configuration->value;
		
		$count = Configuration::count ();

		$url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		echo "\nurl = $url\n";
		$response = $this->post ( $url, ["key" => $key, "value" => $value] );
		$response->assertStatus ( 302 );
		
		$response->dumpHeaders();
		$response->dumpSession();
		$response->dump();
		
		$new_count = Configuration::count ();
		$expected = $count + 1;
		$this->assertEquals ( $expected, $new_count, "a configuration has been created, actual=$new_count, expected=$expected" );		
	}
	
	
	
}
