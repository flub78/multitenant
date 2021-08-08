<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

/**
 * @author frederic
 *
 */
abstract class DuskTestCase extends BaseTestCase {
	use CreatesApplication;

	/**
	 * Prepare for Dusk test execution.
	 *
	 * @beforeClass
	 * @return void
	 */
	public static function prepare() {
		if (! static::runningInSail ()) {
			static::startChromeDriver ();
		}
	}

	/**
	 * Create the RemoteWebDriver instance.
	 *
	 * @return \Facebook\WebDriver\Remote\RemoteWebDriver
	 */
	protected function driver() {
		$options = (new ChromeOptions ())->addArguments ( collect ( [ 
				'--window-size=1920,1080'
		] )->unless ( $this->hasHeadlessDisabled (), function ($items) {
			return $items->merge ( [ 
					'--disable-gpu',
					'--headless'
			] );
		} )->all () );

		return RemoteWebDriver::create ( $_ENV ['DUSK_DRIVER_URL'] ?? 'http://localhost:9515', DesiredCapabilities::chrome ()->setCapability ( ChromeOptions::CAPABILITY, $options ) );
	}

	/**
	 * Determine whether the Dusk command has disabled headless mode.
	 *
	 * @return bool
	 */
	protected function hasHeadlessDisabled() {
		return isset ( $_SERVER ['DUSK_HEADLESS_DISABLED'] ) || isset ( $_ENV ['DUSK_HEADLESS_DISABLED'] );
	}

	/**
	 * Logout of the application
	 *
	 * @param unknown $browser
	 */
	protected function logout($browser) {
		$browser->visit ( '/home' )
		->click ( '@user_name' )
		->click ( '@logout' )
		->assertPathIs ( '/' )
		->assertSee ( 'Register' );
	}

	/**
	 * Login in the application
	 * @param unknown $browser
	 * @param string $user
	 * @param string $password
	 */
	protected function login($browser, $user = "", $password = "") {
		if (!$user) $user = env('TEST_LOGIN');
		if (!$password) $password = env('TEST_PASSWORD');
			
		$browser->visit ( '/login' )
		->type ( 'email', $user )
		->type ( 'password', $password )
		->press ( 'Login' )
		->assertPathIs ( '/home' );
	}
	
	/**
	 * Use the "Showing X to Y of Z entries" to extract first, last or count.
	 * 
	 * @param unknown $browser
	 * @param string $which
	 */
	protected function datatable_count($browser, $which="count") {
		$dump = $browser->driver->getPageSource();
		$pattern = '/Showing (\d+) to (\d+) of (\d+) entries/';
		
		if (preg_match($pattern, $dump, $matches)) {
			switch ($which) {
				case 'string':
					return $matches[0];
					break;
				case 'first':
					return $matches[1];
					break;
				case 'last':
					return $matches[2];
					break;
				case 'count':
					return $matches[3];
					break;
				default:
					return -1;
			}
		}
		return -1;
	}
}
