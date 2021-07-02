<?php
namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use App\Helpers\Config;

/**
 * Helper to handle dates in the current context
 * 
 * 
 * @author frederic
 *
 */
class DateFormat {
	
	/**
	 * Check if db_date is a correct UTC date
	 * @param String $db_date
	 * @return boolean
	 */
	static function db_date_has_date($db_date) {
		$date_regexp = '/((\d{4})\-(\d{2})\-(\d{2}))/';
		if (preg_match($date_regexp, $db_date, $matches))
			return true;
		else
			return false;
	}

	/**
	 * Check if db_date is a Date or a DateTime
	 * @param String $db_date
	 * @return boolean
	 */
	static function db_date_has_time($db_date) {
		$time_regexp = '/((\d{2})\:(\d{2})\:(\d{2}))/';
		
		if (preg_match($time_regexp, $db_date, $matches))
			return true;
			else
				return false;
	}
	
    /**
     * Translate a date from database format into local format
     *
     * @param  string $date yyyy-mm-dd
     * @param optional timezone
     * @return string localized date
     */
	static public function db_to_date($db_date, $tz = "") 
    {
    	if (!$tz) {
    		$tz = Config::config('app.timezone');
    	}
        $date = Carbon::create($db_date);        
        $date->timezone($tz);
         return $date->format(__('general.date_format'));
    }


    /**
     * Translate a localized date from into a MySql storage format
     *
     * @param  string $date localized date
     * @param optional timezone
     * @return string yyyy-mm-dd
     */
    static public function date_to_db($local_date, $tz = "") 
    {
    	if (!$tz) {
    		$tz = Config::config('app.timezone');
    	}
    	$date = date_create_from_format(__('general.date_format'), $local_date);
        $date = Carbon::createFromFormat(__('general.date_format'), $local_date, $tz);
        if (!$date) {
        	throw new Exception("incorrect date $local_date");
        }
        return $date->format("Y-m-d");
    }

}