<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use Exception;

/**
 * Calendar event model
 * 
 * It is the interface between the database format (standardized UTC MySql format)
 * and the user interface (localized attribute in the user timezone)
 * 
 * database attributes:
 * 		datetime('start');	// '2021-06-29 00:00:00'
 * 		datetime('end')->nullable();	// '2021-06-28 11:30:00'
 * 
 * UI attributes
 * 		start_date
 * 		start_time
 * 		end_date
 * 		end_time
 * 
 * Maybe later:
 * -----------
 * It is possible to add a duration attribute with
 * 		on_change('duration') { end_time = start_time + duration }
 * 		on_change('end_time') { duration = end_time - start_time }
 * 		on_change('start_time') { end_time = start_time + duration }
 *
 * 		Note that, to be universal, duration of more than 24 hours should be accepted
 * 		and the formulas above completed to take start_date and end_date into account.
 * 
 * Implementation point to check:
 * I do not know if the setters and getters are always used by the Laravel internal layers
 * and if overwriting them is enough to guarantee a correct behavior...
 * 
 * Discussion on private attributes
 * https://stackoverflow.com/questions/40331167/how-do-i-make-a-property-private-in-a-laravel-eloquent-model/40331294
 * 
 * Laravel accessors
 * https://laravel.com/docs/8.x/eloquent-mutators
 * 
 * @author frederic
 *
 */
class CalendarEvent extends ModelWithLogs
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    
    protected $table = 'calendar_events';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'title',
    		'groupId',
    		'allDay',
    		'start', 'end',
    		'editable', 'startEditable', 'durationEditable',
    		'backgroundColor', 'borderColor', 'textColor'
    ];
    
    /**
     * The attributes that are guarded (not mass assignable)
     * Use either $fillable or $guarded, not both...
     */
    // protected $guarded = [];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    		'editable', 'startEditable', 'durationEditable'
    ];
    
    
    /**
     * Extract the date part from a DateTime
     * @throws Exception
     * @return string
     */
    protected function extractDate($date) {
    	if ($date == "") return "";
    	
    	$date_regexp = '/((\d{4})\-(\d{2})\-(\d{2}))/';
    	    	
    	if (preg_match($date_regexp, $date, $matches)) {
    		$year = $matches[2];
    		$month = $matches[3];
    		$day = $matches[4];
    		return "$day/$month/$year";
    	} else {
    		throw new Exception("Incorrect date format: $date, expected YYY-MM-DD");
    	}
    }

    /**
     * Extract the time part from a DateTime
     * @throws Exception
     * @return string
     */
    protected function extractTime($date) {
    	if ($date == "") return "";
    	
    	$time_regexp = '/((\d{2})\:(\d{2})\:(\d{2}))/';
    	
    	if (preg_match($time_regexp, $date, $matches)) {
    		$hour = $matches[2];
    		$minute = $matches[3];
    		$second = $matches[4];
    		return "$hour:$minute";
    	} else {
    		throw new Exception("Incorrect date format: $date, expected YYY-MM-DD");
    	}
    }
    
    /**
     * StartDate getter
     * @throws Exception
     * @return string
     */
    public function getStartDate() {
    	return $this->extractDate($this->start);    	
    }
    
    /**
     * StartTime getter
     * @throws Exception
     * @return string
     */
    public function getStartTime() {
    	return $this->extractTime($this->start); 
    }
    
    /**
     * EndDate getter
     * @throws Exception
     * @return string
     */
    public function getEndDate() {
    	return $this->extractDate($this->end);
    }
    
    /**
     * EndTime getter
     * @throws Exception
     * @return string
     */
    public function getEndTime() {
    	return $this->extractTime($this->end);
    }
    
    public function setStartAttribute($value)
    {
    	//echo "setStartAttribute($value)\n"; exit;
    	$this->attributes['start'] = $value;
    }
}
