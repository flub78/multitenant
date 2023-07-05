<?php
namespace App\Helpers;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

  
/**
 * Class to manage backups
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
	public static function restore (string $filename, string $database, $pretend = false) {
		if (PHP_OS == "WINNT") {
			$mysql = 'c:\xampp_php8\mysql\bin\mysql.exe';
		} else {
			$mysql = '/usr/bin/mysql';
		}
		// TODO : check if executable file exists

		$cmd = "gzip -d -c < " . $filename . "| $mysql --user=" . env ( 'DB_USERNAME' ) . " --password=" . env ( 'DB_PASSWORD' ) . " --host=" . env ( 'DB_HOST' ) . " " . $database;
		
		$returnVar = NULL;
		$output = NULL;
		
		Log::Debug("BackupHelper.restore : $cmd");
		
		if ($pretend) {
			echo "pretend: " . $cmd . "\n";
		} else {
			exec ( $cmd, $output, $returnVar );
		}
	}
	
	/**
	 * Create a backup file for a database
	 * 
	 * @param string $database
	 * @param string $backup_fullname
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 */
	public static function backup($database, $backup_fullname, $host, $user, $password) {
		if (PHP_OS == "WINNT") {
			$mysqldump = 'c:\xampp_php8\mysql\bin\mysqldump.exe';
		} else {
			// Default on Linux
			$mysqldump = '/usr/bin/mysqldump';			
		}
		// TODO : check if executable file exists

		// create the backup directory if it does not exist
		$dirname = dirname($backup_fullname);
		if (!is_dir($dirname)) {
			mkdir($dirname, 0777, true);
			if (!is_dir($dirname)) {
				throw new Exception("backup dir $dirname not created");
			}
		}
		
		if ($database && $backup_fullname) {
			
			$cmd = "$mysqldump --user=$user --password=$password --host=$host $database  | gzip > $backup_fullname";
			
			Log::Debug("BackupHelper.backup : $cmd");

			$returnVar = NULL;
			$output = NULL;
			
			// echo "backup cmd = $cmd\n";

			$exec = exec ( $cmd, $output, $returnVar );
			if ($output) {
				echo "mysqldump output: $output\n";
			}
			if ($returnVar != 0) {
				echo "mysqldump returns: $returnVar\n";
			}
			return $exec;
		} else {
			return false;
		}
	}
}