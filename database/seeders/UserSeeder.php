<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = "Arif Kurniawan";
        $user->email = "ryvexc@gmail.com";
        $user->password = "1234";
        $user->role_id = 1;
        $user->save();

        $user = new User;
        $user->name = "Adhie Avianto";
        $user->email = "adhieavianto@gmail.com";
        $user->password = "1234";
        $user->role_id = 2;
        $user->save();
    }
}
