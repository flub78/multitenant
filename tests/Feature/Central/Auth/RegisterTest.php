<?php

/**
 * https://medium.com/@DCzajkowski/testing-laravel-authentication-flow-573ea0a96318
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Log;



/**
 * Checks that authentication mechanisms are working
 * 
 * The intend is not to test the framework, however the user management
 * is directly a feature of the application.
 * 
 * @author frede
 *
 */
class RegisterTest extends TestCase {
	 
    
	/**
	 * A user can access to the register form
	 * @return void
	 */
	public function test_users_can_view_a_register_form() {
	    $response = $this->get('/register');
	    $response->assertSuccessful();
	    $response->assertViewIs('auth.register');
	    $response->assertSeeText('Register');
	    
	    $response->assertStatus ( 200 );	    
	}
	
	/**
	 * Access to home when logged on
	 */
	public function test_user_cannot_view_the_register_form_when_authenticated() {
	    $user = User::factory ()->make ();
	    $response = $this->actingAs($user)->get('/register');
	    $response->assertRedirect('/home');
	}
	
	/**
	 * Create a user and log on
	 * 
_token	h3UsceTPP66vAme8rNNzyUXQI77HEhL2D8i1qEmx
name	toto
email	toto@gmail.com
password	tototo
password_confirmation	tototo
	 */
	public function test_user_can_register_and_create_a_user()
	{	    
	    $this->withoutMiddleware();
	    
	    // number of users before registration
	    $count = User::all()->count();
	    
	    // creation of a user
	    $user = User::factory ()->make ();
	    $user->password =  bcrypt($password = 'i-love-laravel');
	    
	    // not registered in database
	    $new_count = User::all()->count();
	    $this->assertEquals($new_count, $count, "No additional user in database");
	    
	    // register it
	    $response = $this->post('/register', [
	        'name' => $user->name,
	        'email' => $user->email,
	        'password' => $password,
	        'password_confirmation' => $password,
	        '_token' => csrf_token()
	    ]);

	    // check that a user has been created
	    $new_count = User::all()->count();
	    $this->assertEquals($new_count, $count + 1, "One additional user in database");
	    	    
	    $response->assertRedirect('/home');   // Error 419: previously valid authentication has expired
	    
	    $registered_user = User::where('name', $user->name)->first();  
	    $this->assertAuthenticatedAs($registered_user);
	    	    
	    $registered_user->delete();
	    $new_count = User::all()->count();
	    $this->assertEquals($new_count, $count, "Back to the initial number of users");
	}
	
	public function ttest_user_can_login_under_a_previously_registered_user()
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
	
}
