<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;


/**
 * Role model
 *
 * Acces to the percistency layer
 *
 * @author fred
 *
 */
class Role extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'roles';
    
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
	protected $fillable = ["name", "description"];

    /**
     * Return a human readable unique string
     */
    public function image() {
    	return $this->name;						// code generator version
        // return "role_" . $this->id;			manually replace
    }
    
    /**
     * Return a selector to select one element
     */
    public static function selector($where = []) {
        $roles = Role::where($where)->get();
        $res = [];
        foreach ($roles as $role) {
            $res[] = ['name' => $role->image(), 'id' => $role->id];
        }
        return $res;
    }
}