<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BranchesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Builder $branches
    ) {}

    public function query(): Builder
    {
        return $this->branches;
    }

    public function headings(): array
    {
        return [
            'Branch ID',
            'Merchant',
            'Name',
            'Manager Name',
            'Manager Email',
            'Manager Phone Number',
            'Address'
        ];
    }

    public function map($branch): array
    {
        return [
            $branch->normalizedId(),
            $branch->tenant->name,
            $branch->name,
            $branch->manager->name ?? "",
            $branch->manager->email ?? "",
            $branch->manager->phone_number ?? "",
            $branch->address
        ];
    }
}
