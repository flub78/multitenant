<?php

namespace App\Helpers;

use Exception;
use Carbon\Carbon;
use App\Helpers\Config;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Helper to handle dates in the current context
 *
 * Initially they were converting between localized strings in the local timezone to UTC
 * database format.
 * 
 * However by using the date time in HTML 5 input, the conversion to the database format is
 * automatic. The 
 * 
 * @author frederic
 *        
 */
class DateFormat {

	/**
	 * Translate a localized date from into a MySql storage format
	 *
	 * @param string $date
	 *        	localized date
	 * @return string yyyy-mm-dd
     *
	 */
	static public function date_to_db($date) {
		$db_date = Carbon::createFromFormat ( __ ( 'general.date_format' ), $date );
		return $db_date->format ( "Y-m-d" );
	}

	/**
	 * Build an UTC datetime from a local date and a time
	 *
	 * @param string $date
	 *        	localized date
	 * @param
	 *        	optional tz timezone
	 * @return string yyyy-mm-dd
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function datetime_to_db($local_datetime, $tz = "") {
		if (!$local_datetime) return $local_datetime;
				
		if (! $tz) {
			$tz = Config::config ( 'app.timezone' );
		}
		// echo "tz = $tz\n"; 
		$local_datetime = str_replace('T', ' ', $local_datetime);
		
		// $date = Carbon::createFromFormat ( __ ( 'general.datetime_format' ), $local_datetime, $tz );
		$date = Carbon::createFromFormat ("Y-m-d H:i" , $local_datetime, $tz);
		$date->timezone ( "UTC" );
		return $date->format ( "Y-m-d H:i:s" );
	}
	
	/**
	 * Translate a datetime into local format.
	 * Just a format change, or does it manage timezone ?
	 * @param String $date
	 * @param Boolean $date_only
	 */
	static public function local_datetime($db_datetime, bool $date_only = false) {
	    if (!$db_datetime) return "";
	    
	    try  {
	       $tz = Config::config ('app.timezone');
	       $date = Carbon::createFromFormat ("Y-m-d H:i:s" , $db_datetime, "UTC");
	       $date->timezone ($tz);
	    
	       if ($date_only) {
	           return $date->format (__ ( 'general.date_format' ));
	       } else {
	           return $date->format (__ ( 'general.local_datetime_format' ));
	       }
	    } catch (Exception $e) {
	       return "Incorrect datetime"; 
	    }
	}
	
	/**
	 * transform a datetime into a local value that can be used as value for an HTML
	 * input datetime-local
	 */
	static public function to_local ($db_datetime) {
	    if (!$db_datetime) return "";
	    
	    try  {
	        $tz = Config::config ('app.timezone');
	        $date = Carbon::createFromFormat ("Y-m-d H:i:s" , $db_datetime, "UTC");
	        $date->timezone ($tz);

	        return $date->format (__ ( 'general.datetime_format' ));
	        
	    } catch (Exception $e) {
	        return "Incorrect datetime";
	    }
	}

}