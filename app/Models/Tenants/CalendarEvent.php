<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use Exception;
use Carbon\Carbon;
use App\Helpers\Config;
use App;

/**
 * Calendar event model
 * 
 * It is the interface between the database format (standardized UTC MySql format)
 * and the user interface (localized attribute in the user timezone)
 * 
 * database attributes:
 * 		datetime('start');				// '2021-06-29 00:00:00'
 * 		datetime('end')->nullable();	// '2021-06-28 11:30:00'
 * 
 * UI attributes
 * 		start_date						string in local format
 * 		start_time						time in local format
 * 		end_date
 * 		end_time
 * 
 * When an EventCalendar is created or fetched from the database only the start and end attributes are set.
 * The start_date, start_time, end_date and end_time will be populated the first time that 
 * getStartDate, getStartTime, getEndDate, getEndTime are called.
 * 
 * Should these derived values be stored in database ? It depends on how often events are created, modified and read,
 * and of the relative cost to compute derived attributes compared to retrieve them.
 * 
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
    		'description',
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
     * In database date time are stored as composed UTC strings
     * 
     * $this->start:      string value in UTC "2021-07-14 12:30:00"
     * $this->start_date: cached string value for local date
     * $this->start_time: cached string for local time
     */
    protected function setStartDateAndTime() {
    	if (isset($this->start)) {
    		$date = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
    		$tz = Config::config('app.timezone');
    		$date->tz($tz);
    	
    		$this->start_date = $date->format(__('general.date_format'));
    		$this->start_time = $date->format(__('general.time_format'));
    	} else {
    		$this->start_date = '';
    		$this->start_time = '';
    	}
    }

    /**
     * 
     */
    protected function setEndDateAndTime() {
    	if (isset($this->end)) {
    		$date = Carbon::createFromFormat('Y-m-d H:i:s', $this->end);
    		$date->tz(Config::config('app.timezone'));
    	
    		$this->end_date = $date->format(__('general.date_format'));
    		$this->end_time = $date->format(__('general.time_format'));
    	} else {
    		$this->end_date = '';
    		$this->end_time = '';	
    	}
    }
    
    /**
     * StartDate getter
     * @throws Exception
     * @return string
     */
    public function getStartDate() {
    	if (!isset($this->start_date)) {
    		$this->setStartDateAndTime();
    	}
    	return $this->start_date;
    }
    
    /**
     * StartTime getter
     * @throws Exception
     * @return string
     */
    public function getStartTime() {
    	if (!isset($this->start_time)) {
    		$this->setStartDateAndTime();
    	}
    	return $this->start_time;
    }
    
    /**
     * EndDate getter
     * @throws Exception
     * @return string
     */
    public function getEndDate() {
    	if (!isset($this->end_date)) {
    		$this->setEndDateAndTime();
    	}
    	return $this->end_date;
    }
    
    /**
     * EndTime getter
     * @throws Exception
     * @return string
     */
    public function getEndTime() {
    	if (!isset($this->end_time)) {
    		$this->setEndDateAndTime();
    	}
    	return $this->end_time;
    }    
    
    /**
     * @return number
     */
    public function durationInSeconds() {
    	if (!isset($this->start) || !isset($this->end)) {
    		return 0;
    	}
    	$start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
    	$end = Carbon::createFromFormat('Y-m-d H:i:s', $this->end);
    	return $end->diffInSeconds($start);
    }
        
}
