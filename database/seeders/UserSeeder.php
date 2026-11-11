<?php

namespace Database\Seeders;

use App\Models\Tenant;
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

        $user2 = new User;
        $user2->name = "Adhie Avianto";
        $user2->email = "adhieavianto@gmail.com";
        $user2->password = "1234";
        $user2->role_id = 2;
        $user2->save();

        $tenant = new Tenant();
        $tenant->name = "Apple Inc.";
        $tenant->user_id = $user2->id;
        $tenant->save();
    }
}
