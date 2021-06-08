<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:delete {--force} {backup_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a local backup';

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
    	
    	$dirpath = storage_path() . "/app/backup/";
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
    	if ($this->option('force') || $this->confirm('Delete ' . $selected_file . '?')) {
    		$filename = storage_path() . "/app/backup/" . $selected_file;
    		unlink($filename);
    		
    		if (file_exists($filename)) {
    			echo "Error deleting " . $selected_file;
    			return 1;
    		} else {
    			echo $selected_file . " deleted";
    			return 0;
    		}
    		
    	} else {
    		echo "command cancelled";
    	}
    	return 0;
    }
}
