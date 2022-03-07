<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use App\Helpers\Config;
use Carbon\Carbon;


/**
 * CodeGenType model
 *
 * Acces to the percistency layer
 *
 * @author fred
 *
 */
class CodeGenType extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'code_gen_types';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ["name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff_date", "takeoff_time", "price", "big_price", "qualifications", "picture", "attachment"];

	/**
	 * Get the Birthday date
	 *
	 * @param  string  $value date in MySql format
	 * @return string the date in local format
	 */
	public function getBirthdayAttribute($value) {
        $db_format = 'Y-m-d';
        $date = Carbon::createFromFormat($db_format, $value);
		$date->tz(Config::config('app.timezone'));
		return $date->format(__('general.date_format'));
	}
	
	/**
	 * Set the Birthday date
	 *
	 * @param  string  $value date in local format
	 */
	public function setBirthdayAttribute($value) {
        $db_format = 'Y-m-d';
		$date = Carbon::createFromFormat(__('general.date_format'), $value);
        $this->attributes['birthday'] = $date->format($db_format);
	}
	
    /**
     * Get the Takeoff datetime
     *
     * @param  string  $value datetime in MySql format
     * @return string the datetime in local format
     */
    public function getTakeoffAttribute($value) {
        $db_format = 'Y-m-d H:i:s';
        $datetime = Carbon::createFromFormat($db_format, $value);
        $datetime->tz(Config::config('app.timezone'));
        return $datetime->format(__('general.datetime_format'));
    }
    
    /**
     * Set the Takeoff datetime
     *
     * @param  string  $value datetime in local format
     */
    public function setTakeoffAttribute($value) {
        $db_format = 'Y-m-d H:i:s';
        $datetime = Carbon::createFromFormat(__('general.datetime_format'), $value);
        $this->attributes['takeoff'] = $datetime->format($db_format);
    }
    
    /**
     * Get the Takeoff date
     * 
     * @param unknown $value
     * @return string
     */
    public function getTakeoffDateAttribute($value) {
    	return substr($this->takeoff, 0, 10);
    }

    /**
     * Get the Takeoff time
     * 
     * @param unknown $value
     * @return string
     */
    public function getTakeoffTimeAttribute($value) {
    	return substr($this->takeoff, 11, 5);
    }
    
    /**
     *  Set the Takeoff date
     *  
     * @param unknown $value
     */
    public function setTakeoffDateAttribute($value) {
    	$this->takeoff =  $value . " " . $this->takeoff_time;
    }
    
    /**
     * Set the Takeoff time
     * 
     * @param unknown $value
     */
    public function setTakeoffTimeAttribute($value) {
    	$this->takeoff = $this->takeoff_date. " " . $value;
    }
    
}