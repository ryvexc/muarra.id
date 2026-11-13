<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Builder $products
    ) {}

    public function query(): Builder
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Name',
            'Category',
            'Subcategory',
            'Sell Price',
            'Cost Price',
            'Est. Profit',
            'Updated At',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->normalizedId(),
            $product->name,
            $product->category->name,
            $product->subcategory ?? "",
            $product->harga_jual,
            $product->harga_modal,
            $product->harga_jual - $product->harga_modal,
            $product->updated_at,
            $product->created_at,
        ];
    }
}
