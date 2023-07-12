<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\ModelWithLogs;
use Illuminate\Database\QueryException;
use Exception;

const SCHEMA = "schema";


/**
 * This special Model is used to get information on the database schema.
 * It uses the 'schema' connection.
 * 
 * @author frederic
 *
 */
class Schema extends ModelWithLogs {
	
    /**
     * Returns a list of string containing the list of database tables
     * 
     * @return NULL[]|array
     */
    public static function tableList() {
    	try {
    		$select = DB::connection(SCHEMA)->select("SHOW TABLES");
    		
    		$res = [];
    		$attr = "Tables_in_" . ENV('DB_SCHEMA', 'tenanttest');
    		foreach ($select as $obj) {
    			$res[] = $obj->$attr;
    		}
    		
    		return $res;
    	} catch (QueryException $e) {
    		return [];
    	}
    }
    
    /**
     * Return true when a table exists in the database
     * 
     * @param string $table
     * @return bool
     */
    public static function tableExists(string $table) : bool{
    	return in_array($table, Schema::tableList());
    }
    
    /**
     * Collect information about a database table
     * 
     * @param string $table
     * @return an array of object information, one entry for every field
     */
    public static function tableInformation ($table) {
    	try {
    		$select = DB::connection(SCHEMA)->select("SHOW FULL COLUMNS FROM $table");
    		return $select;
    	} catch (QueryException $e) {
    		return null;
    	}
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
    	$table_info = Schema::tableInformation($table);
    	
    	if (!$table_info) return null;
    	
    	foreach ($table_info as $col) {
    		if ($col->Field == $field) {
    			return $col;
    		}
    	}
    	return null;
    }
    
    /**
     * Returns true when a column is required in a database table
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function required (string $table, string $field) {
    	$col_info = self::columnInformation($table, $field);
    	
    	if ($col_info) {
    		return ($col_info->Null != "YES");
    	} else {
    		return false;
    	}
    }

    /**
     * True when a table field element cannot be duplicated
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function unique(string $table, string $field) {
    	// $col_info = self::columnInformation($table, $field);
    	// $fk = self::foreignKey($table, $field);
    	
    	$info = self::indexInfo($table, $field);
    	
    	if (!$info) return false;
    	
    	// Todo check unique. Foreign keys are BTREE ... 
    	if ($info->Index_type == "BTREE") 
    		return true;
    	else 
    		return false;
    }
    
    /**
     * Returns the list of fields of a table
     * 
     * @param string $table
     * @return NULL| and array of string containing the field names
     */
    public static function fieldList (string $table) {
    	$table_info = Schema::tableInformation($table);
    	
    	if (!$table_info) return null;
    	
    	$res = [];
    	foreach ($table_info as $col) {
    		$res[] = $col->Field;
    	}
    	return $res;    	
    }
    
    /**
     * Returns true if a field exists in a table
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function fieldExists (string $table, string $field) {
    	$field_list = Schema::fieldList($table);
    	if (!$field_list) return false;
    	return in_array($field, $field_list);
    }
    
    /**
     * returns a list of string with the existing types used in the database
     * 
     * @return string []
     */
    public static function existingTypes () {
    	$res = [];
    	$tables = Schema::tableList();
    	foreach ($tables as $table) {
    		$fields = Schema::fieldList($table);
    		foreach ($fields as $field) {
    			$info = Schema::columnInformation($table, $field);
    			$type = $info->Type;
    			if (!in_array($type, $res)) $res[] = $type;
    		}
    	}
    	return $res;
    }
    
    /**
     * returns the database type of a field
     * 
     * @param string $table
     * @param string $field
     * @return string
     */
    public static function columnType (string $table, string $field) {
    	$info = Schema::columnInformation($table, $field);
    	if (! $info) return "";
    	return $info->Type;
    }
    
    /**
     * returns the comment of a field
     * 
     * @param string $table
     * @param string $field
     * @return unknown
     */
    public static function columnComment (string $table, string $field) {
    	$info = Schema::columnInformation($table, $field);
    	if ($info) return $info->Comment;
    	return "";
    }
    
    /**
     * returns a json array from a field comment
     *
     * @param string $table
     * @param string $field
     * @return unknown
     */
    public static function columnMetadata (string $table, string $field) {
    	$comment = Schema::columnComment($table, $field);
    	return json_decode($comment, true);
    }
    
    
    /**
     * Returns the basic type of a field
     * 
     * @param string $table
     * @param string $field
     * @return unknown|string
     */
    public static function basicType (string $table, string $field) {
    	$type = self::columnType($table, $field);
    	$pattern = '/^([\w-]+).*$/i';
    	if (preg_match($pattern, $type, $matches)) {
    		return $matches[1];
    	}
    	return "";
    }
    
    /**
     * True when a field is an integer
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function integerType (string $table, string $field) {
    	$type = self::columnType($table, $field);
    	$pattern = '/int/i';
    	if (preg_match($pattern, $type, $matches)) {
    		return true;
    	}
    	return false;
    }
    
    /**
     * True when a field is unsigned
     * 
     * @param string $table
     * @param string $field
     * @return boolean
     */
    public static function unsignedType (string $table, string $field) {
    	$type = self::columnType($table, $field);
    	$pattern = '/int/i';
    	if (preg_match($pattern, $type, $matches)) {
    		$pattern = '/unsigned/i';
    		if (preg_match($pattern, $type, $matches)) {
    			return true;
    		}
    		return false;
    	}
    	return false;
    }
    
    
    /**
     * Returns the size of a field or 0 when undefined
     * 
     * @param string $table
     * @param string $field
     * @return 0|number
     */
    public static function columnSize (string $table, string $field) {
    	$info = Schema::columnInformation($table, $field);
    	if (! $info) return 0;
    	$type = $info->Type;
    	
    	$pattern = '/^.*?(\d+).*$/i';
    	if (preg_match($pattern, $type, $matches)) {
    		return $matches[1];
    	}
    	
    	// column size is not included in the data type for bigint (some new MySql versions)
    	// It must be extracted from others fields
    	
    	return 0;
    }
    
    /**
     * Returns the list of indexes of a table
     * 
     * @param string $table
     * @return array
     */
    public static function indexList (string $table) {
    	$database = ENV('DB_SCHEMA', 'tenanttest');
    	try {
    		return DB::connection(SCHEMA)->select("SHOW INDEX FROM $table FROM $database");
    	} catch (QueryException $e) {
    		return null;
    	}
    }

    /**
     * Returns information about one index
     * 
     * @param string $table
     * @return array
     */
    public static function indexInfo (string $table, string $field) {
    	$indexes = self::indexList($table);
    	
    	if (! $indexes) return null;
    	
    	foreach ($indexes as $i) {
    		if ($i->Column_name == $field) return $i;
    	}
    	return null;
    }
    
    /**
     * Returns the primary index of a table
     * 
     * @param String $table
     * @return unknown|string
     */
    public static function primaryIndex(String $table) {
    	$indexes = self::indexList($table);
    	if (! $indexes) return "";
    	foreach ($indexes as $i) {
    		if ($i->Key_name ==  "PRIMARY") return $i->Column_name;
    	}
    	return "";
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
    	$database = ENV('DB_SCHEMA', 'tenanttest');
    	$sql = "SELECT CONSTRAINT_SCHEMA, CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_SCHEMA,REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
				from INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
				WHERE CONSTRAINT_SCHEMA = '$database' 
				and TABLE_NAME = '$table' and COLUMN_NAME = '$field' ";
    	$res = DB::connection(SCHEMA)->select($sql);
    	foreach ($res as $row) {
    		if ($row->REFERENCED_TABLE_NAME != "") return $row;
    	}
    	return null;
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
    	$fk = self::foreignKey($table, $field);
    	return ($fk) ? $fk->REFERENCED_TABLE_NAME : null;
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
    	$fk = self::foreignKey($table, $field);
    	return ($fk) ? $fk->REFERENCED_COLUMN_NAME : null;
    }
    
    /**
     * Returns the type of on delete constraint
     * 
     * @param string $table
     * @param string $field
     */
    public static function onDeleteConstraint(string $table, string $field, string $type = "on_delete") {
    	$fk = self::foreignKey($table, $field);
    	
    	if (!$fk) return "";		// check if it's a foreign key
    	
    	$referenced_table = $fk->REFERENCED_TABLE_NAME;
    	    	
    	$database = ENV('DB_SCHEMA', 'tenanttest');
    	// Not a really elegant select but attempts to do better have failed
     	$sql = "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS; ";
    	$res = DB::connection(SCHEMA)->select($sql);
    	foreach ($res as $row) {
    		if ($row->TABLE_NAME != $table) continue;
    		if ($row->CONSTRAINT_SCHEMA != $database) continue;
    		if ($row->REFERENCED_TABLE_NAME != $referenced_table) continue;
    		if ($type == "on_delete") 
    			return strtolower($row->DELETE_RULE);
    		else
    			return strtolower($row->UPDATE_RULE);
    	}
    	return "";
    }

    /**
     * Returns the type of on delete constraint
     *
     * @param string $table
     * @param string $field
     */
    public static function onUpdateConstraint(string $table, string $field) {
    	return self::onDeleteConstraint($table, $field, "on_update");
    }
    
    /**
     * True if this table is referenced in another table. In which case there
     * is a foreign key in the other table which points to this table.
     * 
     * @param string $target_table
     */
    public static function isReferenced(string $target_table) {
    	$tables = self::tableList();
    	foreach ($tables as $table) {
    		$fields = self::fieldList($table);
    		foreach ($fields as $field) {
    			$rt = self::foreignKeyReferencedTable($table, $field);
    			if ($rt == $target_table) {
    				return true;	
    			}
    		}
    	}
    	return false;
    }
    
 }
