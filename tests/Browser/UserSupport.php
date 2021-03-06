<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

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
class UserSupport extends DuskTestCase {
	
	function __construct() {
		parent::__construct ();
		
		$this->name = "Titi";
		$this->email1 = "titi@gmail.com";
		$this->email2 = "titi@free.fr";
		$this->password = "password4titi";
		$this->new_password = "new4titi";
		
		$this->wait = 0;
		$this->screenshots_dir = "Central";
	}
	
	/**
	 * A basic browser test example.
	 *
	 * @return void
	 */
	public function test_login() {
		$this->browse ( function ($browser) {
			$this->login($browser, env('TEST_LOGIN'), env('TEST_PASSWORD'));
			
			$browser->screenshot ( $this->screenshots_dir . '/after_login' );
		} );
	}
	
	/**
	 * Test that the user can log out
	 *
	 * @return void
	 */
	public function test_logout() {
		$this->browse ( function ($browser) {
			$this->logout($browser);
			
			$browser->screenshot($this->screenshots_dir . '/after_logout');
		} );
	}
	
	/**
	 * Scenario: A user can register
	 *      Given I am a non registered user
	 *      and on the registration page
	 *      When I fill the form and submit
	 *      Then I am logged in
	 *      and I can use the same credential to login later
	 *
	 * Scenario: An admin can change a user email
	 *      Given I am an admin
	 *      and on a user edit form
	 *      When I change the email and submit
	 *      Then the user can login using the new email address
	 */
	public function test_user_registration_and_crud() {
		
		$this->browse ( function (Browser $browser) {
			
			// get initial count
			$this->login($browser);
			$browser->visit ( '/user' );
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
			$this->login($browser);
			$browser->assertPathIs ( '/home' );
			sleep($this->wait);
			
			// goto the user page
			$browser->visit ( '/user' )
			->assertSee ( $this->name)
			->assertSee ( $this->email1);
			
			$after_registration_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count + 1, $after_registration_count, "a user has registered");
			sleep($this->wait);
			
			// goto the user edit page and change the email address
			$browser->click ( '@edit_' . $this->name )
			->assertSee ('Edit user')
			->type ( 'email', $this->email2 );
			sleep($this->wait);
			
			$browser->press('Update')
			->assertPathIs ( '/user' )
			->assertSee ( $this->email2);
			
			$after_update_count = $this->datatable_count($browser);
			$this->assertEquals($after_registration_count, $after_update_count, "update does not modify user count");
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
			$this->login($browser);
			sleep($this->wait);
			
			// goto the user page
			$browser->visit ('/user');
			sleep(0.5);
			$dusk_label = '@delete_' . $this->name;
			$browser->click( $dusk_label);
			
			sleep(0.5);
			// $browser->screenshot($this->screenshots_dir . '/after_user_delete');
			
			$browser->visit ('/user');
			sleep(0.5);
			
			$final_count = $this->datatable_count($browser);
			$this->assertEquals($initial_count, $final_count, "user deleted, initial=$initial_count, final=$final_count");
			
			// logout
			$this->logout($browser);
		} );
	}

	
	/**
	 * Scenario: An admin can create a user
	 *      Given I am an admin
	 *      and I am logged in
	 *      and on my user create page
	 *      When I fill the form and submit
	 *      Then a user is created
	 *      and can login using the temporary password
	 *
	 */
	public function test_a_admin_can_create_a_user() {
		
		$this->browse ( function (Browser $browser) {
			
			$this->login($browser);
			
			// get initial user count
			$browser->visit ( '/user' );
			$initial_count = $this->datatable_count($browser);
			
			// create a user
			$browser->visit ( '/user/create' )
			->assertSee ( 'New User' )
			->type ( 'name', $this->name )
			->type ( 'email', $this->email1 )
			->check ('active')
			->type ( 'password', $this->password )
			->type ( 'password_confirmation', $this->password );
			sleep($this->wait);
			
			$browser->press ( 'Submit' )
			->assertPathIs ( '/user' )
			->assertSee ( $this->name );
			sleep($this->wait);
			
			$new_count = $this->datatable_count($browser);
			$this->assertEquals($new_count, $initial_count + 1, "a user has been created");
			$this->logout($browser);
			
			// Login as the new user
			$this->login($browser, $this->email1, $this->password);
			$browser->assertSee ( $this->name )
			->assertSee ( 'Dashboard' );
			sleep($this->wait);
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			// and admin can chage a user password
			$this->login($browser);
			
			$browser->visit ( '/user' )
			->click('@edit_' . $this->name)
			//->assertValue('name', $this->name )
			->assertSee('Edit user');
			
			$browser
			->type ( 'email', $this->email2 )
			->type ( 'password', $this->new_password )
			->type ( 'password_confirmation', $this->new_password );
			sleep($this->wait);
			
			$browser->press ( 'Update' )
			->assertPathIs ( '/user' )
			->assertSee($this->name);
			sleep($this->wait);
			
			$this->logout($browser);
			
			// The user can login using new credentials
			$this->login($browser, $this->email2, $this->new_password);
			
			$this->logout($browser);
		} );
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
			$browser->visit ( '/change_password/change_password' )
			->assertSee(__('user.change_password'))
			->assertSee(__('user.email'))
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

			// Then change with valid values
			$browser->type ( 'password',  $this->password )
			->type ( 'email', $this->email2)
			->type ( 'new_password', $this->new_password )
			->type ( 'new_password_confirmation', $this->new_password)
			->press('Update');
			// ->assertSee('changed');
			
			// Logout
			$this->logout($browser);
			sleep($this->wait);
			
			// And log in again with new password
			$this->login($browser, $this->email2, $this->new_password);
			$this->logout($browser);
			
		} );
	}
	
	/**
	 * Scenario: A user can ask for a password reset link
	 *      Given I am a registered user
	 *      and on the password forgotten page
	 *      When I type my email address
	 *      Then I see a message that a reset password link has been sent
	 *      
	 *      Note it would be better to check that the message has actually been sent
	 *      but it is more complicated. 
	 */
	public function test_user_can_ask_for_a_password_reset_link() {
		
		$this->markTestSkipped('Skipped because I need to setup smtp on the jenkins server');
		
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
			
			$this->logout($browser);
			
			// there is a forgotten password link on the login page
			$browser->visit ( '/login' )
			->clickLink('Forgot Your Password?')
			->assertPathIs ( '/password/reset' );
			
			// a link can be requested
			// The test can only work with user pre registered with mailgun
			$browser->type ( 'email', env('TEST_LOGIN'))
			->press ('Send Password Reset Link')
			->assertSee('We have emailed your password reset link');
		} );
	}
	
}
