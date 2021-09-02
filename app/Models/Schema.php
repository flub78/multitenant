<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Models\ModelWithLogs;


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
     * The attributes that are mass assignable.
     *
     * admin can be mass assignable allowing admin users to create others admin users
     * However it is important to protect all URLs that modify users with the admin middleware.
     * Forgetting to do that could allow users to gain unauthorizes access.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
        

    /**
     * Collect information about a database table
     * 
     * @param string $table
     */
    public static function tableInformation ($table) {
    	$select = DB::connection(SCHEMA)->select("SHOW COLUMNS FROM $table");
    	return $select;
    }
    
    public static function columnInformation ($table, $field) {
    	$table_info = Schema::tableInformation($table);
    	
    	foreach ($table_info as $col) {
    		if ($col->Field == $field) {
    			return $col;
    		}
    	}
    	return null;
    }
    
    public static function fieldList ($table) {
    	$table_info = Schema::tableInformation($table);
    	
    	$res = [];
    	foreach ($table_info as $col) {
    		$res[] = $col->Field;
    	}
    	return $res;    	
    }
    
    
}
