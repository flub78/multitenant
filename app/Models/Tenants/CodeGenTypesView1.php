<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use App\Helpers\Config;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use App\Models\Tenants\CodeGenType;

/**
 * CodeGenTypesView1 model
 *
 * Acces to the percistency layer
 * code_gen_types_view1 is a MySQL view
 *
 * @author fred
 *
 */
class CodeGenTypesView1 extends ModelWithLogs {


    /**
     * The associated database table
     */
    protected $table = 'code_gen_types_view1';
 
    /**
     * Views usually have no regular factories. The generation of test data
     * usually implies to generate data in others tables.
     * 
     * @param int $number
     * @param array $argv arguments to pass to referenced factories
     */
    public static function factoryCreate(int $number = 1, $argv = []) {
        $cnt = self::count();
        for ($i = 0; $i < $number; $i++) {
        	CodeGenType::factory()->create($argv);
        }
        return $cnt;
    }
}