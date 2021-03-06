<?php

/**
 * Test cases: CodeGenType CRUD
 *
 */
namespace tests\Feature\Api;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CodeGenType;
use Laravel\Sanctum\Sanctum;

class CodeGenTypeControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
		
		$this->base_url = '/code_gen_type';
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	/**
	 * Test that base URL returns a json list of elements
	 */
	public function ttest_code_gen_type_index_json() {
		
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$code_gen_type1 = CodeGenType::factory ()->create ();
		CodeGenType::factory ()->create ();
		
		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json['data']));

        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $this->assertEquals($code_gen_type1->$field, $json['data'][0][$field]);             
        }
	}
	
	/**
	 * Get one element form the API
	 */
	public function ttest_show() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$code_gen_type1 = CodeGenType::factory ()->create ();
		CodeGenType::factory ()->create ();
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/1');
		$response->assertStatus(200);
		$json = $response->json();
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $this->assertEquals($code_gen_type1->$field, $json[$field]);             
        }
	}
	
	
	/**
	 * Create elements from the API
	 */
	public function ttest_code_gen_type_store() {
		
		$this->withoutMiddleware();
		
		$initial_count = CodeGenType::count();
		
		//prepare an element
		$code_gen_type = CodeGenType::factory()->make();
		$elt = [];
        // Normally the crsf token is required for all post, put, patch and delete requests
        // But in this context all middleware are disabled ...
        // $elt['_token'] = csrf_token();
		foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
		    $elt[$field] = $code_gen_type->$field;
		}
				
		// call the post method to create it
		$response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url , $elt);
		
		// $response->dump();
		$response->assertStatus(201);
		$json = $response->json();
		
		// by default the store method returns the created element
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertEquals($code_gen_type->$field, $json[$field]);             
        }
		
		// check that an element has been created
		$new_count = CodeGenType::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "event created, actual=$new_count, expected=$expected" );
		
		// and it can be retrieved		
		$back = CodeGenType::latest()->first();
		$this->assertNotNull($back);
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertEquals($code_gen_type->$field, $back->$field);             
        }
	}
	
	/**
	 * Test store error cases
	 */
	public function ttest_code_gen_type_store_incorrect_value() {
	
	    $this->withoutMiddleware();
		
		$cnt = 1;
		foreach (CodeGenType::factory()->error_cases() as $case) {
            $initial_count = CodeGenType::count ();

            $elt = $case["fields"];
            
            $response = $this->postJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url, $elt);
            $json = $response->json();
            $this->assertEquals('The given data was invalid.', $json['message']);
            foreach ($case["errors"] as $field => $msg) {
                $this->assertEquals($msg, $json['errors'][$field][0]);   
            }
             
            $new_count = CodeGenType::count ();
            $this->assertEquals ( $initial_count, $new_count, "error case $cnt: code_gen_type not created, actual=$new_count, expected=$initial_count" );
            $cnt = $cnt + 1;
		}
	}

	/**
	 * Delete an element through the API
	 */
	public function ttest_delete() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $initial_count = CodeGenType::count ();
		CodeGenType::factory()->create();
		$back = CodeGenType::latest()->first();
		$id = $back->id;
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertEquals($json, 1);
		
		$new_count = CodeGenType::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Event deleted, actual=$new_count, expected=$expected" );		
	}
	
	/**
	 * Test deleting a non existing element
	 */
	public function test_delete_inexisting_elt() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		$id = "123456789";
		$initial_count = CodeGenType::count ();
		
		$response = $this->deleteJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id);
		
		// $response->dump();
		$json = $response->json();
		$this->assertTrue(strpos($json['message'], "No query results for model") >= 0);
		$this->assertTrue(strpos($json['message'], $id) >= 0);
		
		$new_count = CodeGenType::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "Nothing deleted, actual=$new_count, expected=$expected" );
	}
	
	/**
	 * Check that an element can be updated through the REST API
	 */
	public function ttest_update() {
		
		$this->withoutMiddleware();
		
		CodeGenType::factory()->create();
		$back = CodeGenType::latest()->first();
		$id = $back->id;
				
        $initial_count = CodeGenType::count ();

        //prepare another element
        $code_gen_type = CodeGenType::factory()->make();
        $elt = [];
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $elt[$field] = $code_gen_type->$field;
        }
		$elt['_token'] = csrf_token();
						
		$response = $this->patchJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '/' . $id, $elt);
		$this->assertEquals(1, $response->json());		

		$updated = CodeGenType::findOrFail($id);
		
		foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
		    if ($field != "id")
                $this->assertEquals($elt[$field], $updated->$field);             
        }

		$new_count = CodeGenType::count ();
		$this->assertEquals ( $new_count, $initial_count, "Count does not change on update, actual=$initial_count, expected=$new_count" );
	}

	/**
	 * Test pagination
	 */
	public function ttest_code_gen_type_pagination() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $cnt = 0;		
		for ($i = 0; $i < 90; $i++) {			
			CodeGenType::factory ()->create ();
            if ($cnt == 19) {
                $elt20 = CodeGenType::latest()->first();
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
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
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
	public function ttest_bad_page_number() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		for ($i = 0; $i < 100; $i++) {			
			CodeGenType::factory ()->create ();
		}
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=120');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(0, count($json['data'])); // just returns no data
	}
	
	
	/**
	 * Test that pages are correctly sorted
	 */
	public function ttest_code_gen_type_sorting() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
	    $cnt = 0;
		for ($i = 0; $i < 90; $i++) {			
			CodeGenType::factory ()->create ();
            if ($cnt == 19) {
                $elt20 = CodeGenType::latest()->first();
            }
            if ($cnt == 84) {
                $elt85 = CodeGenType::latest()->first();
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
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertEquals($elt20->$field, $json['data'][19][$field]);
        }
		
		// Sorting on start (reverse order)
		$first_field = [ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff_date", "takeoff_time", "price", "big_price", "qualifications", "color_name", "picture", "attachment" ][0];
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=-' . $first_field);
		$json = $response->json();
		
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $this->assertEquals($elt85->$field, $json['data'][6][$field]);
        }
	}
	
	/**
	 *
	 */
	public function ttest_code_gen_type_sorting_on_multiple_columns() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		//
		for ($i = 0; $i < 100; $i++) {
			CodeGenType::factory ()->create ();
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
	public function ttest_sorting_on_bad_column_name() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		for ($i = 0; $i < 100; $i++) {
			CodeGenType::factory ()->create ();
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
			CodeGenType::factory ()->create ();
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
