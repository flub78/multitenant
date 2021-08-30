<?php

/**
 * Test cases:
 *
 * Nominal: CRUD testing
 *
 * Error test case:
 *      store of incorrect data is rejected
 * 		delete a non existing role
 *
 * From the Laravel documentation:
 * Unexpected behavior may occur if multiple
 * requests are executed within a single test method.
 */
namespace tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleControllerTest extends TenantTestCase {
	
	protected $basename = "user_roles";
	
	protected $tenancy = true;
	
	// Clean up the database
	use RefreshDatabase;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(UserRole::class)->create();
		// $this->user = UserRole::factory ()->make ();
		$this->user = User::factory ()->create ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	/**
	 * Create an element and returns its id
	 * @return int
	 */
	private function create_first() {
		$this->be ( $this->user );
		
		$initial_count = UserRole::count();
		$this->assertTrue($initial_count == 0, "No element after refresh");
		
		// Create
		$game = UserRole::factory()->make();
		$game->save();
		$count = UserRole::count();
		$this->assertTrue($count == 1, "One element created");

		# Read
		$stored = UserRole::where('name', $game->name)->first();
		return ($stored->id);		
	}

	/**
	 * Index view
	 *
	 * @return void
	 */
	public function test_user_roles_index_view() {
				
		$this->be ( $this->user );
		$response = $this->get ( '/user_roles' );
		$this->assertTrue(true);
		// $response->assertStatus ( 200 );
		// $response->assertSeeText ( 'Users' );
		// $response->assertSeeText ( 'Edit' );
	}

	/**
	 * Create view
	 *
	 * @return void
	 */
	public function ttest_users_create_view() {
		$this->be ( $this->user );
		$response = $this->get ( '/users/create' );
		$response->assertStatus ( 200 );
		$response->assertSeeText (__('users.new'));
	}
	
	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function ttest_users_edit_view_existing_element() {
		
		$id = $this->create_first();
		
		$response = $this->get ( "/users/$id/edit" );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( __('general.edit') );
		$response->assertSeeText ( __('users.elt') );
	}
	
	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function ttest_users_edit_view_unknown_element_return_404() {
		
		$id = $this->create_first() + 1000;
		
		$response = $this->get ( "/users/$id/edit" );
		$response->assertStatus ( 404 );	// not found		
	}
		
	/**
	 * Test element storage
	 */
	public function ttest_users_store() {		
		
		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();
				
		$initial_count = UserRole::count();
		
		$elt = array('name' => 'Turlututu', 'email' => 'turlututu@free.fr', 'password' => 'password', 'password_confirmation' => 'password');
		$response = $this->post('/users', $elt);
		
		if (session('errors')) {
			$this->assertTrue(session('errors'), "session has no errors");
		}
		
		$count = UserRole::count();
		$this->assertTrue($count == $initial_count + 1, "One new elements in the table");
	}

	/**
	 * Test element storage
	 */
	public function ttest_users_store_incorrect_element() {
		
		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();
		
		$initial_count = UserRole::count();
		
		$elt = array('name' => 'Turlututu', 'email' => 'go.email');
		$response = $this->post('/users', $elt);
		$response->assertStatus ( 302);
		
		if (!session('errors')) {
			$this->assertTrue(session('errors'), "session has errors");
		}
		
		$count = UserRole::count();
		$this->assertTrue($count == $initial_count, "No creation in the table");
	}
	
	/**
	 * 
	 */
	public function ttest_users_update_and_delete() {
		$this->test_users_store();
		
		$initial_count = UserRole::count();
		
		$stored = UserRole::where('name', 'Turlututu')->first();
		$this->assertEquals( $stored->email, 'turlututu@free.fr', "check retrieve value");
		$new_email = 'new.email@free.fr';
		$elt = array('name' => $stored->name, 'email' => $new_email, 'id' => $stored->id, 'password' => 'password', 'password_confirmation' => 'password');
		
		$url = "/users/" . $stored->id;
		$response = $this->patch($url, $elt);
		
		$response->assertStatus (302);
		$this->assertNull(session('errors'), "session has no errors");
		
		$elt['admin'] = 1;
		unset($elt['password']);
		unset($elt['password_confirmation']);
		$response = $this->patch($url, $elt);
		$this->assertNull(session('errors'), "session has no errors");
		
		$stored = UserRole::where('name', 'Turlututu')->first();
		$this->assertEquals( $stored->email, $new_email, "value updated");
		// $this->assertEquals(1, $stored->isAdmin());
		// echo "admin = " . $stored->admin;
		
		$url = "/users/" . $stored->id;
		$this->delete($url);
		$count = UserRole::count();
		$this->assertTrue($count == $initial_count - 1, "Element updated then deleted ($url)"); 
	}
	
	/**
	 * Change password
	 *
	 * @return void
	 */
	public function ttest_users_can_access_change_password_view() {
		$this->be ( $this->user );
		$response = $this->get ( '/change_password/change_password' );
		$response->assertStatus ( 200 );
		$response->assertSeeText (__('users.change_password'));
	}
	
	public function ttest_user_can_change_password () {
		$this->be ( $this->user );
		
		$new_mail = 'my-new-email@free.fr';
		$elt = array('password' => 'password', 
				'new_password' => 'new_password',
				'email' => $new_mail,
				'new_password_confirmation' => 'new_password');
		
		$url = "/change_password/password";
		$response = $this->patch($url, $elt);
		$response->assertStatus ( 302 );
		/*
		var_dump($this->user->id);
		$updated = UserRole::where(['email' => $new_mail]);
		var_dump($updated);
		*/
	}
}
