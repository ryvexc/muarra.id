<?php

namespace App\Http\Controllers;

use App\Models\InventoryHistory;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class InventoryHistoryController extends Controller
{
    public function index()
    {
        $user = currentUser();

        $inventory_histories = [];

        if ($user instanceof User) {
            $inventory_histories = InventoryHistory::where("tenant_id", $user->tenant->id)->orderBy("created_at", "desc")->get();
        } elseif ($user instanceof Staff) {
            $inventory_histories = InventoryHistory::where("tenant_id", $user->tenant->id)->where("branch_id", $user->branch->id)->orderBy("created_at", "desc")->get();
        }

        return view("page.inventory_history.index", [
            "inventory_histories" => $inventory_histories
        ]);
    }
}
