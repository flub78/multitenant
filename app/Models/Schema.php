<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\ModelWithLogs;
use Illuminate\Database\QueryException;

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
     * Returns a list of string containing the list of database table
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
     * @param string $table
     * @return boolean
     */
    public static function tableExists(string $table) {
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
    		$select = DB::connection(SCHEMA)->select("SHOW COLUMNS FROM $table");
    		return $select;
    	} catch (QueryException $e) {
    		return null;
    	}
    }
    
    /**
     * Returns an object containing column information
     *    Attributes are Field, Type, Null, Key, Default and Extra
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
     * Returns the list of fields of a table
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
     * @param string $table
     * @param string $field
     * @return string
     */
    public static function columnType (string $table, string $field) {
    	return Schema::columnInformation($table, $field)->Type;
    }
    
    /**
     * @param string $table
     * @param string $field
     * @return unknown|string
     */
    public static function basicType (string $table, string $field) {
    	$type = self::columnType($table, $field);
    	$pattern = '/^([\w-]+).*$/i';
    	if (preg_match($pattern, $type, $matches)) {
    		// var_dump($matches);
    		return $matches[1];
    	}
    	return "";
    }
    
    /**
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
     * @param string $table
     * @param string $field
     * @return 0|number
     */
    public static function columnSize (string $table, string $field) {
    	$type = Schema::columnInformation($table, $field)->Type;
    	
    	$pattern = '/^.*?(\d+).*$/i';
    	if (preg_match($pattern, $type, $matches)) {
    		// var_dump($matches);
    		return $matches[1];
    	}
    	return 0;
    }
    
    /**
     * Returns the list of indexes of a table
     * @param string $table
     * @return array
     */
    public static function indexList (string $table) {
    	$database = ENV('DB_SCHEMA', 'tenanttest');
    	return DB::connection(SCHEMA)->select("SHOW INDEX FROM $table FROM $database");
    }
}
