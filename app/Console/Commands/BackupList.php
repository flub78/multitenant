<?php

namespace App\Console\Commands;

use App\Helpers\TenantHelper;
use Illuminate\Console\Command;

/**
 * An artisan command to list the backups stored on local storage
 * Supports central and tenant applications
 *
 * @author frederic
 *        
 */
class BackupList extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'backup:list {--all : All tenants} {--tenant= : one tenant}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List the local backups';

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
	private function listBackups(string $storage, string $context = "") {
		if (! is_dir ( $storage )) {
			echo "directory $storage not found\n";
			return;
		}
		echo "Local backups:$context\n";
		$backup_list = scandir ( $storage );
		for($i = 2; $i < count ( $backup_list ); $i ++) {
			echo ($i - 1) . ") " . $backup_list [$i] . "\n";
		}
		echo ".\n";
	}

	/**
	 * List the backups for a tenant
	 *
	 * @param string $tenant
	 */
	private function listTenantBackup(string $tenant_id) {
		$backup_storage = TenantHelper::backup_dirpath ( $tenant_id );
		echo "backup storage for $tenant_id = $backup_storage";

		if ($backup_storage) {
			echo "backup storage for $tenant_id = $backup_storage\n";
		} else {
			echo "backup storage for $tenant_id = $backup_storage not found\n";
			return;
		}
		$this->listBackups ( $backup_storage, "tenant=" . $tenant_id );
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		if ($this->option ( 'tenant' )) {
			$this->listTenantBackup ( $this->option ( 'tenant' ) );
			return 0;
		}

		$this->listBackups ( TenantHelper::backup_dirpath (), "central database" );

		if ($this->option ( 'all' )) {
			foreach ( TenantHelper::tenant_id_list () as $tenant_id ) {
				$this->listTenantBackup ( $tenant_id );
			}
		}
    	   	    	    	
        return 0;
    }
}
