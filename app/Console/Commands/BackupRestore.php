<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;

/**
 * An artisan command to restore a database backup
 * Support central and tenant applications
 *
 * @author frederic
 *        
 */
class BackupRestore extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'backup:restore {--force : do not ask for confirmation} {--tenant= : Tenant to restore} {--pretend :  Dump the command that would be run} {backup_id}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Restore a local backup';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$backupId = $this->argument ( 'backup_id' );
		$tenant = $this->option ( 'tenant' );
		$quiet = $this->option ( 'quiet' );
		if (! $tenant)
			$tenant = "";

		$dirpath = TenantHelper::backup_dirpath ( $tenant );
		$backup_list = scandir ( $dirpath );

		// Look for the file specified by the user
		$selected_file = "";
		for($i = 2; $i < count ( $backup_list ); $i ++) {
			$num_id = $i - 1;
			if (($num_id == $backupId) || ($backup_list [$i] == $backupId)) {
				$selected_file = $backup_list [$i];
				break;
			}
		}

		if (! $selected_file) {
			echo "Backup $backupId not found";
			return 1;
		}

		// The backup exists
		$filename = TenantHelper::backup_fullname ( $tenant, $selected_file );
		$database = TenantHelper::tenant_database ( $tenant );
		
		if ($this->option ( 'force' ) || $this->confirm ( 'Are you sure you want to restore ' . $selected_file . ' ?' )) {

			BackupHelper::restore($filename, $database, $this->option ( 'pretend' ));

			if (!$quiet) echo 'backup ' . $selected_file . " restored";
		} else {
			echo "command cancelled";
		}
    	
        return 0;
    }
}
