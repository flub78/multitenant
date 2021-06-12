<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Carbon\Carbon;
use App\Helpers\CommonTenant;


/**
 * This class provide tenant aware services.
 *
 * @author frederic
 *        
 */
abstract class TenantCommand extends Command {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * Return the database name for a tenant or central database when no tenant is specified
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public static function database_name(string $tenant_id = "") {
		if ($tenant_id) {
			$tnt = Tenant::whereId ( $tenant_id )->first ();

			if ($tnt) {
				return $tnt ['tenancy_db_name'];
			} else {
				return "";
			}
		}
		return env ( 'DB_DATABASE' );
	}

	/**
	 * return storage path for tenant or central application
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public function storage_path(string $tenant_id = "") {
		if ($tenant_id) {
			// tenant case
			$db = database_name ( $tenant_id );
			if ($db) {
				// regular tenant storage path
				return storage_path () . "/$db";
			} else {
				// tenant not found
				return "";
			}
		}

		// storage path for central application
		$storage = storage_path ();
		return $storage;
	}

	/**
	 * return backup storage
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public function backup_dirpath(string $tenant_id = "") {
		$storage = $this->storage_path ( $tenant_id );
		if ($storage) {
			return $storage . "/app/backup";
		}
		return "";
	}
	
	/**
	 * return a backup full file name
	 * 
	 * @param string $tenant_id
	 */
	public function backup_fullname(string $tenant_id = "", $filename = "") {
		if ($filename == "") {
			$filename = "backup-" . Carbon::now()->format('Y-m-d_His') . ".gz";
		}
		$storage = $this->backup_dirpath($tenant_id);
		if ($storage) {
			return $storage . '/' . $filename;
		}
		return "";
	}

	/**
	 * return the list of tenant id
	 * @return array
	 */
	public function tenant_id_list() {
		$tenants = Tenant::all();
		$result = array();
		foreach ($tenants as $tenant) {
			array_push($result, $tenant->id);
		}
		return $result;		
	}
}
