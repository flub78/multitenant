<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model for tenant and central application
 * 
 * @author frederic
 * @review 02/09/2021
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * admin can be mass assignable allowing admin users to create others admin users
     * However it is important to protect all URLs that modify users with the admin middleware.
     * Forgetting to do that could allow users to gain unauthorizes access.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    	'password', 
    	'admin',  
    	'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * @return boolean
     */
    public function isAdmin() {
    	return $this->admin;
    }
    
    /**
     * @return boolean
     */
    public function isActive() {
    	return $this->active;
    }
    
    /**
     * Check it two users are equals (mainly for testing)
     * @param User $x
     * @return boolean
     */
    public function equals(User $x) {
    	foreach ($this->fillable as $attr) {
    		if ($this->$attr != $x->$attr) {
    			// echo "$attr : " . ($this->$attr) . " != " . ($x->$attr);
    			return false;
    		}
    	}
    	return true;
    }
    
    /**
     * full_name attribute
     * As names are unique, just create aliases
     * @return string
     */
    public function getFullNameAttribute() {
    	return $this->name;
    }

    
    /**
     * short_name attribute
     * @return string
     */
    public function getShortNameAttribute() {
    	return $this->name;
    }
    
    /**
     * Return a list of name and id to be used to generate HTML selectors
     * @param array $where
     * @return a list of ['name' => xxx, 'id' => yyy]
     */
    public static function selector($where = []) {
    	$users = User::where($where)->get();
    	$res = [];
    	foreach ($users as $user) {
    		$res[] = ['name' => $user->full_name, 'id' => $user->id];
    	}
    	return $res;
    }
}
