<?php
namespace App\Helpers;

use App\Models\Tenant;
use Carbon\Carbon;
  
class TenantHelper {
	
	/**
	 * Return the database name for a tenant or central database when no tenant is specified
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public static function tenant_database (string $tenant_id = "") {
		if ($tenant_id) {
			// It is safer to retrieve the name from the database
			$tnt = Tenant::whereId ( $tenant_id )->first ();

			if ($tnt) {
				return $tnt ['tenancy_db_name'];
			} else {
				echo "tenant $tenant_id not found in tenant table";
				return "";
			}
			
			// An alternative is tu follow the same convention than the tenant layer
			// return "tenant" . $tenant_id;
		} else {
			return env ( 'DB_DATABASE' );
		}
	}
	
	/**
	 * return storage path for tenant or central application
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public static function storage_dirpath(string $tenant_id = "") {
		if ($tenant_id) {
			// tenant case
			$db = TenantHelper::tenant_database ( $tenant_id );
			if ($db) {
				// regular tenant storage path
				return storage_path () . "/$db";
			} else {
				// tenant not found
				return "";
			}
			
			// by convention
			// return storage_path () . "/tenant" . $tenant_id;
		} else {
			return storage_path ();
		}
	}
	
	/**
	 * return backup storage
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public static function backup_dirpath(string $tenant_id = "") {
		$storage = TenantHelper::storage_dirpath($tenant_id);
		if ($storage)
			return $storage . '/app/backup';
		else 
			return "";
	}
	
	/**
	 * return a backup full file name
	 *
	 * @param string $tenant_id
	 */
	public static function backup_fullname(string $tenant_id = "", $filename = "") {
		if ($filename == "") {
			$filename = "backup-" . Carbon::now()->format('Y-m-d_His') . ".gz";
		}
		$storage = TenantHelper::backup_dirpath($tenant_id);
		if ($storage) {
			return $storage . '/' . $filename;
		}
		return "";
	}
	
	/**
	 * return the list of tenant id
	 * @return array
	 */
	public static function tenant_id_list() {
		$tenants = Tenant::all();
		$result = array();
		foreach ($tenants as $tenant) {
			array_push($result, $tenant->id);
		}
		return $result;
	}
	
}