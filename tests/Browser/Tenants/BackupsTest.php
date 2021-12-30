<?php

namespace Tests\Browser\Tenants;

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

		$database =  "tenanttest";

		/*
		 echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		 echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 echo "DB_USERNAME=" . env('DB_USERNAME') . "\n";
		 echo "DB_PASSWORD=" . env('DB_PASSWORD') . "\n";
		 echo "DB_HOST=" . env('DB_HOST') . "\n";
		 
		 echo "database=$database\n";
		 */

		sleep($this->wait);
		// Restore a test database
		$filename = storage_path () . '/app/tests/tenant_nominal.gz';
		$this->assertFileExists ( $filename, "tenant_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}
	
	// TODO: test disabled because env is not correctly setup for tenant controllers
	public function test_backup() {
		
		// $this->markTestSkipped('Skipped env is not correctly setup for tenant controllers'); 
		
		$this->browse ( function (Browser $browser) {
			
			// Login
			$this->login($browser);
			
			// Goto backup
			$browser->visit ( '/backup' )
			->assertPathIs ( '/backup' )
			->assertSee ( 'Multi tenant =' )
			->assertSee ( 'Number' )
			->assertSee ( 'Backup' )
			->assertSee ( 'Restore' )
			->assertSee ( 'Delete' );
			
			// count existing backups
			$initial_count = $this->datatable_count($browser);
			// echo "\n$initial_count existing backups\n";
			
			// create a new backup
			$browser->click('@new_backup');
			
			// Check that a new backup has been created
			$new_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count + 1, $new_count, "A backup has been created");
			
			// count the users
			$browser->visit ( '/user' );
			$user_count = $this->datatable_count($browser);
			// echo "\ninitial user count = $user_count\n";
			
			// Create a new user
			$name = "New_user";
			$email = "user@free.fr";
			$password = "User_password";
			$browser->visit ( '/user/create' )
			->assertSee ( 'New User' )
			->type ( 'name', $name )
			->type ( 'email', $email )
			->type ( 'password', $password )
			->type ( 'password_confirmation', $password );
			sleep($this->wait);
			
			$browser->press ( 'Submit' )
			->assertPathIs ( '/user' )
			->assertSee ( $email );
			
			// Check that a new user has been created
			$browser->visit ( '/user' );
			$new_user_count = $this->datatable_count($browser);
			$this->assertEquals($user_count + 1, $new_user_count, "a new user has been created");
			// echo "\nnew user count = $new_user_count\n";
			
			// Restore the backup
			$browser->visit ( '/backup' )
			->click('@restore_' . $new_count);
			// $browser->screenshot('Tenants/after_backup_restore');
			

			// count again the users
			$browser->visit ( '/user' );
			$user_count_after_restore = $this->datatable_count($browser);
			// $this->assertEquals($user_count, $user_count_after_restore, "the new user has been erased");
					
			// delete the new backup
			$browser->visit ( '/backup' )
			->click('@delete_' . $new_count);
			
			$final_count = $this->datatable_count($browser);
			
			$this->assertEquals($initial_count, $final_count, "back to initial backup number");
			
			// logout
			$this->logout($browser);
		} );
	}
}
