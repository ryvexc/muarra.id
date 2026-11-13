<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;
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

        $cashWallet = new Wallet();
        $cashWallet->uuid = Str::uuid();
        $cashWallet->type = "cash";
        $cashWallet->tenant_id = $user->tenant->id;
        $cashWallet->balance = 0;
        $cashWallet->save();

        $cashlessWallet = new Wallet();
        $cashlessWallet->uuid = Str::uuid();
        $cashlessWallet->type = "cash";
        $cashlessWallet->tenant_id = $user->tenant->id;
        $cashlessWallet->balance = 0;
        $cashlessWallet->save();
    }
}
