<?php

/**
 * Test cases:
 *
 * Just a way to automatically trigger the test controller
 */
namespace tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Metadata;


class MetadataControllerTest extends TenantTestCase {

	protected $tenancy = true;

	function __construct() {
		parent::__construct ();

		// required to be able to use the factory inside the constructor
		$this->createApplication ();
		
		// $this->user = factory(User::class)->create();
		$this->user = User::factory ()->make ();
		$this->user->admin = true;
	}

	function __destruct() {
		$this->user->delete ();
	}

	
	/**
	 */
	public function test_index_page() {
		$this->be ( $this->user );
	
		// main test entry
		$this->get_tenant_url($this->user, 'metadata',
				['Metadata', __('metadata.title'), __('metadata.table'), __('metadata.field'), __('metadata.foreign_key')]);
	}

	public function test_create_page() {
		$this->get_tenant_url($this->user, 'metadata/create', 
				[__('metadata.new'), __('metadata.table'), __('metadata.foreign_key')]);
	}
	
	public function ttest_store() {
		// Post a creation request
		$metadata = Metadata::factory()->create(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$elt = ["table" => "name", "field" => 'name', '_token' => csrf_token()];
		
		$initial_count = Metadata::count ();
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'metadata', ['created'], $elt);
		
		$new_count = Metadata::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "metadata created, actual=$new_count, expected=$expected" );
	}
	
	
}
