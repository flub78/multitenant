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
use App\Helpers\CodeGenerator as CG;


class CodeGenTypeControllerFilterTest extends TenantTestCase {
	
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
	 * Create some elements for testing
	 *
	 * @param int $number of elementes to create
	 * @return an array of elements
	 */
    public function create_elements(int $number = 1, $argv = []) {
	   $elements = [];
       for ($i = 0; $i < $number; $i++) {
            $elements[] = CodeGenType::factory ()->create ($argv);
        }
        return $elements;
	}
	
	/**
	 * Test that base URL returns a json list of elements
	 */
	public function test_code_gen_type_index_json() {
		
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
        $elements = $this->create_elements(2);
		
		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);
		
		$json = $response->json();
		$this->assertEquals(2, count($json['data']));

        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "black_and_white", "color_name", "picture", "attachment" ] as $field) {
            if ($field != "id")
                $this->assertEquals($elements[0]->$field, $json['data'][0][$field]);             
        }
	}

	public function test_filtering() {
		Sanctum::actingAs(User::factory()->create(),['api-access']);
		
		// generate the test data set
		for ($i = 0; $i < 100; $i++) {
			CodeGenType::factory ()->create ();
		}		
		
		// Filtering on a boolean value
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=black_and_white:1');
		$json = $response->json();		
		$number_of_boolean_on = count($json['data']);
		
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=black_and_white:0');
		$json = $response->json();
		$number_of_boolean_off = count($json['data']);
		
		var_dump($response->json());
		
		$this->assertGreaterThan(25, $number_of_boolean_on);
		$this->assertGreaterThan(25, $number_of_boolean_off);
		$this->assertEquals(100, $number_of_boolean_on + $number_of_boolean_off);
		
		return;
		
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
