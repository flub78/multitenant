<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:list';

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
    	$dirpath = storage_path() . "/app/backup";
    	
    	if (!is_dir($dirpath)) {
    		echo "creating " . $dirpath . "\n";
    		mkdir($dirpath);
    	}
    	
    	echo "Local backups:\n";
    	$backup_list = scandir($dirpath);
    	for ($i = 2; $i < count($backup_list); $i++) {
    		echo ($i - 1) . ") " . $backup_list[$i] . "\n";
    	}
    	echo ".";
        return 0;
    }
}
