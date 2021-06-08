<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class BackUpCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create';

    /**
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
        $filename = "backup-" . Carbon::now()->format('Y-m-d_His') . ".gz";
        
        $mysqldump = 'c:\xampp\mysql\bin\mysqldump.exe';
        
        $command = "$mysqldump --user=" . env('DB_USERNAME') .
        	" --password=" . env('DB_PASSWORD') . 
        	" --host=" . env('DB_HOST') . " " . env('DB_DATABASE') .
        	"  | gzip > " . storage_path() . "/app/backup/" . $filename;
                
        $returnVar = NULL;
        $output  = NULL;
        
        exec($command, $output, $returnVar);
    }
}
