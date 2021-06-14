<?php

/**
 * https://medium.com/@DCzajkowski/testing-laravel-authentication-flow-573ea0a96318
 */

namespace tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;


// use Session;


/**
 * Checks that authentication mechanisms are working
 * 
 * The intend is not to test the framework, however the user management
 * is directly a feature of the application.
 * 
 * @author frede
 *
 */
class LoginTest extends TestCase {
	    
	/**
	 * A user can access to the login form
	 * @return void
	 */
	public function test_users_can_view_a_login_form() {
	    $response = $this->get('/login');
	    $response->assertSuccessful();
	    $response->assertViewIs('auth.login');
	    $response->assertStatus ( 200 );	    
	}
	
	/**
	 * Access to home when logged on
	 */
	public function test_user_cannot_view_the_login_form_when_authenticated() {
		$user = User::factory ()->make ();
	    $response = $this->actingAs($user)->get('/login');
	    $response->assertRedirect('/home');
	}
	
	/**
	 * Create a user and log on
	 */
	public function test_user_can_login_with_correct_credentials()
	{	    
 	    $this->withoutMiddleware();
	    
	    // create a user
	    $count = User::all()->count();
	    
	    $user = User::factory ()->make ();
	    $user->password =  bcrypt($password = 'i-love-laravel');
	    $user->save();
	    
	    $new_count = User::all()->count();
	    $this->assertEquals($new_count, $count + 1, "One more user in database");
	    
	    $response = $this
	       ->from('/login')
	       ->post('/login', [
	        'email' => $user->email,
	        'password' => $password,
	    ]);

	       $this->assertAuthenticatedAs($user);
	    
	    $user->delete();
	}
	
	public function test_user_cannot_login_with_incorrect_password()
	{
	    $this->withoutMiddleware();
	    
	    $user = User::factory ()->make ();
	    $user->password =  bcrypt($password = 'i-love-you');
	    $user->save();
	    
	    $response = $this
	       ->from('/login')
	       ->post('/login', [
	            'email' => $user->email,
	           'password' => 'invalid-password',
	       ]);
	    
	    $response->assertRedirect('/login');
	    $response->assertSessionHasErrors('email');
	    $this->assertTrue(session()->hasOldInput('email'));
	    $this->assertFalse(session()->hasOldInput('password'));
	    $this->assertGuest();
	    
	    $user->delete();
	}
	
	
	/**
	 * A user can access to the forgottent password form
	 * @return void
	 */
	public function test_users_can_view_a_forgotten_password_form() {
	    $response = $this->get('/password/reset');
	    $response->assertSuccessful();
	    $response->assertViewIs('auth.passwords.email');
	    $response->assertStatus ( 200 );
	}
	
	/**
	 * _token	LBD6UDDeSsTDJVNsMQAzNKUGVSHVV0DRmwcHWaHp
     * email	unknow@email.adddress
     * TODO fix mixed incorrect localization on forgotten password
	 */
	public function ttest_error_returned_on_unknown_address_on_forgotten_password() {
	    
	    // $this->withoutMiddleware();
	    
	    $response = $this
	    ->followingRedirects()
	    ->post('/password/email', [
	        'email' => "unknow@email.adddress",
	        '_token' => csrf_token()
	    ]);
	    
	    // dd($response->getContent());
	    
	    $response->assertSuccessful(); 
	    $response->assertStatus ( 200 );
	}
	
	/*
	 * FIXME email not sent
	 */
	public function ttest_email_is_sent_on_forgottent_password_request () {
	    $response = $this
	    ->followingRedirects()
	    ->post('/password/email', [
	        'email' => "user@example.com",
	        '_token' => csrf_token()
	    ]);
	    
	    $response->assertSuccessful();
	    $response->assertStatus ( 200 );
	    $response->assertDontSee('Cette adresse email est inconnue');
	    // dd($response);
	}
}
