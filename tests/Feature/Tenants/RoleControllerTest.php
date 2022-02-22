<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Role;
use Illuminate\Support\Facades\Log;

/**
 * Functional test for the Role CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the rolesController class
 *   - the views
 *   - and the model
 *   
 * It is a test at the HTTP level, which means that if the page relies on javascript
 * element to be functional it will not work. Javascript libraries should
 * be used to make the user experience easier, it should not be used to make
 * the application works. Note that this assertion could be reconsidered because of the datatable
 * library
 * 
 * From Laravel documentation each test should only test one request to the controller or the behavior is impredictable.
 * 
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
| Domain | Method    | URI                           | Name              | Action                                                  | Middleware   |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
|        | GET|HEAD  | roles                     | roles.index   | App\Http\Controllers\rolesController@index          | web          |
|        | POST      | roles                     | roles.store   | App\Http\Controllers\rolesController@store          | web          |
|        | GET|HEAD  | roles/create              | roles.create  | App\Http\Controllers\rolesController@create         | web          |
|        | PUT|PATCH | roles/{task}              | roles.update  | App\Http\Controllers\rolesController@update         | web          |
|        | GET|HEAD  | roles/{task}              | roles.show    | App\Http\Controllers\rolesController@show           | web          |
|        | DELETE    | roles/{task}              | roles.destroy | App\Http\Controllers\rolesController@destroy        | web          |
|        | GET|HEAD  | roles/{task}/edit         | roles.edit    | App\Http\Controllers\rolesController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */
class RoleControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	protected $basename = "roles";	
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}


	/**
	 * 
	 * @param string $segments
	 * @return string
	 */
	protected function base_url($segments = "") {
		$url = "/" . $this->basename;
		if ($segments) {
			$url = join("/", [$url, $segments]);
		}
		return $url;
	}
	
	/**
	 * Return the number of elements in the table managed by the CRUD controller under test
	 * @return int
	 */
	protected function eltCount() {
		return Role::count();
	}
	
    /**
     * Test display of all elements
     * Given the user is logged on
     * When calling index URL
     * Then the table view is displayed
     * 
     * @return void
     */
    public function testBaseUrlDisplaysTableView() {   
		Log::Debug(__METHOD__);
		
		$look_for = [__('role.title'), __('role.add'), __('navbar.tenant'), tenant('id')];
		$look_for[] = __('role.name');
		$look_for[] = __('role.description');
		
		$this->get_tenant_url($this->user, 'role', $look_for);		
	}
	
    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
		$this->get_tenant_url($this->user, 'role/create', [__('role.new')]);
	}
	
	/**
	 * Test display of one element
	 * Given the user is logged on
	 * Given at least one element in the table
	 * When sending a get request
	 * Then the element is displayed
	 *
	 * @return void
	 */
    public function ttestGetRequestShowsElement() {        
        Log::Debug(__METHOD__);
		
        Role::factory()->create();
        $latest = Role::latest()->first();
		
        $id = $latest->id;
		
		$this->get_tenant_url($this->user, 'role/' . $id);
	}
	
	/**
     * Test a post request
     * Given the user is logged on
     * When sending a post request
     * Then the element is stored in the database
     * 
     * @return void
     */
    public function testPostRequestStoresElement()
    {    	        
		Log::Debug(__METHOD__);
		
		// Post a creation request
		$role = Role::factory()->make();
		$elt = ['_token' => csrf_token()];
		$elt['name'] = $role->name;
		$elt['description'] = $role->description;
		
		$initial_count = Role::count ();
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'role', ['created'], $elt);
		
		$new_count = Role::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "role created, actual=$new_count, expected=$expected" );		
	}
			
	/**
	 * Test an invalid post request
	 *
	 * Given the user is logged on
	 * When sending a post request with invalid fields
	 * Then errors messages are generated
	 *
	 * @return void
	 */
	public function testInvalidPostGeneratesErrors() {
		Log::Debug(__METHOD__);

		$cnt = 1;
		foreach (Role::factory()->error_cases() as $case) {
			$initial_count = Role::count ();
				
			$elt = ['_token' => csrf_token()];
			$elt = array_merge($elt, $case["fields"]);
		
			$response = $this->post_tenant_url( $this->user, 'role', [], $elt, $errors_expected = true);
			// $response->dumpSession();
		
			$response->assertSessionHasErrors($case["errors"]);
		
			$new_count = Role::count ();
			$this->assertEquals ( $initial_count, $new_count, "error case $cnt: role not created, actual=$new_count, expected=$initial_count" );
			$cnt = $cnt + 1;
		}
		$this->assertTrue($cnt == 1 + count(Role::factory()->error_cases()));
	}

	
	/**
	 * Check that the edit form is correctly displayed
	 * Given the user is logged on
	 * When sending a get request to edit
	 * Then the modification form is displayed pre-filled with the element
	 *
	 * @return void
	 */
	public function testEditUrlDisplaysPopulatedEditForm() {
		
		Log::Debug(__METHOD__);
		
        Role::factory()->create();
        $latest = Role::latest()->first();
        $id = $latest->id;
		
		$this->get_tenant_url($this->user, 'role/' . $id . '/edit', ['Edit role']);
	}

	/**
	 * Test an element update
	 * Given the user is logged on
	 * Given at least one element in the table
	 * When sending a put request
	 * Then the element is modified in the database
	 *
	 * @return void
	 */
	public function testPostRequestUpdatesElement() {
		Log::Debug(__METHOD__);
		
		$role = Role::factory()->make();				// create an element
		$role2 = Role::factory()->make();				// and a second one
		$elt = ['_token' => csrf_token()];
		$elt2 = ['_token' => csrf_token()];
		
        foreach ([ "name", "description" ] as $field) {
				$elt[$field] = $role->$field;
				$elt2[$field] = $role2->$field;
			}
        
        $role->save();                            // save the first element
        $latest = Role::latest()->first();
        $this->assertNotNull($latest);
        $id = $latest->id;
        $this->assertNotNull($id);
		
		$initial = Role::where('id', $id)->first();		// get it back
        $this->assertNotNull($initial);
		
        // Check that the first saved element has the correct values and is different from the second one
        foreach ([ "name", "description" ] as $field) {
			if ($field != 'id') {
				$this->assertEquals($initial->$field, $elt[$field]);
				$this->assertNotEquals($initial->$field, $role2->$field);
			}
		}
				
        // Update the values using the second element
        $elt2['id'] = $id;
        $this->patch_tenant_url($this->user, 'role/' . $id, $elt2);
		
		$updated = Role::where('id', $id)->first();		
        $this->assertNotNull($updated);     
        foreach ([ "name", "description" ] as $field) {
			if ($field != 'id') {
				$this->assertEquals($updated->$field, $elt2[$field]);
			}
		}
		$updated->delete();
	}
	
	/**
	 * Test delete element
	 * Given the user is logged on
	 * Given at least one element in the table
	 * When sending a delete request
	 * Then the element is removed from the database
	 * @return void
	 */
	public function testDeleteRequestsRemovesElement() {
        Log::Debug(__METHOD__);
		
		$initial_count = Role::count ();
		
        Role::factory()->create();
        $latest = Role::latest()->first();
        $id = $latest->id;
		
		$new_count = Role::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "one role created, actual=$new_count, expected=$expected" );
		
		$this->delete_tenant_url($this->user, 'role/' . $id, ['deleted']);
		
		$count_after_delete = Role::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $count_after_delete, "role deleted, actual=$count_after_delete, expected=$expected" );		
	}

	/**
	 * Test not found URL
	 * Given the user is logged on
	 * When calling an unknow URL
	 * Then an error is returned
	 * @return void
	 */
	public function testUrlNotFoundReturns404() {
		Log::Debug(__METHOD__);
		$response = $this->get('/role/undefined_url');
		$response->assertStatus(404);
	}	
}
