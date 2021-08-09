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
class BackupsTest extends DuskTestCase {

	function __construct() {
		parent::__construct ();
		
		$this->wait = 0;
	}

	public function setUp(): void {
		parent::setUp ();

		$database = env ( 'DB_DATABASE' );

		/**
		 echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		 echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 echo "url=" . env('APP_URL') . "\n";
		 echo "database=$database\n";
		 */

		sleep($this->wait);
		// Restore a test database
		$filename = storage_path () . '/app/tests/central_nominal.gz';
		$this->assertFileExists ( $filename, "central_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}
	
	public function test_backup() {
		
		$this->browse ( function (Browser $browser) {
			
			// Login
			$this->login($browser);
			
			// Goto backup
			$browser->visit ( '/backup' )
			->assertPathIs ( '/backup' )
			->assertSee ( 'Multi Central Application' )
			->assertSee ( 'Number' )
			->assertSee ( 'Backup' )
			->assertSee ( 'Restore' )
			->assertSee ( 'Delete' );
			
			// count existing backups
			$initial_count = $this->datatable_count($browser);
			echo "\n$initial_count existing backups\n";
			
			// create a new backup
			$browser->click('@new_backup');
			
			// Check that a new backup has been created
			$new_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count + 1, $new_count, "A backup has been created");
			
			// count the users
			$browser->visit ( '/users' );
			$users_count = $this->datatable_count($browser);
			
			// Create a new user
			$name = "New_user";
			$email = "user@free.fr";
			$password = "User_password";
			$browser->visit ( '/users/create' )
			->assertSee ( 'New User' )
			->type ( 'name', $name )
			->type ( 'email', $email )
			->type ( 'password', $password )
			->type ( 'password_confirmation', $password );
			sleep($this->wait);
			
			$browser->press ( 'Submit' )
			->assertPathIs ( '/users' )
			->assertSee ( $email );
			
			// Check that a new user has been created
			$browser->visit ( '/users' );
			$new_users_count = $this->datatable_count($browser);
			$this->assertEquals($users_count + 1, $new_users_count);
			
			// Restore the backup
			$browser->visit ( '/backup' )
			->click('@restore_' . $new_count);

			// count again the users
			$browser->visit ( '/users' );
			$users_count_after_restore = $this->datatable_count($browser);
			$this->assertEquals($users_count, $users_count_after_restore);
					
			// delete the new backup
			$browser->visit ( '/backup' )
			->click('@delete_' . $new_count);
			
			$final_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count, $final_count);
			
			// logout
			$this->logout($browser);
		} );
	}
}
