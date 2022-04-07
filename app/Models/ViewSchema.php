<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\ModelWithLogs;
use Illuminate\Database\QueryException;
use Exception;

const VIEW_SCHEMA = "schema";


/**
 * This special Model is used to get information on the database schema.
 * It uses the 'schema' connection. It returns information on MySql views.
 * 
 * @author frederic
 *
 */
class ViewSchema extends ModelWithLogs {
	    
    /**
     * Check if a table is a view and returns the view definition if any
     * @param String $view
     * @return string|unknown
     */
    public static function isView(String $view) {    		
    	
    	$dbs = ENV('DB_SCHEMA', 'tenanttest');
    	$dbs = 'tenantabbeville';					// TODO: remove once the migration is defined, it's for testing
    	
    	$sql = "SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = '$dbs' AND TABLE_NAME = '$view';";
    	
    	try {
    		$select = DB::connection(VIEW_SCHEMA)->select($sql);
    	} catch (QueryException $e) {
    		// $view not found
    		return "";
    	}
    	
    	if (count($select)) return $select[0]->VIEW_DEFINITION;
    	
    	return "";
    }
    
    /**
     * Analyze a view definition and returns a list of field definition
     * 
     * @param unknown $def
     * @return NULL|unknown[][]|mixed[][]
     */
    public static function ScanViewDefinition($def) {
    	// Extract select, from and where part
    	$def_pattern = '/(.*)select(.*)from(.*)where(.*)/i';
    	if (preg_match($def_pattern, $def, $matches)) {
    		$select = $matches[2];
    		$from = $matches[3];
    		$where = $matches[4];
    	} else {
    		return null;
    	}
    	
    	// Looks for filed definition in the select part
    	$fields = [];
    	
    	// Field definition is a comma separated list
    	$field_defs = explode(',', $select);
    	foreach ($field_defs as $field_def) {
    		
    		// Each element 
    		$str = str_replace('`', '', $field_def);
    		$pattern = '/(\s*)(.*)(\s*AS\s*)(.*)/i';
    		if (preg_match($pattern, $str, $matches)) {
    			$name = $matches[4];
    			$def = $matches[2];
    			
    			$exploded = explode('.', $def);
    			$database = $exploded[0];
    			$table = $exploded[1];
    			$field = $exploded[2];
     			$fields[] = ['name' => $name, 'database' => $database, 'table' => $table, 'field' => $field];
    		}
    	}
    	return $fields;
    }
    
    /**
     * Returns the list of fields of a view from the view definition
     *
     * @param string $view
     * @return NULL| and array of string containing the field names
     */
    public static function fieldList (string $def) {
    	return ["name", "description", "tea_time"];
    }
}
