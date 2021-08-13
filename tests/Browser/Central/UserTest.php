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

	public function tearDown(): void {
		parent::tearDown ();
	}
	
	
	/**
	 * Scenario: A user can modify his/her password
	 *      Given I am a registered user
	 *      and I am logged in
	 *      and on my user edit page
	 *      When I change email and password and submit
	 *      Then I can use the 
	 *
	 * Scenario: An admin can change a user email
	 *      Given I am an admin
	 *      and on a user edit form
	 *      When I change the email and submit
	 *      Then the user can login using the new email address
	 */
	public function test_a_user_can_change_password() {
		
		$this->browse ( function (Browser $browser) {
			
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
			
			// Goto the change password page
			/*
			 * Does not work because the edit page is reserved to admin
			 * I really should have a reset password for users
			 *
			 */ 
			$browser->visit ( '/change_password/change_password' )
			->assertSee(__('users.change_password'))
			->assertSee($this->name);
			
			// First a couple of negative cases
			$browser->press('Update')
			->assertSee('The password field is required')
			->assertSee('The new password field is required.');
			
			$browser->type ( 'password', 'zzzzzzzzzzz' )
			->type ( 'new_password', $this->password )
			->type ( 'new_password_confirmation', 'xxxxxxxxxx' )
			->press('Update')
			->assertSee('The password is incorrect')
			->assertSee('The new password confirmation does not match');
			
			$browser->type ( 'password',  $this->password )
			->type ( 'new_password', $this->new_password )
			->type ( 'new_password_confirmation', $this->new_password)
			->press('Update')
			->assertSee('changed');
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			// And log in again with new password
			$this->login($browser, $this->email1, $this->new_password);
			$this->logout($browser);
			
		} );
	}

}
