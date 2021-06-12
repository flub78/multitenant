<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TenantHelper;

// Use Storage;

class BackupRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore {--force} {--tenant= : one tenant} {backup_id}';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    	$backupId = $this->argument('backup_id');
    	$tenant = $this->option('tenant');
    	if (!$tenant) $tenant = "";
    	
    	$dirpath = TenantHelper::backup_dirpath($tenant);
    	$backup_list = scandir($dirpath);
    	
    	// Look for the file specified by the user
    	$selected_file = "";
    	for ($i = 2; $i < count($backup_list); $i++) {
    		$num_id = $i - 1;
    		if (($num_id == $backupId) || ($backup_list[$i] == $backupId)) {
    			$selected_file = $backup_list[$i];
    			break;
    		}
    	}
    	
    	if (!$selected_file) {
    		echo "Backup $backupId not found";
    		return 1;
    	}
    	 	
    	// The backup exists
    	$filename = TenantHelper::backup_fullname($tenant, $selected_file);
    	$database = TenantHelper::tenant_database($tenant);
    	
    	if ( $this->option('force') || $this->confirm('Are you sure you want to restore ' . $selected_file . ' ?')) {
    		
    		$mysql = 'c:\xampp\mysql\bin\mysql.exe';
    		    		
    		$command = "gzip -d < " . $filename . 
    			"| $mysql --user=" . env('DB_USERNAME') .
    			" --password=" . env('DB_PASSWORD') .
    			" --host=" . env('DB_HOST') . " " . $database;
    		    		
    		$returnVar = NULL;
    		$output  = NULL;
    		
    		exec($command, $output, $returnVar);
    		
    		echo 'backup ' . $selected_file . " restored";	
     		
    	} else {
    		echo "command cancelled";
    	}
    	
        return 0;
    }
}
