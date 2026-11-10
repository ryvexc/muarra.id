<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function index()
    {
        $users = User::all()->where("id", ">", 1)->whereNull("deleted_at");

        return view("page.registered_users.index", [
            "users" => $users
        ]);
    }
}
