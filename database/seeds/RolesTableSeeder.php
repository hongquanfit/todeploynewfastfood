<?php

use Illuminate\Database\Seeder;
use App\Model\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(
            [
                'id' => 2,
                'role' => 'User',
            ]
        );
        Role::create(
            [
                'id' => 1,
                'role' => 'Admin',
            ]
        );
    }
}
