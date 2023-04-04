<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderPaymentMatchedRecordsExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(protected array $rows)
    {
    }

    public function styles(Worksheet $sheet): void
    {
        $unique = [];
        $endNumber = 2;
        $startNumber = '';

        foreach ($this->rows as $row) {
            if (isset($row[4])) {
                if (! isset($unique[$row[4]])) {
                    $startNumber = $endNumber;
                }

                $unique[$row[4]] = [$startNumber, $endNumber];
            }

            $endNumber++;
        }

        foreach ($unique as $value) {
            $sheet->getStyle('A' . $value[0] . ':D' . $value[1])->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => [
                            'argb' => '000000',
                        ],
                    ],
                ],
            ]);
        }
    }

    public function headings(): array
    {
        return ['Date', 'Order Id', 'Transaction Reference', 'Credit Amount'];
    }

    public function array(): array
    {
        $return = [];

        foreach ($this->rows as $row) {
            $return[] = [$row[3], $row[0], $row[1], $row[2]];
        }

        return $return;
    }

    public function title(): string
    {
        return 'Matched';
    }
}
