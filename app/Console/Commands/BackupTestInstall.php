<?php

namespace App\Console\Commands;

use App\Helpers\TenantHelper;
use Illuminate\Console\Command;

/**
 * An artisan copy the latest backup to be used for testing
 * Supports central and tenant applications
 *
 * @author frederic
 *        
 */
class BackupTestInstall extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'backup:test_install {--tenant= : one tenant}  {--pretend : simulation, no actions}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Copy the latest backup for testing';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * List the backups from a directory
	 *
	 * @param string $storage
	 * @param string $context
	 */
	private function copyBackup(string $storage, string $context = "") {
		
		$pretend = $this->option('pretend');
		
		if (! is_dir ( $storage )) {
			echo "directory $storage not found\n";
			return;
		}
		echo "Local backups:$context\n";
		$backup_list = scandir ( $storage );
		$count = count ( $backup_list );
		if ( $count < 3 ) {
			echo "No backups found in $storage, generate a backup before to execute this command\n";
			return;
		}
		$to_copy = "$storage/" . $backup_list [$count - 1];
		
		$name = ($context == "central database") ? "central_nominal.gz" : "tenant_nominal.gz";
		foreach (["tests/data/", "storage/app/tests/"] as $dir) {
			$dest = $dir . $name;
			echo "copy $to_copy " . $dest . "\n";
			if (!$pretend) {
				if (!copy($to_copy, $dest)) {
					echo "\tCopy error...\n";
				}
			}
		}
		echo ".\n";
	}

	/**
	 * List the backups for a tenant
	 *
	 * @param string $tenant
	 */
	private function copyTenantBackup(string $tenant_id) {
		$backup_storage = TenantHelper::backup_dirpath ( $tenant_id );
		echo "backup storage for $tenant_id = $backup_storage";

		if ($backup_storage) {
			echo "backup storage for $tenant_id = $backup_storage\n";
		} else {
			echo "backup storage for $tenant_id = $backup_storage not found\n";
			return;
		}
		$this->copyBackup ( $backup_storage, "tenant=" . $tenant_id );
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		if ($this->option ( 'tenant' )) {
			$this->copyTenantBackup ( $this->option ( 'tenant' ) );
			return 0;
		}

		$this->copyBackup ( TenantHelper::backup_dirpath (), "central database" );
    	   	    	    	
        return 0;
    }
}
