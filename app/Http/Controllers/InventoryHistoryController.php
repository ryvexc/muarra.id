<?php

namespace App\Http\Controllers;

use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryHistoryController extends Controller
{
    public function index()
    {
        $user = currentUser();

        $inventory_histories = InventoryHistory::where("tenant_id", $user->tenant->id)->where("branch_id", $user->branch->id)->orderBy("created_at", "desc")->get();

        return view("page.inventory_history.index", [
            "inventory_histories" => $inventory_histories
        ]);
    }
}
