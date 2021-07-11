<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class TenantExampleTest extends DuskTestCase {


	public function setUp(): void {
		parent::setUp ();
		/**
		*/
		echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		echo "DB_DATABASE=" . env ( 'DB_DATABASE' ) . "\n";
		echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		echo "tenant=" . tenant('id') . "\n";

		// Restore a test database
		Artisan::call ( 'backup:restore', [ 
				'backup_id' => 'a_test.gz',
				'--force' => true,
				'--quiet' => true
		] );

		$count = User::count ();
		$this->assertEquals ( 3, $count );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function testBasicExample() {
		$this->browse ( function (Browser $browser) {
			$browser->visit ( '/' )->assertSee ( 'Laravel' );
		} );
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {

		$this->browse ( function ($browser)  {
			$browser->visit ( '/login' )
			->type ( 'email', env('TEST_LOGIN') )
			->type ( 'password', env('TEST_PASSWORD') )
			->press ( 'Login' )
			->assertPathIs ( '/home' );
			
			$browser->screenshot('tenant_after_login');
		} );
	}

}
