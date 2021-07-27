<?php

/**
 * Test cases:
 *
 * Nominal: CRUD testing
 *
 * Error test case:
 * store of incorrect data is rejected
 * delete a non existing configuration
 *
 * From the Laravel documentation:
 * Unexpected behavior may occur if multiple
 * requests are executed within a single test method.
 *
 * check language when app.locale is not redefined
 * check language when app.locale is set to en
 * check language when app.locale is set to fr
 * check language when app.locale is set to an unsupported language 
 */
namespace tests\Feature\Tenant;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Configuration;
use Illuminate\Support\Facades\App;

class LocalizationTest extends TenantTestCase {
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
	
	/**
	 * Test for English
	 */
	protected function check_en() {
		$this->get_tenant_url($this->user, 'configuration/', ["Tenant Configuration",  "Key", "Value", 'tenant', tenant ( 'id' )]);
		
	}

	public function test_language_when_app_locale_is_not_defined_in_database() {
		$this->check_en();
	}

	public function test_language_when_app_locale_is_en() {
		$this->set_lang('en');
		$this->check_en();
	}
	
	public function test_language_when_app_locale_is_set_with_an_unsupported_value() {
		$this->set_lang('ch');
		$this->check_en();
	}
	
	/**
	 * Test for French
	 */
	public function test_language_when_app_locale_is_fr() {

		$this->set_lang('fr');

		$cfg = Configuration::where ( 'key', 'app.locale' )->first ();
		$this->assertEquals ( 'fr', $cfg->value );
		$this->assertEquals ( 'fr', App::getLocale () );

		// configuration list
		$this->get_tenant_url($this->user, 'configuration/', ["Configuration par locataire",  "Clé", "Valeur", 'locataire', 'test']);
	}	
}
