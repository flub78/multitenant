<?php

namespace App\Console\Commands;

use App\Helpers\TenantHelper;
use Illuminate\Console\Command;

/**
 * This is a command to check the date of review of the project files
 * 
 * Reviews are identified by a line with "reviewed"
 * 
 * I need to:
 * - find out the date of the last review for one or several files
 * - identify the files that have never been reviewed
 * - identify the files which have been updated since the last review
 */


/**
 * Scan a directory and fill a result array
 * 
 * @param unknown $dir
 * @param array $results
 * @return unknown|string
 */
function getDirContents($dir, &$results = array()) {
	$files = scandir($dir);

	foreach ($files as $key => $value) {
		$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
		if (!is_dir($path)) {
			$results[] = $path;
			echo "\t$path\n";
			$cmd = 'git log -1 --pretty="format:%ci" ' . $path;
			// echo "\ncmd = $cmd";

			$returnVar = NULL;
			$output = NULL;
			exec($cmd, $output, $returnVar);
			foreach ($output as $line) {
				echo "\t\t" . $line . "\n";
			}

			$cmd = 'grep reviewed ' . $path;
			$returnVar = NULL;
			$output = NULL;
			exec($cmd, $output, $returnVar);
			foreach ($output as $line) {
				echo "\t\t" . $line . "\n";
			}

			echo "\n";
		} else if ($value != "." && $value != "..") {
			getDirContents($path, $results);
			$results[] = $path;
			echo "$path:\n";
		}
	}

	return $results;
}

/**
 * An artisan command to check file review dates
 *
 * Usage: php artisan dev:review
 * 
 * @author frederic
 *        
 */
class DevReview extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dev:review';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List the date of review of the project files';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$verbose = $this->option('verbose');

		foreach (['app', 'database', 'resources', 'tests'] as $file) {
			getDirContents($file);
		}
		return 0;
	}
}
