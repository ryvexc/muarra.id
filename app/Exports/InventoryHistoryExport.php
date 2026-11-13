<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryHistoryExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Builder $inventory_histories
    ) {}

    public function query(): Builder
    {
        return $this->inventory_histories;
    }

    public function headings(): array
    {
        return [
            'History ID',
            'Branch',
            'Item ID',
            'Item Name',
            'Type',
            'Quantity',
            'Stock Before',
            'Stock After',
            'Ref-ID',
            'Created By',
            'Description',
            'Created At'
        ];
    }

    public function map($inventory_history): array
    {
        return [
            $inventory_history->normalizedId(),
            $inventory_history->branch->name,
            $inventory_history->ingredient->normalizedId(),
            $inventory_history->ingredient->name,
            $inventory_history->type,
            $inventory_history->quantity,
            $inventory_history->stock_before,
            $inventory_history->stock_after,
            $inventory_history->reference_type . "-" . $inventory_history->reference_id,
            $inventory_history->created_by_staff_id ? $inventory_history->createdByStaff->name : $inventory_history->createdByUser->name,
            $inventory_history->description,
            $inventory_history->created_at,
        ];
    }
}
