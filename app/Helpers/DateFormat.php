<?php
namespace App\Helpers;

use Exception;

/**
 * Helper to handle dates in the current context
 * 
 * @author frederic
 *
 */
class DateFormat {
	
    /**
     * Translate a date from database format  into localized format
     *
     * @param  string $date yyyy-mm-dd
     * @return string localized date
     */
	static public function db_to_date($db_date) 
    {
        $date = date_create($db_date);
        return date_format($date, __('general.date_format'));
    }


    /**
     * Translate a localized date from into a MySql storage format
     *
     * @param  string $date localized date
     * @return string yyyy-mm-dd
     */
    static public function date_to_db($localized_date) 
    {
        $date = date_create_from_format(__('general.date_format'), $localized_date);
        if (!$date) {
        	throw new Exception("incorrect date $localized_date");
        }
        return date_format($date, "Y-m-d");
    }

}