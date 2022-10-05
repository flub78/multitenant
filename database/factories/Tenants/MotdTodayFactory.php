<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\MotdToday;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;


class MotdTodayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MotdToday::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = MotdToday::count ();
        $next = $count + 1;
        
        return [
            'id' => rand(0, 10000),
            'title' => "title_" . $next . "_" . Str::random(),
            'message' => "message_" . $next . "_" . Str::random(),
            'publication_date' => $this->faker->unique()->date(__("general.database_date_format")),
            'end_date' => $this->faker->unique()->date(__("general.database_date_format")),
            'created_at' => motds.created_at faker type=timestamp, subtype=
,
            'updated_at' => motds.updated_at faker type=timestamp, subtype=
,
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
