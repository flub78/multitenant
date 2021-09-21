<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata;
use App\Models\Schema;

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
	
	static public function fillable (String $table) {
		$list = Schema::fieldList($table);
		$list = array_diff($list, ["created_at", "updated_at"]);  // remove some values
		$list = array_values($list); // re-index
		array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
		$res = implode(', ', $list); // transform into string
		return $res;
	}
}