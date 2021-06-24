<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConfigurationFactory extends Factory {
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
		$random_str = Str::random ( 6 );

		return [ 
				'key' => "key." . $next . '.' . $random_str,
				'value' => "value " . $next . '.' . $random_str
		];
	}

}
