<?php

namespace App\Http\Controllers;

use App\Exports\ExportWalletStatement;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $user = currentUser();

        $cashWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cash")->first();
        $cashlessWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cashless")->first();

        $transactions = WalletTransaction::whereIn("wallet_id", [$cashWallet->id, $cashlessWallet->id])->get();

        return view("page.finance.wallet.transactions", [
            "transactions" => $transactions
        ]);
    }

    public function exportStatement(Request $request)
    {
        $user = currentUser();

        $cashWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cash")->first();
        $cashlessWallet = Wallet::where('tenant_id', $user->tenant->id)->where("branch_id", NULL)->where("type", "cashless")->first();

        $transactions = WalletTransaction::query()->whereIn("wallet_id", [$cashWallet->id, $cashlessWallet->id]);

        return Excel::download(
            new ExportWalletStatement($transactions, $user->tenant, $cashlessWallet),
            'statement-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
