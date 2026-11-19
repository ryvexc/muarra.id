<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function main()
    {
        $user = currentUser();

        $cashWallet = NULL;
        $cashlessWallet = NULL;

        if ($user->role->id == 3) {
            $cashWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", $user->branch->id)->where("type", "cash")->first();
            $cashlessWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", $user->branch->id)->where("type", "cashless")->first();
        } else if ($user->role->id == 2) {
            $cashWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cash")->first();
            $cashlessWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cashless")->first();
        }

        $transactions = WalletTransaction::whereIn("wallet_id", [$cashWallet->id, $cashlessWallet->id])->whereDate("created_at", Carbon::today())->latest()->get();

        return view("page.finance.wallet.main", [
            "cashWallet" => $cashWallet,
            "cashlessWallet" => $cashlessWallet,
            "transactions" => $transactions
        ]);
    }

    public function tenant()
    {
        return view("page.maintenance.disabled");
    }
}
