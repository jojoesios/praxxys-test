<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Test Admin',
            'username' => 'admin',
            'password' => bcrypt('123456'),
        ]);
    }
}
