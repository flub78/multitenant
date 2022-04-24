<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models\Tenants;

use App\Models\ModelWithLogs;
use App\Models\Tenants\UserRole;
use App\Models\Tenants\Role;
use App\Models\User;


/**
 * UserRolesView1 model
 *
 * Acces to the percistency layer
 * user_roles_view1 is a MySQL view
 *
 * @author fred
 *
 */
class UserRolesView1 extends ModelWithLogs {


    /**
     * The associated database table
     */
    protected $table = 'user_roles_view1';
 
    /**
     * Views usually have no regular factories. The generation of test data
     * usually implies to generate data in others tables.
     * 
     * @param array $argv arguments to pass to referenced factories
     */
    public static function factoryCreate($argv = []) {
        $cnt = self::count();
        // Generating one element
        // [[class_name]]::factory()->create($argv);
        
        $user_nb = 4;
        $user_ids = [];
        for ($i = 0; $i < $user_nb; $i++) {
        	$user_ids[] = User::factory()->create(['name' => 'user_' . $i]);
        }

        // var_dump($user_ids);
        
        return $cnt;
    }
}