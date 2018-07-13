<?php

use Illuminate\Database\Seeder;
use App\Model\StatusUser;

class StatusUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusUser::create([
            'status' => 'Good',
        ]);
        StatusUser::create([
            'status' => 'Block',
        ]);
    }
}
