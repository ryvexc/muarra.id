<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportWalletStatement implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithStyles,
    WithEvents,
    WithColumnWidths,
    WithCustomStartCell
{
    private int $rowNumber = 0;

    public function __construct(
        private Builder $transactions,
        private $tenant,
        private $wallet
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Query
    |--------------------------------------------------------------------------
    */

    public function query(): Builder
    {
        return (clone $this->transactions)
            ->with('wallet')
            ->orderBy('created_at')
            ->orderBy('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Table Configuration
    |--------------------------------------------------------------------------
    */

    public function startCell(): string
    {
        return 'A11';
    }

    public function headings(): array
    {
        return [
            'No',
            'ID',
            'Date',
            'Time',
            'Wallet',
            'Description',
            'Debit (IDR)',
            'Credit (IDR)',
            'Balance (IDR)',
            'Notes',
        ];
    }

    public function map($transaction): array
    {
        return [
            ++$this->rowNumber,
            $transaction->normalizedId(),
            $transaction->created_at->format('d M Y'),
            $transaction->created_at->format('H:i:s'),
            ucfirst($transaction->wallet->type) . ' Wallet',
            ($transaction->description ?? '') . "\n" .
                ($transaction->subdescription ?? ''),
            $transaction->type === 'debit' ? $transaction->amount : null,
            $transaction->type === 'credit' ? $transaction->amount : null,
            $transaction->balance,
            $transaction->notes,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 28,
            'C' => 15,
            'D' => 13,
            'E' => 18,
            'F' => 42,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 30,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        // Table heading
        $sheet->getStyle('A11:J11')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '2563EB',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Header height
        $sheet->getRowDimension(11)->setRowHeight(35);

        // Data alignment
        if ($lastRow >= 12) {

            $sheet->getStyle("A12:J{$lastRow}")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Description wrap
            $sheet->getStyle("F12:F{$lastRow}")
                ->getAlignment()
                ->setWrapText(true);

            // Numeric formatting
            $sheet->getStyle("G12:I{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // No alignment
            $sheet->getStyle("A12:A{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $now = now();

                /*
                |--------------------------------------------------------------------------
                | Tenant Information
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A1', 'Nama/Name');
                $sheet->setCellValue('C1', $this->tenant->name);

                $sheet->setCellValue('H1', 'Periode/Period');
                $sheet->setCellValue(
                    'J1',
                    $now->copy()->startOfMonth()->format('d M Y') .
                        ' - ' .
                        $now->copy()->endOfMonth()->format('d M Y')
                );

                $sheet->setCellValue('A2', 'Dicetak pada/Issued on');
                $sheet->setCellValue('C2', $now->format('d M Y'));

                /*
                |--------------------------------------------------------------------------
                | Wallet Information
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A4', 'Wallet Statement');

                $sheet->setCellValue(
                    'A5',
                    'Nomor Rekening/Account Number'
                );

                $sheet->setCellValue('C5', $this->wallet->uuid);

                $sheet->setCellValue(
                    'A6',
                    'Mata Uang/Currency'
                );

                $sheet->setCellValue('C6', 'IDR');

                /*
                |--------------------------------------------------------------------------
                | Transaction Summary
                |--------------------------------------------------------------------------
                */

                $first = (clone $this->transactions)
                    ->reorder()
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->first();

                $last = (clone $this->transactions)
                    ->reorder()
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->first();

                $incoming = (clone $this->transactions)
                    ->reorder()
                    ->where('type', 'credit')
                    ->sum('amount');

                $outgoing = (clone $this->transactions)
                    ->reorder()
                    ->where('type', 'debit')
                    ->sum('amount');

                // Opening Balance
                $opening = $first
                    ? (
                        $first->type === 'credit'
                        ? $first->balance - $first->amount
                        : $first->balance + $first->amount
                    )
                    : ($this->wallet->balance ?? 0);

                // Closing Balance
                $closing = $last
                    ? $last->balance
                    : $opening;

                $summary = [
                    'Saldo Awal/Initial Balance' => $opening,
                    'Dana Masuk/Incoming Transactions' => $incoming,
                    'Dana Keluar/Outgoing Transactions' => $outgoing,
                    'Saldo Akhir/Closing Balance' => $closing,
                ];

                $row = 5;

                foreach ($summary as $label => $amount) {

                    $sheet->setCellValue("H{$row}", $label);
                    $sheet->setCellValue("J{$row}", $amount);

                    $sheet->mergeCells("H{$row}:I{$row}");

                    $row++;
                }

                /*
                |--------------------------------------------------------------------------
                | Layout
                |--------------------------------------------------------------------------
                */

                // Merge information cells
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');
                $sheet->mergeCells('C1:E1');
                $sheet->mergeCells('H1:I1');
                $sheet->mergeCells('C2:E2');
                $sheet->mergeCells('C5:E5');
                $sheet->mergeCells('C6:E6');

                // General alignment
                $sheet->getStyle('A1:J8')
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('J1')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Labels
                $sheet->getStyle('A1:A6')
                    ->getFont()
                    ->setBold(true);

                // Statement title
                $sheet->getStyle('A4')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(14);

                // Summary labels
                $sheet->getStyle('H5:H8')
                    ->getFont()
                    ->setBold(true);

                // Summary values
                $sheet->getStyle('J5:J8')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('J5:J8')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $sheet->getStyle('J5:J8')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Closing balance emphasis
                $sheet->getStyle('H8:J8')
                    ->getBorders()
                    ->getTop()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('H8:J8')
                    ->getFont()
                    ->setBold(true);

                /*
                |--------------------------------------------------------------------------
                | Table Layout
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A11:J11')
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_MEDIUM);

                // Freeze table header
                $sheet->freezePane('A12');

                // Print settings
                $sheet->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageSetup()
                    ->setFitToPage(true);

                $sheet->getPageMargins()->setTop(0.4);
                $sheet->getPageMargins()->setBottom(0.4);
                $sheet->getPageMargins()->setLeft(0.3);
                $sheet->getPageMargins()->setRight(0.3);
            },
        ];
    }
}
