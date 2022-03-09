<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = Role::count ();
        $next = $count + 1;
        
        return [
            'name' => "name_" . $next . "_" . Str::random(),
            'description' => "description_" . $next . "_" . Str::random(),
        ];
    }
    
    /**
     * return a list of erroneous fields and associated expected errors 
     * [
     * 		["fieds" => [],
     * 		 "errors" => ["name" => "The name field is required."]
     * 		],
     * 		["fields" => ['name' => $too_long_name, 'email' => 'incorrect_email'],
     * 		 "errors" => ['name' => 'The name must not be greater than 255 characters.', 'email' => 'The email must be a valid email address.']
     * 		]
     * ]
     * @return string[]
     */
    public function error_cases () {
    	$scenarios = [];
    	$bad_name = "Too long............................................................................................................................................................................................................................................................................................................................................................................................................................................";
    	$scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
    	$scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
    	return $scenarios;
    }
}
