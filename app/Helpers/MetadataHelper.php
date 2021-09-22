<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata as Meta;
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
	 * Element name
	 * @param string $table
	 * @return string
	 */
	static public function element(string $table) {
		return rtrim($table, 's');
	}

	static public function fillable_fields(String $table) {
		$list = Schema::fieldList($table);
		$list = array_diff($list, ["created_at", "updated_at"]);  // remove some values
		$list = array_values($list); // re-index
		return $list;
	}
	
	/**
	 * List of fillable fields
	 * @param String $table
	 * @return string
	 */
	static public function fillable_names (String $table) {
		$list = self::fillable_fields($table);
		array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
		$res = implode(', ', $list); // transform into string
		return $res;
	}
	
	// ###############################################################################################################
	// ###############################################################################################################
	
	static public function field_display (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		
		if ($subtype == "email") {
			return '<A HREF="mailto:{{$' . $table . '->' . $field . '}}">{{$' . $table . '->' . $field . '}}</A>';
		}
		
		return '{{$' . $table . '->'. $field. '}}';
	}
	
	static public function field_input_edit (String $table, String $field) {
		return "input_edit $table.$field";
	}
	
	static public function field_input_create (String $table, String $field) {
		return "input_create $table.$field";
	}
	
	static public function field_button_edit (String $table, String $field) {
		return "button_edit $table.$field";
	}
	
	static public function field_button_delete (String $table, String $field) {
		return "button_delete $table.$field";
	}
	
	static public function field_rule_edit (String $table, String $field) {
		return "rule_edit $table.$field";
	}
	
	static public function field_rule_create (String $table, String $field) {
		return "rule_create $table.$field";
	}
	
	/**
	 * Metadata for an individual field
	 * @param String $table
	 * @param String $field
	 * @return string[]|\App\Helpers\String[]
	 */
	static public function field_metadata(String $table, String $field) {
		return ['name' => $field, 
				'display' => self::field_display($table, $field), 
				'input_edit' => self::field_input_edit($table, $field), 
				'input_create' => self::field_input_create($table, $field), 
				'button_edit' => self::field_button_edit($table, $field), 
				'button_delete' => self::field_button_delete($table, $field), 
				'rule_edit' => self::field_rule_edit($table, $field), 
				'rule_create' => self::field_rule_create($table, $field), 
		];
	}
	
	/**
	 * An array of metadata for all fillable fields
	 * @param String $table
	 * @return string[][]
	 */
	static public function fillable (String $table) {
		$res = [];
		$list = self::fillable_fields($table);
		foreach ($list as $field) {
			$res[] = self::field_metadata($table, $field);
		}
		return $res;
	}
	
	/**
	 * All the metadata for a table
	 *
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