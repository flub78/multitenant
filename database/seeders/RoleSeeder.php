<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\App\Models\Tenants\Role::factory()->create(['name' => 'admin', 'description' => 'admin user']);
    	\App\Models\Tenants\Role::factory()->create(['name' => 'user', 'description' => 'Regular user']);
    	\App\Models\Tenants\Role::factory()->create(['name' => 'guest', 'description' => 'Read only user']);
    }
}
