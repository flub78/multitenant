<?php

/**
 * Test cases: MotdToday CRUD
 *
 */
namespace tests\Feature\Api;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\MotdToday;
use Laravel\Sanctum\Sanctum;
use App\Helpers\CodeGenerator as CG;


class MotdTodayControllerTest extends TenantTestCase {
	
	protected $tenancy = true;
	
	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
		
		$this->base_url = '/motd_today';
	}

	function __destruct() {
		$this->user->delete ();
	}
	
	/**
	 * Create some elements for testing
	 *
	 * @param int $number of elementes to create
	 * @return an array of elements
	 */
    public function create_elements(int $number = 1, $argv = []) {
	   $elements = [];
       for ($i = 0; $i < $number; $i++) {
            $elements[] = MotdToday::factoryCreate ();
        }
        return $elements;
	}
	
	/**
	 * Test that base URL returns a json list of elements
	 */
	public function test_motd_today_index_json() {
		
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $elements = $this->create_elements(2);
		
		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json['data']));

        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            if ($field != "")
                $this->assertEquals($elements[0]->$field, $json['data'][0][$field]);             
        }
	}
	
	/**
	 * Test pagination
	 */
	public function test_motd_today_pagination() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $cnt = 0;		
		for ($i = 0; $i < 90; $i++) {			
            MotdToday::factoryCreate ();
            if ($cnt == 19) {
                $elt20 = MotdToday::latest()->first();
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
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
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
            MotdToday::factoryCreate ();
		}
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?per_page=20&page=120');
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(0, count($json['data'])); // just returns no data
	}
	
	
	/**
	 * Test that pages are correctly sorted
	 */
	public function test_motd_today_sorting() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
	    $cnt = 0;
		for ($i = 0; $i < 90; $i++) {			
			MotdToday::factory ()->create ();
            if ($cnt == 19) {
                $elt20 = MotdToday::latest()->first();
            }
            if ($cnt == 84) {
                $elt85 = MotdToday::latest()->first();
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
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            $this->assertEquals($elt20->$field, $json['data'][19][$field]);
        }
		
		// Sorting on start (reverse order)
		$first_field = [ "title", "message", "publication_date", "end_date" ][0];
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=-' . $first_field);
		$json = $response->json();
		
        foreach ([ "title", "message", "publication_date", "end_date" ] as $field) {
            $this->assertEquals($elt85->$field, $json['data'][6][$field]);
        }
	}
	
	/**
	 *
	 */
	public function ttest_motd_today_sorting_on_multiple_columns() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		//
		for ($i = 0; $i < 100; $i++) {
			MotdToday::factory ()->create ();
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
			MotdToday::factory ()->create ();
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
			MotdToday::factory ()->create ();
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
