<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConfigurationFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Configuration::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition() {
		$count = Configuration::count ();
		$next = $count + 1;

		return [ 
            'key' => $this->faker->unique()->randomElement(["app.locale","app.timezone","browser.locale"]),
            'value' => "value_" . $next . "_" . Str::random(),
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
        $scenarios[] = ["fields" => ["key" => "app_bad_key"], "errors" => ["key" => "The key format is invalid."]];
        $scenarios[] = ["fields" => ["key" => "app.bad_key"], "errors" => ["key" => "The selected key is invalid."]];
        return $scenarios;       
    }
}
