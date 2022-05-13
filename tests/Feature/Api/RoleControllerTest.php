<?php

/**
 * Test cases: Role CRUD
 *
 */
namespace tests\Feature\Api;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Role;
use Laravel\Sanctum\Sanctum;

class RoleControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
		
		$this->base_url = '/role';
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	/**
	 * Test that base URL returns a json list of elements
	 */
	public function test_role_index_json() {
		
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$role1 = Role::factory ()->create ();
		Role::factory ()->create ();
		
		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json['data']));

        foreach ([ "name", "description" ] as $field) {
            if ($field != "id")
                $this->assertEquals($role1->$field, $json['data'][0][$field]);             
        }
	}
	
	/**
	 * Get one element form the API
	 */
	public function test_show() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$role1 = Role::factory ()->create ();
		Role::factory ()->create ();
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/1');
		$response->assertStatus(200);
		$json = $response->json();
        foreach ([ "name", "description" ] as $field) {
            if ($field != "id")
                $this->assertEquals($role1->$field, $json[$field]);             
        }
	}
	
	
	/**
	 * Create elements from the API
	 */
	public function test_role_store() {
		
		$this->withoutMiddleware();
		
		$initial_count = Role::count();
		
		//prepare an element
		$role = Role::factory()->make();
		$elt = [];
		// Normally the crsf token is required for all post, put, patch and delete requests
		// But in this context all middleware are disabled ...
		// $elt['_token'] = csrf_token();
		foreach ([ "name", "description" ] as $field) {
		    $elt[$field] = $role->$field;
		}
				
		// call the post method to create it
		$response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url , $elt);
		
		// $response->dump();
		$response->assertStatus(201);
		$json = $response->json();
		
		// by default the store method returns the created element
        foreach ([ "name", "description" ] as $field) {
            $this->assertEquals($role->$field, $json[$field]);             
        }
		
		// check that an element has been created
		$new_count = Role::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
		
		// and it can be retrieved		
		$back = Role::latest()->first();
		$this->assertNotNull($back);
        foreach ([ "name", "description" ] as $field) {
            $this->assertEquals($role->$field, $back->$field);             
        }
	}
	
	/**
	 * Test store error cases
	 */
	public function test_role_store_incorrect_value() {		
		
		$this->withoutMiddleware();
		
		$cnt = 1;
		foreach (Role::factory()->error_cases() as $case) {
            $initial_count = Role::count ();

            $elt = $case["fields"];
            
            $response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url, $elt);
            $json = $response->json();
            $this->assertEquals('The given data was invalid.', $json['message']);
            foreach ($case["errors"] as $field => $msg) {
                $this->assertEquals($msg, $json['errors'][$field][0]);   
            }
             
            $new_count = Role::count ();
            $this->assertEquals ( $initial_count, $new_count, "error case $cnt: role not created, actual=$new_count, expected=$initial_count" );
            $cnt = $cnt + 1;
		}
	}

	/**
	 * Delete an element through the API
	 */
	public function test_delete() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $initial_count = Role::count ();
		Role::factory()->create();
		$back = Role::latest()->first();
		$id = $back->id;
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertEquals($json, 1);
		
		$new_count = Role::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}
	
	/**
	 * Test deleting a non existing element
	 */
	public function test_delete_inexisting_elt() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$id = "123456789";
		$initial_count = Role::count ();
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertTrue(strpos($json['message'], "No query results for model") >= 0);
		$this->assertTrue(strpos($json['message'], $id) >= 0);
		
		$new_count = Role::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Nothing deleted, actual=$new_count, expected=$expected" );
	}
	
	/**
	 * Check that an element can be updated through the REST API
	 */
	public function test_update() {
		
		$this->withoutMiddleware();
		
		Role::factory()->create();
		$back = Role::latest()->first();
		$id = $back->id;
				
        $initial_count = Role::count ();

        //prepare another element
        $role = Role::factory()->make();
        $elt = [];
        foreach ([ "name", "description" ] as $field) {
            $elt[$field] = $role->$field;
        }
		$elt['_token'] = csrf_token();
						
		$response = $this->patchJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id, $elt);
		$this->assertEquals(1, $response->json());		

		$updated = Role::findOrFail($id);
		
		foreach ([ "name", "description" ] as $field) {
		    if ($field != "id")
                $this->assertEquals($elt[$field], $updated->$field);             
        }

		$new_count = Role::count ();
		$this->assertEquals ( $new_count, $initial_count, "Count does not change on update, actual=$initial_count, expected=$new_count" );
	}

	/**
	 * Test pagination
	 */
	public function test_role_pagination() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$cnt = 0;
		for ($i = 0; $i < 90; $i++) {			
			Role::factory ()->create();
			if ($cnt == 19) {
				$elt20 = Role::latest()->first();
			}
			$cnt = $cnt + 1;
			if ($cnt == 19) sleep(2); // because lastest has a second precision
		}
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// with a page parameter the API returns the collection in the data field
		// Check that 20 elements out of 100 have been received
		$this->assertEquals(20, count($json['data']));   
		foreach ([ "name", "description" ] as $field) {
			$this->assertEquals($elt20->$field, $json['data'][19][$field]);
		}
		
		//echo "last_page_url = " . $json['last_page_url'] . "\n";
		$response = $this->getJson($json['last_page_url'] . '&per_page=20');
		$json = $response->json();
		$this->assertEquals(10, count($json['data']));
	}
	
	/**
	 * Non existing page number
	 */
	public function test_bad_page_number() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		for ($i = 0; $i < 100; $i++) {		
			Role::factory ()->create ();
		}
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=120');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(0, count($json['data'])); // just returns no data
	}
	
	
	/**
	 * Test that pages are correctly sorted
	 */
	public function test_role_sorting() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
	    $cnt = 0;
		for ($i = 0; $i < 90; $i++) {			
			Role::factory ()->create ();
            if ($cnt == 19) {
                $elt20 = Role::latest()->first();
            }
            if ($cnt == 84) {
                $elt85 = Role::latest()->first();
            }
            $cnt++;
            if (($cnt == 19) || ($cnt == 84)) sleep(2);
		}
		
		// Call a page
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
        foreach ([ "name", "description" ] as $field) {
            $this->assertEquals($elt20->$field, $json['data'][19][$field]);
        }
		
		// Sorting on start (reverse order)
		$first_field = [ "name", "description" ][0];
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=-' . $first_field);
		$json = $response->json();
		
        foreach ([ "name", "description" ] as $field) {
            $this->assertEquals($elt85->$field, $json['data'][6][$field]);
        }
	}
	
	/**
	 *
	 */
	public function ttest_role_sorting_on_multiple_columns() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		//
		for ($i = 0; $i < 100; $i++) {
			Role::factory ()->create ();
		}
		
		// First page, non sorted
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=1');
		$response->assertStatus(200);
		
		$json = $response->json();
		// First call without sorting
		$this->assertEquals(20, count($json['data']));
		$this->assertEquals('event_1', $json['data'][0]['title']);  // regular order
		
		// Sorting on multiple columns
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=allDay,-start');
		$json = $response->json();
		$this->assertEquals('event_100', $json['data'][0]['title']); // reverse order
		$this->assertEquals('event_98', $json['data'][1]['title']); // reverse order
		$this->assertEquals('event_96', $json['data'][2]['title']); // reverse order
		
		$this->assertEquals(0, $json['data'][0]['allDay']);
		$this->assertEquals(0, $json['data'][1]['allDay']);
		$this->assertEquals(0, $json['data'][2]['allDay']);
		$this->assertEquals(0, $json['data'][48]['allDay']);
		$this->assertEquals(0, $json['data'][49]['allDay']);
		$this->assertEquals(1, $json['data'][50]['allDay']);
		$this->assertEquals(1, $json['data'][51]['allDay']);
		$this->assertEquals(1, $json['data'][99]['allDay']);
	}

	/**
	 * Sorting on bad column name
	 */
	public function test_sorting_on_bad_column_name() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		for ($i = 0; $i < 100; $i++) {
			Role::factory ()->create ();
		}
				
		// Sorting on multiple columns
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=Unknown,-ColumnName');
		$json = $response->json();
		$this->assertEquals("Illuminate\Database\QueryException", $json['exception']);
		$this->assertStringContainsString("Unknown column ", $json['message']);
	}

	public function ttest_filtering() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		for ($i = 0; $i < 100; $i++) {
			Role::factory ()->create ();
		}		
		
		// Filtering on multiple columns
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1');
		$json = $response->json();
		$this->assertEquals(50, count($json['data']));
		
		// Filtering on multiple columns
		$limit = $date->sub(10, 'hour');
		$after =  htmlspecialchars(',start:>' . $limit->toDateTimeString());
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1' . $after);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));

		$after =  htmlspecialchars(',start:>=' . $limit->toDateTimeString());
		$url = 'http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=allDay:1' . $after;
		$response = $this->getJson($url);
		$json = $response->json();
		$this->assertEquals(3, count($json['data']));
	}
}
