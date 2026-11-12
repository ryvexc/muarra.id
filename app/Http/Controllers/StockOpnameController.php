<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\StockReport;
use App\Models\StockReportItem;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        $stockReports = StockReport::latest()
            ->get()
            ->groupBy(function ($report) {
                return $report->created_at->format('Y-m-d');
            });

        return view('page.stock.report.index', [
            "stockReports" => $stockReports
        ]);
    }

    public function view(string $id)
    {
        $stockReportItems = StockReportItem::where("stock_report_id", $id)->get();

        return view("page.stock.report.view", [
            "stockReportItems" => $stockReportItems
        ]);
    }

    public function new()
    {
        $ingredients = Ingredient::whereNull("deleted_at")->get();

        $latest_opening_stock_report = StockReportItem::select(
            'ingredient_id',
            'actual_stock'
        )
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('stock_report_items')
                    ->groupBy('ingredient_id');
            })
            ->pluck('actual_stock', 'ingredient_id');

        $ingredients->transform(function ($ingredient) use ($latest_opening_stock_report) {

            if ($latest_opening_stock_report->has($ingredient->id)) {
                // Sudah pernah opname
                $ingredient->opening_stock =
                    $latest_opening_stock_report[$ingredient->id];
            } else {
                // Belum pernah opname
                $supply = Supply::where(
                    'ingredient_id',
                    $ingredient->id
                )->sum('quantity');

                $ingredient->opening_stock =
                    $ingredient->inventory->where("branch_id", currentUser()->branch->id)->first()->stock - $supply;
            }

            return $ingredient;
        });

        return view('page.stock.report.new', [
            'ingredients' => $ingredients,
            'latest_opening_stock_report' => $latest_opening_stock_report
        ]);
    }

    public function createReport(Request $request)
    {
        DB::transaction(function () use ($request) {

            $stockReport = new StockReport();
            $stockReport->tenant_id = currentUser()->tenant->id;
            $stockReport->staff_id = currentUser()->id;
            $stockReport->branch_id = currentUser()->branch->id;
            $stockReport->stock_matched_count = 0;
            $stockReport->stock_surplus_count = 0;
            $stockReport->stock_surplus_value = 0;
            $stockReport->stock_shortage_count = 0;
            $stockReport->stock_shortage_value = 0;
            $stockReport->save();

            $matchedCount = 0;
            $surplusCount = 0;
            $surplusValue = 0;
            $shortageCount = 0;
            $shortageValue = 0;

            foreach ($request->items as $item) {

                $difference = $item['difference'] ?? 0;

                $ingredient = Ingredient::findOrFail(
                    $item['ingredient_id']
                );

                if ($difference > 0) {
                    $surplusCount++;
                } elseif ($difference < 0) {
                    $shortageCount++;
                } else {
                    $matchedCount++;
                }

                $unitPrice = $ingredient->harga_satuan ?? 0;

                $differenceValue = $difference * $unitPrice;

                if ($difference > 0) {
                    $surplusValue += $differenceValue;
                } elseif ($difference < 0) {
                    $shortageValue += abs($differenceValue);
                }

                $stockReportItem = new StockReportItem();
                $stockReportItem->stock_report_id = $stockReport->id;
                $stockReportItem->ingredient_id = $item['ingredient_id'];
                $stockReportItem->opening_stock = $item['opening_stock'];
                $stockReportItem->outgoing_stock = $item['outgoing_stock'];
                $stockReportItem->incoming_stock = $item['incoming_stock'];
                $stockReportItem->expected_stock = $item['expected_stock'];
                $stockReportItem->actual_stock = $item['actual_stock'] ?? 0;
                $stockReportItem->difference = $difference;
                $stockReportItem->unit_price = $unitPrice;
                $stockReportItem->difference_value = $differenceValue;
                $stockReportItem->notes = $item['notes'] ?? null;
                $stockReportItem->save();

                $inventory = Inventory::where("branch_id", currentUser()->branch->id)->where("tenant_id", currentUser()->tenant->id)->where("ingredient_id", $ingredient->id)->first();

                $stock_before = $inventory->stock;
                $inventory->stock = $item['actual_stock'] ?? 0;
                $inventory->save();

                $history = new InventoryHistory();
                $history->tenant_id = currentUser()->tenant->id;
                $history->branch_id = currentUser()->branch->id;
                $history->ingredient_id = $item["ingredient_id"];
                $history->type = "adjustment";
                $history->quantity = $inventory->stock - $stock_before;
                $history->stock_before = $stock_before;
                $history->stock_after = $inventory->stock;
                $history->reference_type = "stock report";
                $history->reference_id = $stockReport->id;
                $history->notes = "Adjustment by Stock Report #" . $stockReport->normalizedId();
                $history->created_by_staff_id = currentUser()->id;
                $history->save();

                $ingredient->save();
            }

            if ($shortageCount > 0) {
                $status = 'shortage';
            } elseif ($surplusCount > 0) {
                $status = 'surplus';
            } else {
                $status = 'matched';
            }

            $stockReport->stock_matched_count = $matchedCount;

            $stockReport->stock_surplus_count = $surplusCount;
            $stockReport->stock_surplus_value = $surplusValue;

            $stockReport->stock_shortage_count = $shortageCount;
            $stockReport->stock_shortage_value = $shortageValue;

            $stockReport->status = $status;
            $stockReport->save();

            Supply::where("tenant_id", currentUser()->tenant->id)->where("branch_id", currentUser()->branch->id)->whereNull('stock_report_id')->update([
                'stock_report_id' => $stockReport->id
            ]);

            InventoryHistory::where("tenant_id", currentUser()->tenant->id)->where("branch_id", currentUser()->branch->id)->whereNull("stock_report_id")->update([
                'stock_report_id' => $stockReport->id
            ]);
        });

        return redirect()->route("stock-report.index");
    }
}
