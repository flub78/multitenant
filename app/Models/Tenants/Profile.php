<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use App\Helpers\Config;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use App\Models\User;


/**
 * Profile model
 *
 * Acces to the percistency layer
 * profiles is a regular table not a MySQL view
 *
 * @author fred
 *
 */
class Profile extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'profiles';
 
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
	protected $fillable = ["first_name", "last_name", "birthday", "user_id"];

	/**
	 * Get the image of the referenced user
	 *
	 * @return String
	 */
	public function user_image() {
		$user = User::findOrFail ( $this->user_id );
		return $user->image();
	}
	
	/**
     * Get the Birthday date
     *
     * @param  string  $value date in MySql format
     * @return string the date in local format
     */
    public function getBirthdayAttribute($value) {
        if (!$value) return $value;
        $db_format = 'Y-m-d';
        try {
            $date = Carbon::createFromFormat($db_format, $value);
        } catch (InvalidFormatException $e) {
            echo "getBirthdayAttribute($value) " . $e->getMessage();
            exit;
        }
        $date->tz(Config::config('app.timezone'));
        $result = $date->format(__('general.date_format'));
        // echo "\ngetBirthdayAttribute(String $value) => $result";
        return $result;
    }
    
    /**
     * Set the Birthday date
     *
     * @param  string  $value date in local format
     */
    public function setBirthdayAttribute($value) {
        if (!$value) return $value;
        $db_format = 'Y-m-d';
        $date = Carbon::createFromFormat(__('general.date_format'), $value);
        $this->attributes['birthday'] = $date->format($db_format);
    }

    /**
     * Get the user associated with the profile.
     */
    public function user() {
    	return $this->belongsTo(User::class);
    }
}