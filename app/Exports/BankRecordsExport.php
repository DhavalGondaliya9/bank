<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BankRecordsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(protected $sheets)
    {
    }


    public function sheets(): array
    {
        return [
            new BankMatchedRecordsExport($this->sheets['bankMatchRecord']),
            new BankUnmatchedRecordsExport($this->sheets['bankUnmatchRecord']),
            new BankIgnoreRecordsExport($this->sheets['bankIgnoreRecord']),
        ];
    }
}
