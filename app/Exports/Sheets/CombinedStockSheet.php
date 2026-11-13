<?php

namespace App\Exports\Sheets;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CombinedStockSheet implements
    FromQuery,
    WithTitle,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private int $tenantId
    ) {}

    public function title(): string
    {
        return 'Combined';
    }

    public function query(): Builder
    {
        return Ingredient::query()
            ->where('tenant_id', $this->tenantId)
            ->with('unit_of_measurement')
            ->withSum('inventory as total_stock', 'stock')
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'Ingredient ID',
            'Ingredient Name',
            'Total Stock',
            'Unit',
            'Unit Price',
            'Last Updated',
            'Created At',
        ];
    }

    public function map($ingredient): array
    {
        return [
            $ingredient->normalizedId(),
            $ingredient->name,
            $ingredient->total_stock ?? 0,
            $ingredient->unit_of_measurement->symbol,
            $ingredient->harga_satuan,
            $ingredient->updated_at?->format('Y-m-d H:i:s'),
            $ingredient->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
