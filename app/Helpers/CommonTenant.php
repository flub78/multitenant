<?php
namespace App\Helpers;
  
class CommonTenant {
	
	public static function tenant_database (string $tenant_id = "") {
		if ($tenant_id) {
			return "tenant" . $tenant_id;
		} else {
			return env ( 'DB_DATABASE' );
		}
	}
	
	public static function storage_dirpath(string $tenant_id = "") {
		if ($tenant_id) {
			return storage_path () . "/tenant" . $tenant_id;
		} else {
			return storage_path ();
		}
	}
	
	public static function backup_dirpath(string $tenant_id = "") {
		return CommonTenant::storage_dirpath($tenant_id) . '/app/backup';
	}
}