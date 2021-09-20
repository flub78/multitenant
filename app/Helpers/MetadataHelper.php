<?php

namespace App\Helpers;

/**
 * Metadata interface
 *
 *
 * @author frederic
 *        
 */
class MetadataHelper {

	static public function underscoreToCamelCase($string, $capitalizeFirstCharacter = false) {
		$str = str_replace('_', '', ucwords($string, '_'));

		if (!$capitalizeFirstCharacter) {
			$str = lcfirst($str);
		}

		return $str;
	}

	static public function class_name(string $table) {
		return self::underscoreToCamelCase(rtrim($table, 's'), true);
	}

	static public function element(string $table) {
		return rtrim($table, 's');
	}
	
}