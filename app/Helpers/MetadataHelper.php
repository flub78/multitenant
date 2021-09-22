<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata;
use App\Models\Schema;

/**
 * Metadata interface
 *
 * Central point to get metadata associated to a table
 * 
 * @author frederic
 *        
 */
class MetadataHelper {

	/**
	 * Transform a string into CamelCase
	 * @param unknown $string
	 * @param boolean $capitalizeFirstCharacter
	 * @return mixed
	 */
	static public function underscoreToCamelCase($string, $capitalizeFirstCharacter = false) {
		$str = str_replace('_', '', ucwords($string, '_'));

		if (!$capitalizeFirstCharacter) {
			$str = lcfirst($str);
		}
		return $str;
	}

	/**
	 * Return the model class name
	 * @param string $table
	 * @return mixed
	 */
	static public function class_name(string $table) {
		return self::underscoreToCamelCase(rtrim($table, 's'), true);
	}

	
	/**
	 * @param string $table
	 * @return string
	 */
	static public function element(string $table) {
		return rtrim($table, 's');
	}
	
	/**
	 * @param String $table
	 * @return string
	 */
	static public function fillable_names (String $table) {
		$list = Schema::fieldList($table);
		$list = array_diff($list, ["created_at", "updated_at"]);  // remove some values
		$list = array_values($list); // re-index
		array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
		$res = implode(', ', $list); // transform into string
		return $res;
	}
	
	/**
	 * @param String $table
	 * @return string[][]
	 */
	static public function fillable (String $table) {
		return [
				['name' => 'fld1', 'display' => '{{$user->name}}', 'field_display' => 'display_1'],
				['name' => 'fld2', 'field_input' => 'field_2', 'field_display' => 'display_2'],
				['name' => 'fld3', 'field_input' => 'field_3', 'field_display' => 'display_3'],
		];
	}
	
	/**
	 * All the metadata for a table
	 * @param String $table
	 * @return \App\Helpers\String[]|string[]|array[]|mixed[]
	 */
	static public function metadata(String $table) {
		return array(
				'table' => $table,
				'class_name' => self::class_name($table),
				'fillable_names' => self::fillable_names($table),
				'element' => self::element($table),
				'fillable' => self::fillable($table)
		);
	}
}