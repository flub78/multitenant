<?php
namespace App\Helpers;

use App\Models\Tenants\Configuration;
use Exception;

  
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

		try {
			$config = Configuration::where('key', $key)->first();
			if ($config) {
				return $config->value;
			}
		} catch (Exception $e) {
			// likely central application, fall back to config
		}
		return config($key);
	}
	
}