<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            //
        ];
    }
}
