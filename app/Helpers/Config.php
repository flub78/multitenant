<?php
namespace App\Helpers;

use App\Models\Tenants\Configuration;
use Exception;

  
/**
 * Some routines to handle configuration in tenant context
 * 
 * use App\Helpers\Config;
 * 
 * $tz = Config::config('app.timezone');
 * 
 * 
 * @author frederic
 *
 */
class Config {

	/**
	 * Return the value found in database
	 * or the value from configuration files if it does not exist
	 * 
	 * @param string $key
	 * @return unknown|mixed|\Illuminate\Config\Repository
	 */
	static public function config(string $key) {

		try {
			$config = Configuration::find($key);
			if ($config) {
				return $config->value;
			}
		} catch (Exception $e) {
			// likely central application, fall back to config
			// echo "exception:" . $e->getMessage();
		}
		return config($key);
	}
	
	/**
	 * Update a configuration value in database or create it if it does not exist
	 * 
	 * @param string $key
	 * @param string $value
	 */
	static public function set(string $key, string $value) {
		$cfg = Configuration::find($key);
		if ($cfg) {
			$cfg->update(['value' => $value]);
		} else {
			Configuration::create(['key' => $key, 'value' => $value]);
		}
	}
}