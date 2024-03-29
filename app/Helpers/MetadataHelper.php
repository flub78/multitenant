<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata as MetaModel;
use App\Models\Schema;
use App\Models\ViewSchema;
use Illuminate\Support\Facades\Log;

/**
 * Metadata interface
 *
 * Central point to get metadata associated to a table. In other words to fetch information 
 * form the database comments information of from the metadata table.
 * 
 * @author frederic
 *        
 */
class MetadataHelper {

    public static $memoization = [];
    
    /**
     * Some tests create metadata on the fly and need a way to disable the memoizer
     */
    static  public function reset_memoizer () {
        self::$memoization = [];
    }
    
    /**
	 * Transform a string into CamelCase
	 * @param string $string
	 * @param bool $capitalizeFirst
	 * @return mixed
	 */
	static public function underscoreToCamelCase(string $string, bool $capitalizeFirst = false) {
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
	static public function class_name(string $table) : string {
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
	    
	    $key = 'fillable_' . $table . '___' . $field;
	    if (array_key_exists($key, self::$memoization)) {
	        return self::$memoization[$key];
	    }
	    
		// look for options in metadata table
		$options = MetaModel::options($table, $field);		
		if ($options && array_key_exists('fillable', $options)) {
		    self::$memoization[$key] = ($options['fillable'] == "true");
		    return self::$memoization[$key];
		}
		
		// Nothing in metadadata table look in comments
		$meta = Schema::columnMetadata($table, $field);
		if ($meta && array_key_exists('fillable', $meta)) {
			// it is specified in comment
		    self::$memoization[$key] = ($meta['fillable'] == "yes");
		    return self::$memoization[$key];
		}
		
		// default 
		self::$memoization[$key] = true;
		return self::$memoization[$key];
	}
	
	/**
	 * True if in_filter in metadata field comments
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function in_filter($table, $field) {
	    
	    $key = 'in_filter_' . $table . '___' . $field;
	    if (array_key_exists($key, self::$memoization)) {
	        return self::$memoization[$key];
	    }
	    
	    // look for options in metadata table
	    $options = MetaModel::options($table, $field);
	    if ($options && array_key_exists('in_filter', $options)) {
	        self::$memoization[$key] = ($options['in_filter'] == "yes");
	        return self::$memoization[$key];
	    }
	    
	    // Nothing in metadadata table look in comments
	    $meta = Schema::columnMetadata($table, $field);
	    if ($meta && array_key_exists('in_filter', $meta)) {
	        // it is specified in comment
	        self::$memoization[$key] = ($meta['in_filter'] == "yes");
	        return self::$memoization[$key];
	    }
	    
	    // default
	    self::$memoization[$key] = false;
	    return self::$memoization[$key];
	}
	
	
	/**
	 * True if the field must be displayed in the table list view
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function inTable($table, $field) {
	    if (is_array($field)) {
	        var_dump($field);
	        throw new Exception('MetadataHelper.inTable \$field should b ea scalar');
	    }
	    
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
	 * @param string $table
	 * @param string $field
	 * @return string
	 */
	static public function subtype(string $table, string $field) {
	    
	    $key = 'subtype_' . $table . '___' . $field;
	    if (array_key_exists($key, self::$memoization)) {
	        return self::$memoization[$key];
	    }
		
		// value from metadatatable takes precedence
		$subtype = MetaModel::subtype($table, $field);
		if ($subtype != "") {
		    self::$memoization[$key] = $subtype;
			return $subtype;
		}

		// look in the field comment
		$meta = Schema::columnMetadata($table, $field);
		if ($meta && array_key_exists('subtype', $meta)) {
		    self::$memoization[$key] = $meta['subtype'];
		    return $meta['subtype'];
		}
					
		// Not found, maybe it's a derived field, look for root field
		// Is it a password confirmation ?
		if (preg_match('/(.*)(\_confirmation)/', $field, $matches)) {
			$root = $matches[1];
			
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "password_with_confirmation")) {
			    self::$memoization[$key] = "password_confirmation";
			    return self::$memoization[$key];
			}
		}
		
		// maybe it's a bitfield
		if (preg_match('/(.*)(\_boxes)/', $field, $matches)) {
			$root = $matches[1];
			
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "bitfield")) {
			    self::$memoization[$key] = "bitfield_boxes";
			    return self::$memoization[$key];
			}
		}
		
		// maybe it's a foreign key
		$fk = Schema::foreignKey($table, $field);
		if ($fk) {
		    self::$memoization[$key] = "foreign_key";
		    return self::$memoization[$key];
		}
		
		// not found anywhere
		self::$memoization[$key] = "";
		return self::$memoization[$key];
	}
	
	/**
	 * Return a type for a field. 
	 * @param unknown $table
	 * @param unknown $field
	 * @return string
	 */
	static public function type($table, $field) {
	    $key = 'type_' . $table . '___' . $field;
	    if (array_key_exists($key, self::$memoization)) {
	        return self::$memoization[$key];
	    }
	    
		$full_type = Schema::columnType($table, $field);
						
		if (! $full_type) {
			$subtype = self::subtype($table, $field);
			if ($subtype == "password_confirmation") {
			    self::$memoization[$key] = "password";
			    return self::$memoization[$key];
			} else if ($subtype == "password_with_confirmation") {
			    self::$memoization[$key] = "password";
			    return self::$memoization[$key];
			} 
		}
		$first = explode(' ', $full_type)[0];
		
		$pattern = '/(.*)(\(\d*\)*)/';
		if (preg_match($pattern, $first, $matches)) {
		    self::$memoization[$key] = $matches[1];
		    return self::$memoization[$key];
		}
		self::$memoization[$key] = $first;
		return $first;
	}
	
	static public function field_metadata ($table, $field) {
		// look for options in metadata table
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
	 * bitfield chechboxes. They are never mass assignable.
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
	 * Returns a list with fields that must be included in filter
	 *
	 * @param String $table
	 * @return array
	 */
	static public function filter_fields(String $table) {
	    $list = Schema::fieldList($table);
	    if (! $list) return "";
	    $full_list = [];
	    foreach ($list as $field) {
	        if (! self::in_filter($table, $field)) continue;
	        if (in_array($field, ["id", "created_at", "updated_at"])) continue;
	        
	        $full_list[] = $field;
	    }
	    return $full_list;
	}
	
	
	/**
	 * Returns a list of fields which are present in forms.
	 * The same list is used to generate validation rules.
	 * 
	 * @param String $table
	 * @return array
	 */
	static public function form_fields(String $table) {
	    // start from the database field list
		$list = Schema::fieldList($table);
		if (! $list) return "";
		$full_list = [];
		foreach ($list as $field) {
		    // ignore non fillable fields
			if (! self::fillable($table, $field)) continue;
			// ignore some work fields
			if (in_array($field, ["id", "created_at", "updated_at"])) continue;
			
			// in most cases derived_fields also returns the base field
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
	
	/**
	 * List of filter fields into a comma separated string

	 * @param String $table
	 * @return string
	 *
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function filter_names (String $table) {
	    $list = self::filter_fields($table);
	    if (! $list) return "";
	    array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
	    $res = implode(', ', $list); // transform into string
	    return $res;
	}
	
    /**
     * Returns the primary index of a table
     * 
     * @param String $table
     * @return unknown|string
     */
    public static function primaryIndex(String $table) {
		return Schema::primaryIndex($table);
	}	

	/**
     * Returns the table referenced by a foreign key
     * 
     * @param string $table
     * @param string $field
     * @return NULL
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public static function foreignKeyReferencedTable (string $table, string $field) {
		return Schema::foreignKeyReferencedTable($table, $field);
	}

	/**
     * Returns true when a column is required in a database table
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function required (string $table, string $field) {
		return Schema::required($table, $field);
	}

	    /**
     * Returns the size of a field or 0 when undefined
     * 
     * @param string $table
     * @param string $field
     * @return 0|number
     */
    public static function columnSize (string $table, string $field) {
		return Schema::columnSize($table, $field);
	}

	/**
     * Returns an object containing column information
     *    Attributes are Field, Type, Null, Key, Default, Extra and Comment
     *    
     * @param string $table
     * @param string $field
     * @return NULL| the info object
     */
    public static function columnInformation (string $table, string $field) {
		return Schema::columnInformation($table, $field);
	}

	/**
     * Returns the list of fields of a table
     * 
     * @param string $table
     * @return NULL| and array of string containing the field names
     */
    public static function fieldList (string $table) {
		return Schema::fieldList($table);
	}

	/**
     * If a field is a foreign key returns information about it
     * else returns null
     * 
     * @param string $table
     * @param string $field
     * @return unknown|NULL
     */
    public static function foreignKey (string $table, string $field) {
		return Schema::foreignKey($table, $field);
	}

    /**
     * Returns the column referenced by a foreign key
     * 
     * @param string $table
     * @param string $field
     * @return NULL
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public static function foreignKeyReferencedColumn (string $table, string $field) {
		return Schema::foreignKeyReferencedColumn($table, $field);
    }
	
	/**
     * True when a table field element cannot be duplicated
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function unique(string $table, string $field) {
		return Schema::unique($table, $field);
	}

	    /**
     * returns the comment of a field
     * 
     * @param string $table
     * @param string $field
     * @return unknown
     */
    public static function columnComment (string $table, string $field) {
		return Schema::columnComment($table, $field);
	}

	/**
     * True when a field is unsigned
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function unsignedType (string $table, string $field) {
		return Schema::unsignedType($table, $field);
	}

	/**
     * Returns the type of on delete constraint
     * 
     * @param string $table
     * @param string $field
     */
    public static function onDeleteConstraint(string $table, string $field, string $type = "on_delete") {
		return Schema::onDeleteConstraint($table, $field, $type);
	}

	/**
     * Returns the type of on delete constraint
     *
     * @param string $table
     * @param string $field
     */
    public static function onUpdateConstraint(string $table, string $field) {
		return Schema::onUpdateConstraint($table, $field);
	}

	/**
     * True if this table is referenced in another table. In which case there
     * is a foreign key in the other table which points to this table.
     * 
     * @param string $target_table
     */
    public static function isReferenced(string $target_table) {
		return Schema::isReferenced($target_table);
	}

	/**
     * Check if a table is a view and returns the view definition if any
     * @param String $view
     * @return string|unknown
     */
    public static function isView(String $view) {
		return ViewSchema::isView($view);
	}

	/**
     * Analyze a view definition and returns a list of field definition
     * 
     * @param unknown $def
     * @return NULL|unknown[][]|mixed[][]
     */
    public static function ScanViewDefinition($def) {
		return ViewSchema::ScanViewDefinition($def);
	}
}