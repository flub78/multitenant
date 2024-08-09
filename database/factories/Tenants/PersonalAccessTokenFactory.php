<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwriting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\PersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;


class PersonalAccessTokenFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalAccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = PersonalAccessToken::count();
        $next = $count + 1;

        return [
            'tokenable_type' => "tokenable_type_" . $next . "_" . Str::random(),
            'tokenable_id' => rand(0, 10000),
            'name' => "name_" . $next . "_" . Str::random(),
            'token' => "token_" . $next . "_" . Str::random(),
            'abilities' => $this->faker->unique()->text(200),
            'last_used_at' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
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
    public function error_cases() {
        $scenarios = [];
        // $scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
        // $scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
        return $scenarios;
    }
}
