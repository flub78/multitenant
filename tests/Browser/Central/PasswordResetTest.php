<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * Feature: Password Management
 *      As a registered user
 *      I want to change my password
 *      So I can log in with the new password
 *
 *      As an admin
 *      I want to change the password of a user
 *      So the user can log in with the new password
 *      
 *      As a registered user
 *      I want to receive a link to reset my password
 *      So I can log in again
 *      
 *      rule: users need to be logged in to change their password
 *      rule: non admin users cannot change other password
 *
 * @author frederic
 *        
 */
class PasswordResetTest extends DuskTestCase {

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
     * Scenario: A user can change his/her password
     *      Given I am a registered user
     *      and I am logged in
     *      and on the change password page
     *      When I request a new password
     *      Then I can login using the new password
	 */
	public function test_user_can_change_their_password() {
		
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
			
			// Change my password
			/*
			$browser->visit ('/user/change_password')
			->assertPathIs ('/home');
			*/ 
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			/*
			// Login again with the new password
			$this->login($browser, $this->email1, $this->password);
			$browser->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			sleep($this->wait);
						
			// logout
			$this->logout($browser);
			*/
		} );
	}
	
	/**
     * Scenario: An admin can change users password
     *      Given I am admin
     *      and I am logged in
     *      and on the user edit page
     *      When I change a user password
     *      Then The user can login using the new password
	 *
	 */
	public function test_admin_can_change_passwords() {
		
		$this->browse ( function (Browser $browser) {
			$this->login($browser);
			$this->logout($browser);
		} );
	}

	/**
     * Scenario: User can request a password reset
     *      Given I a registered user
     *      and I am not logged in
     *      When I request a password reset
     *      Then I get a link to reset my password
	 *		And I can use the new password to login
	 */
	public function test_admin_can_reset_their_passwor() {
		
		$this->browse ( function (Browser $browser) {
			$this->login($browser);
			$this->logout($browser);
		} );
	}
	
}
