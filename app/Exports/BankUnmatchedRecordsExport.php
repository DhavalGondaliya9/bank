<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BankUnmatchedRecordsExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(protected array $rows)
    {
    }

    public function headings(): array
    {
        return ['Date', 'Transaction Reference', 'Credit'];
    }

    public function array(): array
    {
        $return = [];

        foreach ($this->rows as $row) {
            $return[] = [$row[0], $row[6], $row[8]];
        }

        return $return;
    }

    public function title(): string
    {
        return 'Unmatched';
    }
}
