<?php

namespace Tests\Browser\Tenants;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;
use PHPUnit\Framework\TestCase;

/**
 * User CRUD
 *
 * @author frederic
 *        
 */
class UserTest extends DuskTestCase {

	function __construct() {
		parent::__construct ();

		$this->name = "Titi Paris";
		$this->email1 = "titi@gmail.com";
		$this->email2 = "titi@free.fr";
		$this->password = "password4titi";
	}

	public function setUp(): void {
		parent::setUp ();

		$database = "tenanttest";

		/**
		 * echo "\nENV=" .
		 * env ( 'APP_ENV' ) . "\n";
		 * echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 * echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 * echo "url=" . env('APP_URL') . "\n";
		 * echo "database=$database\n";
		 */

		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists ( $filename, "tenant_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}

	public function test_user_can_register() {
		
		$this->browse ( function (Browser $browser) {
			
			// Register a new user
			$browser->visit ( '/register' )
			->assertSee ( 'Register' )
			->type ( 'name', $this->name )
			->type ( 'email', $this->email1 )
			->type ( 'password', $this->password )
			->type ( 'password_confirmation', $this->password )
			->press ( 'Register' )
			->assertPathIs ( '/home' )
			->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );

			// Logout
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ( 'Register' );

			// Login again
			$browser->visit ( '/login' )
			->type ( 'email', $this->email1 )
			->type ( 'password', $this->password )
			->press ( 'Login' )->assertPathIs ( '/home' )
			->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			
			// Logout again
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ( 'Register' );
			
			// login as admin
			$browser->visit ( '/login' )
			->type ( 'email', env ( 'TEST_LOGIN' ) )
			->type ( 'password', env ( 'TEST_PASSWORD' ) )
			->press ( 'Login' )
			->assertPathIs ( '/home' );
			
			// goto the user page
			$browser->visit ( '/users' )
			->assertSee ( $this->name)
			->assertSee ( $this->email1)
			->assertSee('Showing 1 to 2 of 2 entries');
			
			// goto the user edit page
			$browser->visit ( '/users/2/edit' )
			->assertSee ('Edit user')
			->type ( 'email', $this->email2 )
			->press('Update')
			->assertPathIs ( '/users' )
			->assertSee ( $this->email2)
			->assertSee('Showing 1 to 2 of 2 entries');
			
			// logout
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ('Register');
			
			// login with the new email address
			$browser->visit ( '/login' )
			->type ( 'email', $this->email2 )
			->type ( 'password', $this->password )
			->press ( 'Login' )->assertPathIs ( '/home' )
			->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			
			// logout
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ('Register');
			
		} );
	}

	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {
		$this->browse ( function ($browser) {
			$browser->visit ( '/login' )
			->type ( 'email', env ( 'TEST_LOGIN' ) )
			->type ( 'password', env ( 'TEST_PASSWORD' ) )
			->press ( 'Login' )
			->assertPathIs ( '/home' );

			$browser->screenshot ( 'Tenants/after_login' );
		} );
	}

	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		$this->browse ( function ($browser) {
			$browser->visit ( '/home' )
			->click ( '@user_name' )
			->click ( '@logout' )
			->assertPathIs ( '/' )
			->assertSee ('Register');
			
			$browser->screenshot('Tenants/after_logout');
		} );
	}
	
}
