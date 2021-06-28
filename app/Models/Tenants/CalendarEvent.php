<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;

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
    		'start', 'end', 'editable', 'startEditable', 'durationEditable',
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
     * Check it two CalendarEvent are equals (mainly for testing)
     * @param CalendarEvent $x
     * @return boolean
     */
    public function equals(CalendarEvent $x) {
    	foreach ($this->fillable as $attr) {
    		if ($this->$attr != $x->$attr) {
    			echo "$attr : " . ($this->$attr) . " != " . ($x->$attr);
    			return false;
    		}
    	}
    	return true;
    }
    
}
