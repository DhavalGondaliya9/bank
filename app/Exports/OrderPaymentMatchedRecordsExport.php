<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderPaymentMatchedRecordsExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(protected array $rows)
    {
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
