<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrderPaymentRecordsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(protected $sheets)
    {
    }


    public function sheets(): array
    {
        return [
            new OrderPaymentMatchedRecordsExport($this->sheets['orderPaymentMatchRecord']),
            new OrderPaymentUnmatchedRecordsExport($this->sheets['orderPaymentUnmatchRecord']),
            new OrderPaymentIgnoreRecordsExport($this->sheets['orderPaymentIgnoreRecord']),
        ];
    }
}
