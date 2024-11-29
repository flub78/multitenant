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

	function __construct(?string $name = null) {
		parent::__construct($name);

		// required to be able to use the factory inside the constructor
		$this->createApplication();
		// $this->user = factory(User::class)->create();
		$this->user = User::factory()->make();
		$this->user->admin = true;

		$this->base_url = '/code_gen_type';
	}

	function __destruct() {
		$this->user->delete();
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
			$elements[] = CodeGenType::factory()->create($argv);
		}
		return $elements;
	}

	/**
	 * Test that base URL returns a json list of elements
	 */
	public function test_code_gen_type_index_json() {

		Sanctum::actingAs(User::factory()->create(), ['api-access']);

		$elements = $this->create_elements(2);

		// Without page parameter the URL returns a collection
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url);
		$response->assertStatus(200);

		$json = $response->json();
		$this->assertEquals(2, count($json['data']));

		foreach (["name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff", "price", "big_price", "qualifications", "black_and_white", "color_name", "picture", "attachment"] as $field) {
			if ($field != "id")
				$this->assertEquals($elements[0]->$field, $json['data'][0][$field]);
		}
	}

	public function test_filtering() {
		Sanctum::actingAs(User::factory()->create(), ['api-access']);

		// generate the test data set
		for ($i = 0; $i < 100; $i++) {
			CodeGenType::factory()->create();
		}

		// Filtering on a boolean value
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=black_and_white:1');
		$json = $response->json();
		$number_of_boolean_on = count($json['data']);

		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?filter=black_and_white:0');
		$json = $response->json();
		$number_of_boolean_off = count($json['data']);

		$this->assertGreaterThan(25, $number_of_boolean_on);
		$this->assertGreaterThan(25, $number_of_boolean_off);
		$this->assertEquals(100, $number_of_boolean_on + $number_of_boolean_off);
	}

	public function test_filtering_on_date() {
		Sanctum::actingAs(User::factory()->create(), ['api-access']);

		// generate the test data set
		for ($i = 0; $i < 10; $i++) {
			CodeGenType::factory()->create();
		}

		// sort by birthday
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=birthday');
		$json = $response->json();
		// var_dump($json);
		foreach ($json["data"] as $elt) {
			// echo $elt['birthday'] . "\n";
		}

		// sort by birthday reverse ordet
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url . '?sort=-birthday');
		$json = $response->json();
		// var_dump($json);
		$cnt = 0;
		foreach ($json["data"] as $elt) {
			echo $elt['birthday'] . "\n";
			$cnt++;
			// take a date in the middle of the range
			if ($cnt == 5) $mid_date =  $elt['birthday'];
		}

		echo "mid date = $mid_date\n";

		// filter on the mid range date
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url
			. '?filter=birthday:' . $mid_date);
		$json = $response->json();
		$this->assertEquals(1, count($json['data']), "only one birthday matches the date");
		$this->assertEquals($mid_date, $json['data'][0]['birthday'], "filter of the correct date");

		// filter greater than the mid range date
		$response = $this->getJson('http://' . $this->domain(tenant('id')) . '/api' . $this->base_url
			. '?filter=birthday:>' . $mid_date);
		$json = $response->json();
		$this->assertGreaterThan(3, count($json['data']));
	}
}
