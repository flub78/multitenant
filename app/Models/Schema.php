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
     * Collect information about a database table
     * 
     * @param string $table
     */
    public static function tableInformation ($table) {
    	try {
    		$select = DB::connection(SCHEMA)->select("SHOW COLUMNS FROM $table");
    		return $select;
    	} catch (QueryException $e) {
    		return null;
    	}
    }
    
    public static function columnInformation ($table, $field) {
    	$table_info = Schema::tableInformation($table);
    	
    	if (!$table_info) return null;
    	
    	foreach ($table_info as $col) {
    		if ($col->Field == $field) {
    			return $col;
    		}
    	}
    	return null;
    }
    
    public static function fieldList ($table) {
    	$table_info = Schema::tableInformation($table);
    	
    	if (!$table_info) return null;
    	
    	$res = [];
    	foreach ($table_info as $col) {
    		$res[] = $col->Field;
    	}
    	return $res;    	
    }
    
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
    
    public static function columnType ($table, $field) {
    	return Schema::columnInformation($table, $field)->Type;
    }
    
    public static function columnSize ($table, $field) {
    	$type = Schema::columnInformation($table, $field)->Type;
    	
    	$pattern = '/^.*?(\d+).*$/i';
    	if (preg_match($pattern, $type, $matches)) {
    		// var_dump($matches);
    		return $matches[1];
    	}
    	return 0;
    }
    
    public static function indexList ($table) {
    	$database = ENV('DB_SCHEMA', 'tenanttest');
    	return DB::connection(SCHEMA)->select("SHOW INDEX FROM $table FROM $database");
    }
}
