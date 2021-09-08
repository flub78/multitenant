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
	
	public function test_store() {
		// Post a creation request
		// $metadata = Metadata::factory()->create(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$elt = ["table" => "users", "field" => 'email', '_token' => csrf_token()];
		
		$initial_count = Metadata::count ();
		
		// call the post method to create it
		$this->post_tenant_url($this->user, 'metadata', ['created'], $elt);
		
		$new_count = Metadata::count ();
		$expected = $initial_count + 1;
		$this->assertEquals ( $expected, $new_count, "metadata created, actual=$new_count, expected=$expected" );
	}

	public function test_store_duplicate() {
		// Post a creation request
		$metadata = Metadata::factory()->create(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$elt = ["table" => "users", "field" => 'email', '_token' => csrf_token()];
		
		$initial_count = Metadata::count ();
		
		// call the post method to create it
		$this->be ( $this->user );
		$this->withoutMiddleware();
		
		$url = 'http://' . tenant('id'). '.tenants.com/metadata';
		
		$response = $this->followingRedirects()->post ( $url, $elt);
		$response->assertStatus ( 500 );
				
		$new_count = Metadata::count ();
		$expected = $initial_count;
		$this->assertEquals ( $expected, $new_count, "metadata created, actual=$new_count, expected=$expected" );
	}
	
	public function test_store_delete() {
		// Post a creation request
		$metadata = Metadata::factory()->make(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$id = $metadata->save();
		
		$initial_count = Metadata::count ();
		
		$this->delete_tenant_url($this->user, 'metadata/' . $id, ['deleted']);
		
		$new_count = Metadata::count ();
		$expected = $initial_count - 1;
		$this->assertEquals ( $expected, $new_count, "metadata deleted, actual=$new_count, expected=$expected" );
	}

	public function test_edit_page() {		
		$metadata = Metadata::factory()->make(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$id = $metadata->save();
		
		$this->get_tenant_url($this->user, 'metadata/' . $id . '/edit', ['Edit Metadata']);
	}
	
	public function test_update() {
		
		$metadata = Metadata::factory()->make(['table' => 'users', 'field' => 'email', 'subtype' => 'email']);
		$subtype = $metadata->subtype;
		$options =  $metadata->options;
		$new_subtype = "phone";
		$new_options = "options";
		
		$this->assertNotEquals($subtype, $new_subtype);
		$this->assertNotEquals($options, $new_options);
		
		$id = $metadata->save();
		
		$elt = ['table' => 'users', 'field' => 'email', "subtype" => $new_subtype, "options" => $new_options, '_token' => csrf_token()];
				
		$this->put_tenant_url($this->user, 'metadata/' . $id, ['updated'], $elt);
		
		$back = Metadata::where('id', $id)->first();
		$this->assertEquals($new_subtype, $back->subtype);
		$this->assertEquals($new_options, $back->options);
		$back->delete();
	}
	
}
