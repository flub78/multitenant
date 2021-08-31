<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];
    
    // As names are unique, just create aliases
    public function getFullNameAttribute() {
    	return $this->name;
    }
    
    public function getShortNameAttribute() {
    	return $this->name;
    }
    
    public static function selector($where = []) {
    	$users = Role::where($where)->get();
    	$res = [];
    	foreach ($users as $user) {
    		$res[] = ['name' => $user->full_name, 'id' => $user->id];
    	}
    	return $res;
    }
    
}
