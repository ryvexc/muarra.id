<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function main()
    {
        return view("page.finance.wallet.main");
    }

    public function tenant()
    {
        return view("page.maintenance.disabled");
    }
}
