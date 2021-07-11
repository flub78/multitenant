<?php
namespace App\Helpers;

use App\Models\Tenant;
use Carbon\Carbon;
  
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
		$mysql = 'c:\xampp\mysql\bin\mysql.exe';
		
		$command = "gzip -d < " . $filename . "| $mysql --user=" . env ( 'DB_USERNAME' ) . " --password=" . env ( 'DB_PASSWORD' ) . " --host=" . env ( 'DB_HOST' ) . " " . $database;
		
		$returnVar = NULL;
		$output = NULL;
		
		if ($pretend) {
			echo "pretend: " . $command . "\n";
		} else {
			exec ( $command, $output, $returnVar );
		}
	}
}