<?php

namespace tests\Feature\Central;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Helpers\DirHelper;
use App\Helpers\TenantHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

/**
 * Test of the tenant controller
 * 
 * @author frederic
 *
 */
class TenantControllerTest extends TenantTestCase {

	protected $tenancy = false;

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
	 * Index view
	 *
	 * @return void
	 */
	public function test_tenants_index_view() {

		$this->be($this->user);
		$response = $this->get('/tenants');
		$response->assertStatus(200);
		$response->assertSeeText('Domain');
		$response->assertSeeText('Edit');
		$response->assertSeeText('Delete');
		$response->assertSeeText(__('general.create'));
	}

	/**
	 * Create view
	 *
	 * @return void
	 */
	public function test_tenants_create_view() {
		$this->be($this->user);

		$response = $this->get('/tenants/create');
		$response->assertStatus(200);
		$response->assertSeeText('New tenant');
		$response->assertSeeText('Add tenant');
	}

	/**
	 * Edit view
	 *
	 * @return void
	 */
	public function test_tenant_edit_view_existing_element() {
		$this->be($this->user);

		$this->create_tenant("autotest", $this->domain("autotest"));

		$count = Tenant::count();
		$this->assertNotEquals(0, $count, "at least one tenant exist");

		$tnt = Tenant::first();
		$this->assertNotNull($tnt);

		$id = $tnt->id;
		$response = $this->get("/tenants/$id/edit");
		$response->assertStatus(200);
		$response->assertSeeText('Edit tenant');

		$this->destroy_tenant("autotest");
	}

	protected function destroy_tenant($id) {
		$tenant = Tenant::findOrFail($id);
		$id = $tenant->id;
		$tenant->delete();

		// delete tenant storage
		$storage = TenantHelper::storage_dirpath($id);
		DirHelper::rrmdir($storage);
	}

	protected function create_tenant($tenant_id, $domain) {
		// normally the tenant should not exist
		$tenant = Tenant::find($tenant_id);
		if ($tenant) {
			// but it does
			$this->destroy_tenant($tenant->id);
		}

		$tenant = Tenant::create(['id' => $tenant_id]);

		$tenant->domains()->create(['domain' => $domain]);

		// create local storage for the tenant
		$storage = TenantHelper::storage_dirpath($tenant_id);
		if (!is_dir($storage)) {
			mkdir($storage, 0755, true);
		}
	}

	public function database_exists(String $db_name) {
		$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
		$db = DB::select($query, [$db_name]);
		return ! empty($db);
	}

	public function test_store() {
		$this->be($this->user);

		$tenant_id = "autotest";
		$domain = $this->domain($tenant_id);
		$database = "tenant" . $tenant_id;
		$storage = TenantHelper::storage_dirpath($tenant_id);

		// call the store entry of the controller
		$elt = ["id" => $tenant_id, "domain" => $domain, '_token' => csrf_token()];
		$response = $this->post("/tenants/", $elt);
		$response->assertStatus(302, "call the store entry of the controller, url=/tenants/, \$elt=" . json_encode($elt));


		// check that the tenant exists
		$tenant = Tenant::findOrFail($tenant_id);
		$this->assertNotNull($tenant);

		// check that the database exists
		$this->assertTrue($this->database_exists($database));

		// check that the local storage exists
		$this->assertTrue(is_dir($storage));

		// delete the tenant
		$this->destroy_tenant($tenant_id);

		// check that the database has been deleted
		$this->assertFalse($this->database_exists($database));

		// local storage deleted
		$this->assertFalse(is_dir($storage));
	}

	public function test_update() {
		$this->be($this->user);

		// create a tenant
		$this->create_tenant("autotest", $this->domain("autotest"));

		// trigger the update entry of the controller
		$url = URL::to('/') . '/tenants/autotest';

		$elt = ["id" => "autotest", "domain" => "autotest.newdomain.com", '_token' => csrf_token()];
		$response = $this->put($url, $elt);
		$response->assertStatus(302);

		// check that the tenant has been updated

		// delete the database
		// delete the local storage
		$this->destroy_tenant("autotest");
	}

	public function test_delete() {
		$this->be($this->user);

		$tenant_id = "autotest2";
		$domain = $this->domain($tenant_id);
		$database = "tenant" . $tenant_id;
		$storage = TenantHelper::storage_dirpath($tenant_id);

		// create a tenant
		$this->create_tenant($tenant_id, $domain);

		// trigger the delete entry of the controller
		$url = URL::to('/') . '/tenants/' . $tenant_id;

		$response = $this->delete($url);
		// $response->assertStatus ( 302 );

		// check that the database has been deleted
		$this->assertFalse($this->database_exists($database));

		// local storage deleted
		$this->assertFalse(is_dir($storage));
	}
}
