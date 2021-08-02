<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TenantHelper;

/**
 * An artisan command to generate a backup in the local storage
 *
 * Support central and tenant databases
 *
 * @author frederic
 *        
 */
class BackUpCreate extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'backup:create  {--all : All tenants} {--tenant= : one tenant}';

	/**
	 *
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a database backup';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * Create a backup
	 *
	 * @param string $tenant_id
	 */
	private function backup(string $tenant_id = "") {
		if (PHP_OS == "WINNT") {
			$mysqldump = 'c:\xampp\mysql\bin\mysqldump.exe';
		} else {
			$mysqldump = '/usr/bin/mysqldump';
		}

		$database = TenantHelper::tenant_database ( $tenant_id );
		$fullname = TenantHelper::backup_fullname ( $tenant_id );

		if ($database && $fullname) {
			$mysqldump = 'c:\xampp\mysql\bin\mysqldump.exe';

			$cmd = "$mysqldump --user=" . env ( 'DB_USERNAME' ) . " --password=" . env ( 'DB_PASSWORD' ) . " --host=" . env ( 'DB_HOST' ) . " $database " . "  | gzip > $fullname";

			$returnVar = NULL;
			$output = NULL;

			exec ( $cmd, $output, $returnVar );

			echo "backup $fullname created\n";
		}
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		// one tenant backup
		if ($this->option ( 'tenant' )) {
			$this->backup ( $this->option ( 'tenant' ) );
			return 0;
		}

		// backup of the central database
		$this->backup ();

		// backup of all the tenants
		if ($this->option ( 'all' )) {
			foreach ( TenantHelper::tenant_id_list () as $tenant_id ) {
				$this->backup ( $tenant_id );
			}
		}
	}

}
