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
	static public function datetime_to_db($local_date, $time = "", $tz = "") {
		if (!$local_date) return $local_date;
		
		// $time = 9:05
		if (! $time) {
			$time = '00:00';
		}
		if (! $tz) {
			$tz = Config::config ( 'app.timezone' );
		}
		$local_datetime = $local_date . ' ' . $time;
		$date = Carbon::createFromFormat ( __ ( 'general.datetime_format' ), $local_datetime, $tz );
		$date->timezone ( "UTC" );
		return $date->format ( "Y-m-d H:i" );
	}

}