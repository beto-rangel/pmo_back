<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate(); // truncate user table each time of seeders run
        User::create([ // create a new user
            'email' => 'marioalberto.rangel.contractor@bbva.com',
            'password' => Hash::make('admin'),
            'name' => 'Alberto Rangel'
        ]);
    }
}
