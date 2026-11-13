<?php

namespace App\Exports\Sheets;

use App\Models\Branch;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BranchStockSheet implements
    FromQuery,
    WithTitle,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private Branch $branch,
        private int $tenantId
    ) {}

    public function title(): string
    {
        return substr(
            preg_replace('/[\\\\\/?*\[\]:]/', '-', $this->branch->name),
            0,
            31
        );
    }

    public function query(): Builder
    {
        return Inventory::query()
            ->where('tenant_id', $this->tenantId)
            ->where('branch_id', $this->branch->id)
            ->with(['ingredient.unit_of_measurement'])
            ->orderBy('ingredient_id');
    }

    public function headings(): array
    {
        return [
            'Ingredient ID',
            'Ingredient Name',
            'Stock',
            'Unit',
            'Unit Price',
            'Last Updated',
            'Created At',
        ];
    }

    public function map($inventory): array
    {
        $ingredient = $inventory->ingredient;

        return [
            $ingredient->normalizedId(),
            $ingredient->name,
            $inventory->stock,
            $ingredient->unit_of_measurement->symbol,
            $ingredient->harga_satuan,
            $ingredient->updated_at?->format('Y-m-d H:i:s'),
            $ingredient->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
