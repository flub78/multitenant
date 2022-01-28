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
		$this->get_tenant_url($this->user, 'configuration',
			[__('configuration.title'), __('configuration.key'), __('configuration.value'), __('configuration.add'), __('navbar.tenant'), tenant('id')]);
	}
	
	public function test_create_page() {
		$this->get_tenant_url($this->user, 'configuration/create', [__('configuration.new')]);
	}
	
	public function test_store() {
		// Post a creation request
		$configuration = Configuration::factory()->make(['key' => 'app.locale', 'value' => 'en']);
		$key = $configuration->key;
		$value = $configuration->value;
		$elt = ["key" => $key, "value" => $value, '_token' => csrf_token()];
		
		$initial_count = Configuration::count ();
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'configuration', ['created'], $elt);
		
		$new_count = Configuration::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "configuration created, actual=$new_count, expected=$expected" );		
	}
			
	public function test_store_incorrect_value() {
		// Post a creation request
		$bad_key = "app_bad_key";
		$configuration = Configuration::factory()->make(['key' => $bad_key, 'value' => 'en']);
		$value = $configuration->value;
		
		$initial_count = Configuration::count ();
				
		// $url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		$elt = ["key" => $bad_key, "value" => $value, '_token' => csrf_token()];

		// 'The key format is invalid'
		$this->post_tenant_url( $this->user, 'configuration', [], $elt, $errors_expected = true, 'post',
				['key' => 'The key format is invalid.']);
		
		/* 
		$errors = session('errors');
		// var_dump($errors);
		$array_errors = (array) $errors->default;
		var_dump($array_errors);
		*/
		
		$new_count = Configuration::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "configuration not created, actual=$new_count, expected=$expected" );
	}
	
	public function test_store_another_incorrect_value() {
		// Post a creation request
		$bad_key = "app.bad_key";
		$configuration = Configuration::factory()->make(['key' => $bad_key, 'value' => 'en']);
		$value = $configuration->value;
		
		$initial_count = Configuration::count ();
		
		// $url = 'http://' . tenant('id'). '.tenants.com/configuration' ;
		$elt = ["key" => $bad_key, "value" => $value, '_token' => csrf_token()];
		
		// 'The key format is invalid'
		$this->post_tenant_url( $this->user, 'configuration', [], $elt, $errors_expected = true, 'post',
				['key' => 'The selected key is invalid.']);
				
		$new_count = Configuration::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "configuration not created, actual=$new_count, expected=$expected" );
	}
	
	public function test_show_page() {
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$configuration->save();
		
		$this->get_tenant_url($this->user, 'configuration/' . $key);
	}

	public function test_edit_page() {
		
		$configuration = Configuration::factory()->make();
		$key = $configuration->key;
		$configuration->save();
		
		$this->get_tenant_url($this->user, 'configuration/' . $key . '/edit', ['Edit configuration']);
	}

	public function test_update() {
		
		$configuration = Configuration::factory()->make(['key' => 'app.timezone', 'value' => 'Europe/Munich']);
		$key = $configuration->key;
		$value = $configuration->value;
		$new_value = "new value";
		$elt = ["key" => $key, "value" => $new_value, '_token' => csrf_token()];
		
		$this->assertNotEquals($value, $new_value);
		$configuration->save();
				
		$this->put_tenant_url($this->user, 'configuration/' . $key, ['updated'], $elt);
		
		$back = Configuration::where('key', $key)->first();		
		$this->assertEquals($new_value, $back->value);
		$back->delete();
	}
	
	public function test_delete() {
		
		$configuration = Configuration::factory()->make(['key' => 'app.timezone', 'value' => 'Europe/London']);
		$key = $configuration->key;
		$configuration->save();
		
		$initial_count = Configuration::count ();
		
		$this->delete_tenant_url($this->user, 'configuration/' . $key, ['deleted']);
		
		$new_count = Configuration::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "configuration deleted, actual=$new_count, expected=$expected" );		
	}
	
}
