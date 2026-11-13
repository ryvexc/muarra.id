<?php

namespace App\Exports;

use App\Exports\Sheets\AllStockSheet;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\Export;
use App\Exports\Sheets\BranchStockSheet;
use App\Exports\Sheets\CombinedStockSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StocksExport implements Export, WithMultipleSheets
{
    public function __construct(
        private int $tenantId,
        private ?int $branchId = null
    ) {}

    public function sheets(): array
    {
        $sheets = [
            new AllStockSheet($this->tenantId),
            new CombinedStockSheet($this->tenantId),
        ];

        $branches = Branch::query()
            ->where('tenant_id', $this->tenantId)
            ->get();

        foreach ($branches as $branch) {
            $sheets[] = new BranchStockSheet(
                $branch,
                $this->tenantId
            );
        }

        return $sheets;
    }
}
