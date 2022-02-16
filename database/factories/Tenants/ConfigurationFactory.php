<?php

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
            'key' => $this->faker->randomElement(["app.locale","app.timezone","browser.locale"]),
            'value' => "value_" . $next . "_" . Str::random(),
		];
	}

}
