<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\BackupHelper;

/**
 * User CRUD
 *
 * @author frederic
 *        
 */
class BackupsTest extends DuskTestCase {

	function __construct() {
		parent::__construct ();
		
		$this->wait = 0;
	}

	public function setUp(): void {
		parent::setUp ();

		$database = env ( 'DB_DATABASE' );

		/**
		 echo "\nENV=" . env ( 'APP_ENV' ) . "\n";
		 echo "login=" . env ( 'TEST_LOGIN' ) . "\n";
		 echo "password=" . env ( 'TEST_PASSWORD' ) . "\n";
		 echo "url=" . env('APP_URL') . "\n";
		 echo "database=$database\n";
		 */

		sleep($this->wait);
		// Restore a test database
		$filename = storage_path () . '/app/tests/central_nominal.gz';
		$this->assertFileExists ( $filename, "central_nominal test backup found" );
		BackupHelper::restore ( $filename, $database, false );
	}

	public function tearDown(): void {
		parent::tearDown ();
	}
	
	public function test_backup_access() {
		
		$this->browse ( function (Browser $browser) {
			$this->login($browser);
			
			$browser->visit ( '/backup' )
			->assertPathIs ( '/backup' )
			->assertSee ( 'Multi Central Application' )
			->assertSee ( 'Number' )
			->assertSee ( 'Backup' )
			->assertSee ( 'Restore' )
			->assertSee ( 'Delete' );
			
			$count = $this->datatable_count($browser);
			echo "\n$count existing backups\n";
			
			// As long as I have difficulties to press the delete buttons with
			// Dusk there is not much that I can do ....
			
			$this->logout($browser);
		} );
	}
}
