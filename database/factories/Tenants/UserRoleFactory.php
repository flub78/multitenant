<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = UserRole::count ();
        $next = $count + 1;
        
        return [
        	'user_id' => $this->faker->randomNumber(), // Foreign key to users, QueryException
        	'role_id' => $this->faker->randomNumber(), // Foreign key to roles, QueryException
        ];
    }
}
