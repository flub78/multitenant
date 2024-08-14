<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;

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
		parent::__construct();
	}

	/**
	 * Create a backup
	 *
	 * @param string $tenant_id
	 */
	private function backup(string $tenant_id = "") {

		$database = TenantHelper::tenant_database($tenant_id);
		$fullname = TenantHelper::backup_fullname($tenant_id);

		if (!(env('DB_DATABASE') && env('DB_USERNAME'))) {
			echo  "missing environment variables\n";
			echo "DB_HOST: " . env('DB_HOST') . "\n";
			echo "DB_USERNAME: " . env('DB_USERNAME') . "\n";
			echo "DB_PASSWORD: " . env('DB_PASSWORD') . "\n";
			echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
			exit(1);
		}

		$res = BackupHelper::backup($database, $fullname, env('DB_HOST'), env('DB_USERNAME'),  env('DB_PASSWORD'));
		if ($res == '') {
			if (!$this->option('quiet')) {
				echo "backup $fullname created\n";
			}
		} else {
			echo "Error on backup $database $fullname";
		}
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		// one tenant backup
		if ($this->option('tenant')) {
			$this->backup($this->option('tenant'));
			return 0;
		}

		// backup of the central database
		$this->backup();

		// backup of all the tenants
		if ($this->option('all')) {
			foreach (TenantHelper::tenant_id_list() as $tenant_id) {
				$this->backup($tenant_id);
			}
		}
	}
}
