<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StaffExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private Builder $staffs
    ) {}

    public function query(): Builder
    {
        return $this->staffs;
    }

    public function headings(): array
    {
        return [
            'Staff ID',
            'Name',
            'Email',
            'Phone Number',
            'Role',
            'Assigned In'
        ];
    }

    public function map($staff): array
    {
        return [
            $staff->normalizedId(),
            $staff->name,
            $staff->email,
            $staff->phone_number ?? "",
            $staff->role->name ?? "",
            $staff->branch->name ?? ""
        ];
    }
}
