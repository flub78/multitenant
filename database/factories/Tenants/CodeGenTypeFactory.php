<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\CodeGenType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CodeGenTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CodeGenType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = CodeGenType::count ();
        $next = $count + 1;
        
        return [
            'name' => "name_" . $next . "_" . Str::random(),
            'phone' => "phone_" . $next . "_" . Str::random(),
        	'description' => $this->faker->unique()->text(200),
        	'year_of_birth' => $this->faker->unique()->year(),
            'weight' => $this->faker->unique()->randomFloat(2, 0.0, 1000.0),
        	'birthday' => $this->faker->unique()->date(__("general.date_format")),
        	'tea_time' => $this->faker->unique()->time("H:i:s"),
        	'takeoff' => $this->faker->unique()->date(__("general.datetime_format")),
        	'price' => $this->faker->unique()->randomFloat(2, 0, 100.0),
        	'big_price' => $this->faker->unique()->randomFloat(2, 0.0, 1000.0),
        	'qualifications' => $this->faker->unique()->randomNumber(),
            'picture' => "picture_" . $next . "_" . Str::random(),
            'attachment' => "attachment_" . $next . "_" . Str::random(),
        ];
    }
    
    /**
     * return a list of erroneous fields and associated expected errors 
     * [
     *      ["fieds" => [],
     *       "errors" => ["name" => "The name field is required."]
     *      ],
     *      ["fields" => ['name' => $too_long_name, 'email' => 'incorrect_email'],
     *       "errors" => ['name' => 'The name must not be greater than 255 characters.', 'email' => 'The email must be a valid email address.']
     *      ]
     * ]
     * @return string[]
     */
    public function error_cases () {
        $scenarios = [];
        // $scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
        // $scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
       return $scenarios;       
    }
}
