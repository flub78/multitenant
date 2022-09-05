<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use Exception;
use Carbon\Carbon;
use App\Helpers\Config;
use App;
use Illuminate\Support\Facades\Log;


/**
 * Calendar event model
 * 
 * The initial version was using separate fields for date and time. 
 * 
 * The current version maps HTML datetime-local inputs and MySql datetime, 
 * so there is much less code. As it is more efficient to maintain a few conversion functions in the DateFormat helper
 * that to have conversion code in the models, this model should become really lean.
 * 
 * Discussion on private attributes
 * https://stackoverflow.com/questions/40331167/how-do-i-make-a-property-private-in-a-laravel-eloquent-model/40331294
 * 
 * Laravel accessors
 * https://laravel.com/docs/8.x/eloquent-mutators
 * 
 * @author frederic
 * reviewed 2022-01-08
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
     * return a local start date time in format "2022-01-30" or "2022-01-24 09:15:00"
     */
    public function getFullcalendarStart() {
    	
    	if (strlen($this->start) > 16) {
    		$date = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
    	} else {
    		$date = Carbon::createFromFormat('Y-m-d', $this->start);
    	}
    	$date->tz(Config::config('app.timezone'));
    	
    	if ($this->allDay) {
    		return $date->format('Y-m-d');
    	} else {
    		return $date->format('Y-m-d H:i:s');
    	}
    }

    /**
     * return a local end date time in format "2022-01-30" or "2022-01-24 09:15:00"
     */
    public function getFullcalendarEnd() {
    	if (! $this->end) return "";
    	Log::debug('getFullcalendarEnd ' . $this->end);
    	if (strlen($this->end) > 16) {
    		$date = Carbon::createFromFormat('Y-m-d H:i:s', $this->end);
    	} else {
    		$date = Carbon::createFromFormat('Y-m-d', $this->end);
    	}
    	$date->tz(Config::config('app.timezone'));
    	
    	if ($this->allDay) {
    		return $date->format('Y-m-d');
    	} else {
    		return $date->format('Y-m-d H:i:s');
    	}
    }
    
}
