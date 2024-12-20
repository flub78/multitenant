<?php

namespace tests\Feature\Central;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test UserController for central application
 * 
 * @author frederic
 *
 */
class UserControllerTest extends TestCase {

	protected $basename = "users";


	// Clean up the database
	use DatabaseTransactions;

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete();
	}

	/**
	 * Create an element and returns its id
	 * @return int
	 */
	private function create_first() {
		$this->be($this->user);

		$initial_count = User::count();
		# $this->assertTrue($initial_count == 0, "No element after refresh");

		// Create
		$game = User::factory()->make();
		$game->save();
		$count = User::count();
		$this->assertTrue($count == $initial_count + 1, "One element created");

		# Read
		$stored = User::where('name', $game->name)->first();
		return ($stored->id);
	}

	/**
	 * Index view
	 *
	 * @return void
	 */
	public function test_users_index_view() {

		$this->be($this->user);
		$response = $this->get('/user');
		$response->assertStatus(200);
		$response->assertSeeText('Users');
	}

	/**
	 * Create view
	 *
	 * @return void
	 */
	public function test_users_create_view() {
		$this->be($this->user);
		$response = $this->get('/user/create');
		$response->assertStatus(200);
		$response->assertSeeText(__('user.new'));
	}

	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function test_users_edit_view_existing_element() {

		$id = $this->create_first();

		$response = $this->get("/user/$id/edit");
		$response->assertStatus(200);
		$response->assertSeeText(__('general.edit'));
		$response->assertSeeText(__('user.elt'));
	}

	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function test_users_edit_view_unknown_element_return_404() {

		$id = $this->create_first() + 1000;

		$response = $this->get("/user/$id/edit");
		$response->assertStatus(404);	// not found		
	}

	/**
	 * Test element storage
	 */
	public function test_users_store() {

		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();

		$initial_count = User::count();

		$elt = array('name' => 'Turlututu', 'email' => 'turlututu@free.fr', 'password' => 'password', 'password_confirmation' => 'password');
		$response = $this->post('/user', $elt);

		if (session('errors')) {
			$this->assertTrue(session('errors'), "session has no errors");
		}

		$count = User::count();
		$this->assertTrue($count == $initial_count + 1, "One new elements in the table");
	}

	/**
	 * Test element storage
	 */
	public function test_users_store_incorrect_element() {

		// to avoid the error: 419 = Authentication timeout
		$this->withoutMiddleware();

		$initial_count = User::count();

		$elt = array('name' => 'Turlututu', 'email' => 'go.email');
		$response = $this->post('/user', $elt);
		$response->assertStatus(302);

		if (!session('errors')) {
			$this->assertTrue(session('errors'), "session has errors");
		}

		$count = User::count();
		$this->assertTrue($count == $initial_count, "No creation in the table");
	}

	/**
	 * 
	 */
	public function test_users_update_and_delete() {
		$this->test_users_store();

		$initial_count = User::count();

		$stored = User::where('name', 'Turlututu')->first();
		$this->assertEquals($stored->email, 'turlututu@free.fr', "check retrieve value");
		$new_email = 'new.email@free.fr';
		$elt = array('name' => $stored->name, 'email' => $new_email, 'id' => $stored->id, 'password' => 'password', 'password_confirmation' => 'password');

		$url = "/user/" . $stored->id;
		$response = $this->patch($url, $elt);

		$response->assertStatus(302);
		$this->assertNull(session('errors'), "session has no errors");

		$elt['admin'] = 1;
		unset($elt['password']);
		unset($elt['password_confirmation']);
		$response = $this->patch($url, $elt);
		$this->assertNull(session('errors'), "session has no errors");

		$stored = User::where('name', 'Turlututu')->first();
		$this->assertEquals($stored->email, $new_email, "value updated");
		// $this->assertEquals(1, $stored->isAdmin());
		// echo "admin = " . $stored->admin;

		$url = "/user/" . $stored->id;
		$this->delete($url);
		$count = User::count();
		$this->assertTrue($count == $initial_count - 1, "Element updated then deleted ($url)");
	}

	/**
	 * Change password
	 *
	 * @return void
	 */
	public function ttest_users_can_access_change_password_view() {
		$this->be($this->user);
		$response = $this->get('/change_password/change_password');
		$response->assertStatus(200);
		$response->assertSeeText(__('user.change_password'));
	}

	public function ttest_user_can_change_password() {
		// var_dump($this->user);
	}
}
