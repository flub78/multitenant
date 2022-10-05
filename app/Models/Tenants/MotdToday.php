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


/**
 * MotdToday model
 *
 * Acces to the percistency layer
 * motd_todays is a MySQL view
 *
 * @author fred
 *
 */
class MotdToday extends ModelWithLogs {


    /**
     * The associated database table
     */
    protected $table = 'motd_todays';
 
    /**
     * Views usually have no regular factories. The generation of test data
     * usually implies to generate data in others tables.
     * 
     * @param array $argv arguments to pass to referenced factories
     */
    public static function factoryCreate($argv = []) {
        $cnt = self::count();
        // Generating one element
        // [[class_name]]::factory()->create($argv);
        return $cnt;
    }
}