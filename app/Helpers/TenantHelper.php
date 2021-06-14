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
			return "tenant" . $tenant_id;			
		} else {
			return env ( 'DB_DATABASE' );
		}
	}
	
	/**
	 * Check if a tenant exists
	 * Multiple possible level, declared in tenant table, database exists on MySQL, storage exists, ...
	 * @param string $tenant_id
	 * @return boolean
	 */
	public static function exist (string $tenant_id = "") {
		$tnt = Tenant::whereId ( $tenant_id )->first ();
		if ($tnt) 
			return true;
		else
			return false;
	}
	
	/**
	 * return storage path for tenant or central application
	 * 
	 * Warning: storage_path has been modified to return something different in central and tenant application 
	 *
	 * @param string $tenant_id
	 * @return string
	 */
	public static function storage_dirpath(string $tenant_id = "") {
		if (!tenant('id')) {
			// Called from central application	
			if ($tenant_id) {
				// tenant case
				return storage_path () . "/tenant" . $tenant_id;
			} else {
				return storage_path ();
			}
		} else {
			// called from a tenant
			if (tenant('id') == $tenant_id) {
				// request for the current tenant
				return storage_path();
			} else {
				// request for another tenant, not sure that it should be allowed
				return "";
			}
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
			return $storage . DIRECTORY_SEPARATOR . $filename;
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
	
	/**
	 * count existing backups
	 * @return number
	 */
	public static function backup_count(string $tenant_id = "") {
		$dirpath = TenantHelper::backup_dirpath ($tenant_id);
		$backup_list = scandir ( $dirpath );
		
		return count ( $backup_list ) - 2;
	}
		
}