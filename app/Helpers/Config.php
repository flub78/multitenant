<?php
namespace App\Helpers;

use App\Models\Tenants\Configuration;

  
/**
 * Some routines to handle configuration in tenant context
 * 
 * @author frederic
 *
 */
class Config {
	
	/**
	 */
	static public function config(string $key) {

		$config = Configuration::where('key', $key)->first();
		if ($config) {
			return $config->value;
		}
		return config($key);
	}
	
}