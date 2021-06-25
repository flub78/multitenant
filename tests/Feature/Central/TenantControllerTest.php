<?php

namespace tests\Feature\Central;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenant;

class TenantControllerTest extends TenantTestCase {
		
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
	 * Index view
	 *
	 * @return void
	 */
	public function test_tenants_index_view() {
				
		$this->be ( $this->user );
		$response = $this->get ( '/tenants' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( 'Domain' );
		$response->assertSeeText ( 'Edit' );
		$response->assertSeeText ( 'Delete' );
		$response->assertSeeText ( __('general.create') );
	}

	/**
	 * Create view
	 *
	 * @return void
	 */
	public function test_tenants_create_view() {
		$this->be ( $this->user );
		
		$response = $this->get ( '/tenants/create' );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( 'New tenant' );
		$response->assertSeeText ( 'Add tenant' );
	}
	
	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function test_tenant_edit_view_existing_element() {
		$this->be ( $this->user );
		
		$count = Tenant::count();
		$this->assertNotEquals(0, $count, "at least one tenant exist"); 
		
		$tnt = Tenant::first();
		$this->assertNotNull($tnt);
				
		$id = $tnt->id;
		$response = $this->get ( "/tenants/$id/edit" );
		$response->assertStatus ( 200 );
		$response->assertSeeText ( 'Edit tenant' );
	}

	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function test_tenant_show() {
		$this->be ( $this->user );
		
		$tnt = Tenant::first();
		$this->assertNotNull($tnt);
		
		$id = $tnt->id;
		$response = $this->get ( "/tenants/$id" );
		$response->assertStatus ( 200 );
	}
	
}
