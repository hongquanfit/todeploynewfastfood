<?php

use Illuminate\Database\Seeder;
use App\Model\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
        	'types' => 'Desert',
        	'orders' => 1,
        ]);
        Type::create([
        	'types' => 'Noodle',
        	'orders' => 2,
        ]);
        Type::create([
        	'types' => 'Rice',
        	'orders' => 3,
        ]);
    }
}
