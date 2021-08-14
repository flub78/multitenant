<?php

namespace Tests\Browser\Central;

use Laravel\Dusk\Browser;
use App\Helpers\BackupHelper;
use Tests\Browser\UserSupport;

/**
 * Feature: User registration
 *      As a guest
 *      I want to register
 *      So I can login

 *      rule: first registered user is admin
 *      rule: others registered users are not admin
 *
  * Feature: User management
 *      As an admin
 *      I want to create users
 *      
 *      So they can login
 *      I want to delete users
 *      So they are forgotten by the system
 *      
 *      I want to change user email address
 *      So they can log in with another email
 *      
 *      I want to change the user password
 *      So they can login with the new password
 *      
 *      rule: non admin users cannot modify others users
 *
 * @author frederic
 *        
 */
class UserTest extends UserSupport {
		

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
	
}
