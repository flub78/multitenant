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
 * 
 * TODO: add test cases on javascript obbjects localization
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

	public function set_lang(String $lang): void {
		$cfg = Configuration::where ( 'key', 'app.locale' )->first ();
		if ($cfg) {
			$cfg->value = $lang;
			$cfg->update ();
		} else {
			Configuration::factory ()->create ( [ 
					'key' => 'app.locale',
					'value' => $lang
			] );
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
		App::setLocale ($lang);
	}
	
	protected function check_en() {
		$this->be ( $this->user );
		
		// configuration list
		$url = 'http://' . tenant ( 'id' ) . '.tenants.com/configuration';
		
		$response = $this->get ( $url );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( "Tenant Configuration" );
		$response->assertSeeText ( "Key" );
		$response->assertSeeText ( "Value" );
		$response->assertSeeText ( 'tenant' );
		$response->assertSeeText ( tenant ( 'id' ) );
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
	 * For some reason the bootstrapper
	 */
	public function test_language_when_app_locale_is_fr() {

		$this->set_lang('fr');
		$this->be ( $this->user );

		$cfg = Configuration::where ( 'key', 'app.locale' )->first ();
		$this->assertEquals ( 'fr', $cfg->value );
		$this->assertEquals ( 'fr', App::getLocale () );

		// configuration list
		$url = 'http://' . tenant ( 'id' ) . '.tenants.com/configuration';

		$response = $this->get ( $url );
		$response->assertStatus ( 200 );
		// $response->dump();
		$response->assertSeeText ( "Configuration par locataire" );
		$response->assertSeeText ( "Clé" );
		$response->assertSeeText ( "Valeur" );
		$response->assertSeeText ( 'locataire' );
		$response->assertSeeText ( 'test' );

		// $response->dump();
	}

}
