<?php

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
}
