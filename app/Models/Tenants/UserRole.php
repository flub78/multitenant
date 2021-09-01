<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tenants\Role;


class UserRole extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'user_id',
    		'role_id',
    ];
        
    /**
     * full_name attribute
     * 
     * @return string
     */
    public function getFullNameAttribute() {
    	return __('user_roles.fullname', ['role' => $this->role_name, 'user' => $this->user_name]);
    }
    
    /**
     * short_name attribute
     * @return string
     */
    public function getShortNameAttribute() {
    	return $this->getFullNameAttribute();
    }
    
    /**
     * user_name attribute
     *
     * @return string
     */
    public function getUserNameAttribute() {
    	$user = User::findOrFail ( $this->user_id );    	
    	return $user->full_name;
    }

    /**
     * role_name attribute
     *
     * @return string
     */
    public function getRoleNameAttribute() {
    	$role = Role::findOrFail ( $this->role_id );
    	return $role->full_name;
    }
    
}
