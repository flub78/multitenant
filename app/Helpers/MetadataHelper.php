<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata as MetaModel;
use App\Models\Schema;
use Illuminate\Support\Facades\Log;

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
	 * @param boolean $capitalizeFirst
	 * @return mixed
	 */
	static public function underscoreToCamelCase($string, $capitalizeFirst = false) {
		$str = str_replace('_', '', ucwords($string, '_'));

		if (!$capitalizeFirst) {
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

	/**
	 * True if fillable in metadata field comments
	 * 
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function fillable($table, $field) {
		// look for options in metadata table
		$options = MetaModel::options($table, $field);		
		if ($options && array_key_exists('fillable', $options)) {
			return ($options['fillable'] == "true");
		}
		
		// Nothing in metadadata table look in comments
		$meta = Schema::columnMetadata($table, $field);
		if ($meta && array_key_exists('fillable', $meta)) {
			// it is specified in comment
			return ($meta['fillable'] == "yes");
		}
		
		// default 
		return true;		
	}
	
	/**
	 * True if the field must be displayed in the table list view
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function inTable($table, $field) {
		$subtype = self::subtype($table, $field);
		
		if (in_array($subtype, ['password_with_confirmation', 'password_confirmation'])) return false;
		$meta = Schema::columnMetadata($table, $field);
				
		if ($field == "id") return false;
		if (! $meta) return true;
		if (! array_key_exists('inTable', $meta)) return true;
		return ($meta['inTable'] == "yes");
	}
	
	/**
	 * True if the field must be displayed in the form views
	 * 
	 * Default is true
	 * InFOrm is a delarative information in metadata
	 * Information in metadata table overcomes comment in the database schema
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function inForm($table, $field) {
		$subtype = self::subtype($table, $field);
		
		// in form bitfields are replaced by an array of checkboxes
		if ('bitfield' == $subtype) return false;
		
		// look for options in metadata table
		$options = MetaModel::options($table, $field);
		
		if ($options && array_key_exists('inForm', $options)) {
			return ($options['inForm'] == "true");
		}
		
		// Nothing in metadadata table look in comments
		
		$meta = Schema::columnMetadata($table, $field);
		if ($field == "id") return false;		// not exact
		// should be excluded auto incremented primary keys "id" is not the only case
		
		if (! $meta) return true;
		if (! array_key_exists('inForm', $meta)) return true;
		return ($meta['inForm'] == "yes");
	}
	
	/**
	 * Return a subtype for a field. The information is looked for either in the json encoded
	 * comment of a field or in the metadata table.
	 * @param unknown $table
	 * @param unknown $field
	 * @return string
	 */
	static public function subtype($table, $field) {
		
		// value from metadatatable takes precedence
		$subtype = MetaModel::subtype($table, $field);
		if ($subtype != "") {
			return $subtype;
		}

		// look in the field comment
		$meta = Schema::columnMetadata($table, $field);
		if ($meta && array_key_exists('subtype', $meta)) return $meta['subtype'];
					
		// Not found, maybe it's a derived field, look for root field
		if (preg_match('/(.*)(\_confirmation)/', $field, $matches)) {
			$root = $matches[1];
			
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "password_with_confirmation")) {
				return "password_confirmation";
			}
		}
		
		if (preg_match('/(.*)(\_date)/', $field, $matches)) {
			$root = $matches[1];
						
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "datetime_with_date_and_time")) {
				return "datetime_date";
			}
		}

		if (preg_match('/(.*)(\_time)/', $field, $matches)) {
			$root = $matches[1];
						
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "datetime_with_date_and_time")) {
				return "datetime_time";
			}
		}
				
		// maybe it's a foreign key
		$fk = Schema::foreignKey($table, $field);
		if ($fk) {
			return "foreign_key";
		}
		
		// not found anywhere
		return "";
	}
	
	/**
	 * Return a type for a field. 
	 * @param unknown $table
	 * @param unknown $field
	 * @return string
	 */
	static public function type($table, $field) {
		$full_type = Schema::columnType($table, $field);
						
		if (! $full_type) {
			$subtype = self::subtype($table, $field);
			if ($subtype == "password_confirmation") {
				return "password";
			} else if ($subtype == "password_with_confirmation") {
				return "password";
			} else if ($subtype == "datetime_date") {
				return "date";
			} else if ($subtype == "datetime_time") {
				return "time";
			}
		}
		$first = explode(' ', $full_type)[0];
		
		$pattern = '/(.*)(\(\d*\)*)/';
		if (preg_match($pattern, $first, $matches)) {
			// var_dump($matches);
			return $matches[1];
		}
		return $first;
	}
	
	static public function field_metadata ($table, $field) {
		// look for options in metadata
		$options = MetaModel::options($table, $field);
		
		if ($options) return $options;
		
		// not found in metadata table, look in comments
		$meta = Schema::columnMetadata($table, $field);

		return $meta;
	}
	
	/**
	 * Returns a list of field to display in the GUI from a column name in the database. This mechanism gives the possibility
	 * to hide some fields by returning and empty list or to generate several fields from one column like password and password confirm from 
	 * a single password column.
	 * 
	 * @param String $table
	 * @param String $field
	 * @return \App\Helpers\String[]
	 */
	static public function derived_fields(String $table, String $field) {
		if (in_array($field, ["id", "created_at", "updated_at"])) {
			return [];
		}
		
		$subtype = self::subtype($table, $field);
		
		if ($subtype == "password_with_confirmation") {
			return [$field, $field . "_confirmation"];
		} elseif ($subtype == "datetime_with_date_and_time") {
			return [$field . "_date", $field . "_time"];
		} elseif ($subtype == 'bitfield') {
			return [$field . "_boxes"];
		}
		return [$field];
	}
	
	/**
	 * Returns a list with fillable fields
	 * 
	 * Fillable fields are mass assignable.
	 * Derived fields are additional fields used in form : password confirmation,
	 * derived date and time of a datetime, bitfield chechboxes. They are never
	 * mass assignable.
	 * 
	 * @param String $table
	 * @return array
	 */
	static public function fillable_fields(String $table) {
		$list = Schema::fieldList($table);
		if (! $list) return "";
		$full_list = []; 
		foreach ($list as $field) {
			if (! self::fillable($table, $field)) continue;
			if (in_array($field, ["id", "created_at", "updated_at"])) continue;
			
			$full_list[] = $field;
		}
		return $full_list;
	}

	/**
	 * Returns a list of fields which are present in forms.
	 * The same list os used to generate validation rules.
	 * 
	 * @param String $table
	 * @return array
	 */
	static public function form_fields(String $table) {
		$list = Schema::fieldList($table);
		if (! $list) return "";
		$full_list = [];
		foreach ($list as $field) {
			if (! self::fillable($table, $field)) continue;
			if (in_array($field, ["id", "created_at", "updated_at"])) continue;
			
			$derived_flds = self::derived_fields($table, $field);
			foreach ($derived_flds as $new_field) {
				$full_list[] = $new_field;
			}			
		}
		return $full_list;
	}
	
	/**
	 * List of fillable fields into a comma separated string
	 * @param String $table
	 * @return string
     *
     * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function fillable_names (String $table) {
		$list = self::fillable_fields($table);
		if (! $list) return "";
		array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
		$res = implode(', ', $list); // transform into string
		return $res;
	}
	
}