<?php
namespace App\Helpers;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

  
/**
 * Static functions to restore a database
 * 
 * @author frederic
 *
 */
class BackupHelper {
	
	/**
	 * Restore a database
	 * 
	 * @param string $filename to restore
	 * @param string $database to overwrite
	 * @param boolean $pretend 
	 */
	public static function restore (string $filename, string $database, $pretend) {
		if (PHP_OS == "WINNT") {
			$mysql = 'c:\xampp\mysql\bin\mysql.exe';
		} else {
			$mysql = '/usr/bin/mysql';
		}
		
		$command = "gzip -d < " . $filename . "| $mysql --user=" . env ( 'DB_USERNAME' ) . " --password=" . env ( 'DB_PASSWORD' ) . " --host=" . env ( 'DB_HOST' ) . " " . $database;
		
		$returnVar = NULL;
		$output = NULL;
		
		Log::Debug("BackupHelper.restore : $command");
		
		if ($pretend) {
			echo "pretend: " . $command . "\n";
		} else {
			exec ( $command, $output, $returnVar );
		}
	}
}