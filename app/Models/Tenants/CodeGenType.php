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
use Carbon\Exceptions\InvalidFormatException;


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
	protected $fillable = ["name", "phone", "description", "year_of_birth", 
			"weight", "birthday", "tea_time", 
			"takeoff_date", "takeoff_time", 
			"price", "big_price", "qualifications", "picture", "attachment"];

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
    	if (! $value) return $value;
        $db_format = 'Y-m-d H:i:s';
        try {
        	$datetime = Carbon::createFromFormat($db_format, $value);
        } catch (InvalidFormatException $e) {
        	echo "Carbon Exception: " . $e->getMessage();
        	echo "\$value = \"$value\"\n";
        	exit;
        }
        $datetime->tz(Config::config('app.timezone'));
        $result = $datetime->format(__('general.datetime_format'));
        // echo "\ngetTakeoffAttribute($value) => $result";
        return $result;
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
        // echo "\nsetTakeoffAttribute($value) => " . $this->attributes['takeoff'];
    }
    
    /**
     * Get the Takeoff date
     * 
     * @param unknown $value
     * @return string
     */
    public function getTakeoffDateAttribute($value) {
    	$result = substr($this->takeoff, 0, 10);
    	// echo "\ngetTakeoffDateAttribute($value) = $result";
    	return $result;
    }

    /**
     * Get the Takeoff time
     * 
     * @param unknown $value
     * @return string
     */
    public function getTakeoffTimeAttribute($value) {
    	$result = substr($this->takeoff, 11, 5);
    	// echo "\ngetTakeoffTimeAttribute($value) => $result";
    	return $result;
    }
    
    /**
     *  Set the Takeoff date
	 *
     * @param String $value localized date
     * @out_assertion the takeoff attribute in MySql format has been modified
     */
    public function setTakeoffDateAttribute(String $value) {
    	$local_datetime = $value . " " . $this->takeoff_time;
    	$this->setTakeoffAttribute($local_datetime);
    	// echo "\nsetTakeoffDateAttribute(String $value) => setTakeoffAttribute($local_datetime)";
    }
    
    /**
     * Set the Takeoff time
     * 
     * @param String $value
     */
    public function setTakeoffTimeAttribute(String $value) {
    	$local_datetime = $this->takeoff_date . " " . $value;
    	$this->setTakeoffAttribute($local_datetime);
    	// echo "\nsetTakeoffTimeAttribute(String $value) => setTakeoffAttribute($local_datetime)";
    }
    
}