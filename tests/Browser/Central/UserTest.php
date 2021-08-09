<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * User CRUD
 *
 * @author frederic
 *        
 */
class UserTest extends DuskTestCase {

	function __construct() {
		parent::__construct ();

		$this->name = "Titi";
		$this->email1 = "titi@gmail.com";
		$this->email2 = "titi@free.fr";
		$this->password = "password4titi";
		
		$this->wait = 0;
	}

	public function setUp(): void {
		parent::setUp ();

		$database = env ( 'DB_DATABASE' );

		/** */
		 echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		 echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 echo "url=" . env('APP_URL') . "\n";
		 echo "database=$database\n";

		sleep($this->wait);
		// Restore a test database
		$filename = storage_path () . '/app/tests/central_nominal.gz';
		$this->assertFileExists ( $filename, "central_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}
	
	public function test_user_registration_and_crud() {
		
		$this->browse ( function (Browser $browser) {
			
			// get initial count
			$this->login($browser);
			$browser->visit ( '/users' );
			$initial_count = $this->datatable_count($browser);
			$this->logout($browser);
			
			// Register a new user
			$browser->visit ( '/register' )
			->assertSee ( 'Register' )
			->type ( 'name', $this->name )
			->type ( 'email', $this->email1 )
			->type ( 'password', $this->password )
			->type ( 'password_confirmation', $this->password );
			sleep($this->wait);
			
			$browser->press ( 'Register' )
			->assertPathIs ( '/home' )
			->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			sleep($this->wait);
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			// Login again as newly registered user
			$this->login($browser, $this->email1, $this->password);
			$browser->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			sleep($this->wait);
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			// login as admin
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			$browser->assertPathIs ( '/home' );
			sleep($this->wait);
			
			// goto the user page
			$browser->visit ( '/users' )
			->assertSee ( $this->name)
			->assertSee ( $this->email1);
			
			$after_registration_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count + 1, $after_registration_count);
			sleep($this->wait);
			
			// goto the user edit page and change the email address
			$browser->click ( '@edit_' . $this->name )
			->assertSee ('Edit user')
			->type ( 'email', $this->email2 );
			sleep($this->wait);
			
			$browser->press('Update')
			->assertPathIs ( '/users' )
			->assertSee ( $this->email2);
			
			$after_update_count = $this->datatable_count($browser);
			$this->assertEquals($after_registration_count, $after_update_count);
			sleep(2 * $this->wait);
			
			// logout
			$this->logout($browser);
			sleep($this->wait);
			
			// login with the new email address
			$this->login($browser, $this->email2, $this->password);
			sleep($this->wait);
			
			$browser->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			
			// logout
			$this->logout($browser);
			
			// delete
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			sleep($this->wait);
			
			// goto the user page
			$browser->visit ('/users');
			$dusk_label = '@delete_' . $this->name;
			// echo "\npressing $dusk_label\n";
			$browser->click( $dusk_label);
			
			$browser->screenshot('Central/after_user_delete');
			
			$browser->visit ('/users');
			$final_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count, $final_count);
			
			// logout
			$this->logout($browser);
		} );
	}
}
