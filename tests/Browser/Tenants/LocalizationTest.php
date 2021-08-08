<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Check that views are correctly translated
 * - PHP strings
 * - datatable
 * - fullcalendar
 * - datepicker
 *  
 *  TODO Test datepicker localization
 *  
 * @author frederic
 *
 */
class LocalizationTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		
		$database = "tenanttest";
		
		/**
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		echo "url=" . env('APP_URL') . "\n";
		echo "database=$database\n";
		*/

		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists($filename, "tenant_nominal test backup found");
		BackupHelper::restore($filename, $database, false);		
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			
			$browser->screenshot('Tenants/after_login');
		} );
	}
	
	public function test_configuration_from_english_to_french() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/configuration' )
			->assertSee ( 'Tenant Configuration' );
			
			$browser->screenshot('Tenants/configuration');
			
			$browser->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
			
			$browser->press ( 'Add Configuration' );
			
			// app.locale	fr
			$browser->type ( 'key', 'app.locale')
			->type ( 'value', 'fr')
			->press ( 'Submit' )
			->assertPathIs('/configuration')
			->assertSee ( 'Rechercher' )
			->assertSee ( 'Précédent' )
			->assertSee ( 'Suivant' )
			->assertSee ( "Affichage de l'élement 1 à 1 sur 1 éléments" );
			
			/*
			 * There is some latency on taking app.locale modifications into account, especially regarding success status. Success status messages
			 * are generated before app.locale is modified, so they stay in the previous language until the next request.
			 *
			 * It is unfortunate, but it is a a rare use case (a tenant is expected to select a language, not to modify it) that does not worth the fix.
			 */
			
			// Back to English
			$browser->visit ( '/configuration/app.locale/edit' )
			->assertSee ( 'Editer configuration' )
			->type ( 'value', 'en')
			->press ( 'Modifier' )
			->assertPathIs('/configuration')
			->assertSee ( 'app.locale modifié' )	// latency
			->assertSee ( 'Search' )
			->assertSee ( 'Previous' )
			->assertSee ( 'Next' )
			->assertSee ( 'Showing 1 to 1 of 1 entries' );
			
			
			// delete
			$browser->visit ( '/configuration' )
			->press('Delete')
			->assertPathIs('/configuration')
			->assertSee ( 'app.locale deleted' )				
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}

	public function test_datatable_titles_localization() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/configuration' );
						
			$browser->assertSee ( 'Key' )
			->assertSee ( 'Value' )
			->assertSee ( 'Edit' )
			->assertSee ( 'Delete' );
			
			$browser->press ( 'Add Configuration' );
			
			// app.locale	fr
			$browser->type ( 'key', 'app.locale')
			->type ( 'value', 'fr')
			->press ( 'Submit' )
			->assertPathIs('/configuration')
			->assertSee ( 'Clé' )
			->assertSee ( 'Valeur' )
			->assertSee ( 'Editer' );
						
			// delete
			$browser->press('Supprimer')
			->assertPathIs('/configuration')
			->assertSee ( 'app.locale supprimé' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}

	public function test_fullcalendar_localization() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar/fullcalendar' );
			
			$browser->assertSee ( 'today' )
			->assertSee ( 'month' )
			->assertSee ( 'week' )
			->assertSee ( 'day' )
			->assertSee ( 'list' );

			$browser->assertSee ( 'Sun' )
			->assertSee ( 'Mon' )
			->assertSee ( 'Tue' )
			->assertSee ( 'Wed' )
			->assertSee ( 'Thu' )
			->assertSee ( 'Fri' )
			->assertSee ( 'Sat' )
			;
			
			$browser->visit ( '/configuration/create' );
			
			// app.locale	fr
			$browser->type ( 'key', 'app.locale')
			->type ( 'value', 'fr')
			->press ( 'Submit' );
			
			$browser->visit ( '/calendar/fullcalendar' )
			->assertSee ( 'Calendrier' );
			
			$browser->assertSee ( 'Aujourdhui' )
			->assertSee ( 'Mois' )
			->assertSee ( 'Semaine' )
			->assertSee ( 'Jour' )
			->assertSee ( 'Liste' );
			
			$browser->assertSee ( 'dim' )
			->assertSee ( 'lun' )
			->assertSee ( 'mar' )
			->assertSee ( 'mer' )
			->assertSee ( 'jeu' )
			->assertSee ( 'ven' )
			->assertSee ( 'sam' )
			;
			
			// delete
			$browser->visit ( '/configuration' );
			$browser->press('Supprimer')
			->assertSee ( 'app.locale supprimé' );
		} );
	}

	public function test_datepicker_localization() {
		
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/calendar/create' )
			->assertSee ( 'New Event' );
			
			$browser->click('@start');
						
			$browser->assertSee ( 'Su' )
			->assertSee ( 'Mo' )
			->assertSee ( 'Tu' )
			->assertSee ( 'We' )
			->assertSee ( 'Th' )
			->assertSee ( 'Fr' )
			->assertSee ( 'Sa' )
			;
			
			$browser->visit ( '/configuration/create' );
			
			// app.locale	fr
			$browser->type ( 'key', 'app.locale')
			->type ( 'value', 'fr')
			->press ( 'Submit' );
			
			// Check datepicker in French
			$browser->visit ( '/calendar/create' )
			->assertSee ( 'Nouvel événement' )
			->click('@start');
			
			$browser->assertSee ( 'L' )
			->assertSee ( 'M' )
			->assertSee ( 'J' )
			->assertSee ( 'V' )
			->assertSee ( 'S' )
			->assertSee ( 'D' )
			;
			
			// delete app.locale
			$browser->visit ( '/configuration' )
			->press('Supprimer')
			->assertPathIs('/configuration')
			->assertSee ( 'app.locale supprimé' )
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}
	
	public function test_home_page_localization() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/home' )
			->assertSee ( 'Home page' )
			->assertSee ( 'Dashboard' )
			->assertSee ( 'You are logged in' );
			
			// app.locale	fr
			$browser->visit('/configuration/create')
			->type ( 'key', 'app.locale')
			->type ( 'value', 'fr')
			->press ( 'Submit' );
			
			$browser->visit ( '/home' )
			->assertSee ( 'Accueil' )
			->assertSee ( 'Tableau de bord' )
			->assertSee ( 'Vous etes connecté' );
			
			// delete
			$browser->visit ( '/configuration' )
			->press('Supprimer')
			->assertSee ( 'Showing 0 to 0 of 0 entries' );
		} );
	}
	
	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		
		$this->browse ( function ($browser)  {
			$this->logout($browser);
			
			$browser->screenshot('Tenants/after_logout');
		} );
	}
	
}
