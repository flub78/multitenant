<?php

/**
 * This is a special model to get information about the tenant schema
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use Illuminate\Support\Facades\DB;



/**
 * Schema model
 *
 * Acces to the percistency layer
 *
 * @author fred
 *
 */
class TenantSchema extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'Schemas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */

    /**
     * Returns a list of string containing the list of database tables
     * 
     * @return NULL[]|array
     */
    public static function tableList() {
        try {
            $select = DB::connection()->select("SHOW TABLES");

            $res = [];
            $attr = "Tables_in_" . DB::connection()->getDatabaseName();
            foreach ($select as $obj) {
                $res[] = $obj->$attr;
            }
            // remove metadata, migrations, password_resets from the list
            $res = array_filter($res, function ($table) {
                return !in_array($table, ['metadata', 'migrations', 'password_resets']);
            });
            return $res;
        } catch (QueryException $e) {
            return [];
        }
    }

    /**
     * Returns a list of string containing the list of database tables
     * 
     * @return NULL[]|array
     */
    public static function columnList() {
        try {
            $table = TenantSchema::tableList();
            $res = [];
            foreach ($table as $tableName) {
                $columns = DB::connection()->select("SHOW COLUMNS FROM " . $tableName);
                $res[$tableName] = array_map(function ($column) {
                    return $column->Field;
                }, $columns);
            }

            return $res;
        } catch (QueryException $e) {
            return [];
        }
    }
}
