<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Builder $orders
    ) {}

    public function query(): Builder
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Date',
            'Time',
            'Pembayaran',
            'Customer Name',
            'Merchant',
            'Branch',
            'Cashier Name',
            'Subtotal',
            'Deduction',
            'Total'
        ];
    }

    public function map($order): array
    {
        return [
            $order->normalizedId(),
            $order->created_at->format('Y-m-d'),
            $order->created_at?->format('H:i:s'),
            $order->payment_type,
            $order->cust_name,
            $order->tenant->name,
            $order->branch->name,
            $order->cashier->name,
            $order->subtotal,
            - ($order->subtotal - $order->total),
            $order->total
        ];
    }
}
