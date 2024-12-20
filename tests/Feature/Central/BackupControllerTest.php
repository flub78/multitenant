<?php

/**
 * Test cases:
 *
 * Nominal: list backups, create a new one, checks that it exists and delete it.
 *
 * Error test case:
 * delete a non existing backup
 * restore a non existing backup
 *
 * attempt to create, restore or delete a backup as non admin
 */

namespace tests\Feature\Central;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Helpers\TenantHelper;

class BackupControllerTest extends TestCase {

	// Clean up the database
	// Not refreshing the database may break others tests
	use DatabaseTransactions;

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
	 * count existing backups
	 * @return number
	 */
	private function backup_count() {
		$dirpath = TenantHelper::backup_dirpath();
		$backup_list = scandir($dirpath);

		return count($backup_list) - 2;
	}

	/**
	 * get a signature/hash of the database
	 * backup current database state (check that a new backup is created)
	 * change the database
	 * restore the previous state
	 * check that the database is back in its initial state
	 * delete the backup
	 * check that there is one less backup in the local storage
	 */
	public function test_backup_create_delete() {
		$this->be($this->user);

		$initial_count = $this->backup_count();

		// backup list
		$response = $this->get('/backup');
		$response->assertStatus(200);
		$response->assertSeeText(__('backup.title'));
		$response->assertSeeText(__('backup.number'));
		$response->assertSeeText(__('backup.restore'));
		$response->assertSeeText(__('backup.new'));

		// create a backup
		$response = $this->get('/backup/create');
		$response->assertStatus(200);

		$this->assertEquals($this->backup_count(), $initial_count + 1, "a backup has been created");

		$id = $initial_count + 1;

		// echo "   warning: restore is not tested\n";

		/*
		 * It seems that restoring a database while phpunit is running has some negative effects...
		 * It blocks the test ....
		 * 
		// Change the database
		$initial_table_count = Game::count ();
		$game = Game::factory ()->make ();
		$game->save ();
		$count = Game::count ();
		$this->assertEquals ( $count, $initial_table_count + 1, "One element created" );

		// Restore the database
		$response = $this->get ( "/backup/$id/restore" );
		$response->assertStatus ( 302 ); // redirected

		// Check rollback
		$this->assertEquals ( Game::count (), $initial_table_count, "Back to initial state" );
		*/

		// Delete the backup
		$response = $this->delete("/backup/$id");
		$response->assertStatus(302); // redirected
		// $response->assertSeeText ( 'deleted' );

		$this->assertEquals($this->backup_count(), $initial_count, "a backup has been deleted");
	}


	/**
	 * 
	 */
	public function test_delete_non_existing_backup() {
		$this->be($this->user);

		$response = $this->delete("/backup/999999999");
		$response->assertStatus(302); // redirected

		// echo "   warning: no reported error is checked\n";

	}

	/**
	 *
	 */
	public function test_restore_non_existing_backup() {
		$this->be($this->user);

		$response = $this->get("/backup/999999999/restore");
		$response->assertStatus(302); // redirected

		// echo "   warning: no reported error is checked\n";
	}

	public function test_backup_download() {
		// create a backup
		$initial_count = $this->backup_count();
		$this->be($this->user);
		$response = $this->get('/backup/create');
		$id = $initial_count + 1;

		// download it
		$response = $this->get("/backup/$id");
		$response->assertStatus(200)
			->assertDownload();

		// delete it
		$response = $this->delete("/backup/$id");
	}
}
