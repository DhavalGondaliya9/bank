<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OrderPayments;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        $order_payments = OrderPayments::get();
        $filepath = public_path('uploads/RHB PTBN.csv');
        $file = fopen($filepath, 'r');
        $importData_arr = [];
        $i = 0;
        while (($filedata = fgetcsv($file, 1000, ',')) !== false) {
            $num = count($filedata);
            if ($i === 0) {
                $i++;

                continue;
            }

            // if ($i > 6 ) {
            //     break;
            // }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }

            $i++;
        }

        fclose($file);
        $match_record_count = 0;
        foreach ($importData_arr as $key => $ia) {
            foreach ($order_payments as $key2 => $op) {
                if (trim($ia[6], "'") === $op->transaction_reference) {
                    $amount = number_format((float) str_replace(',', '', $ia[8]), 2, '.', '');
                    if ($amount === $op->amount) {
                        // $importData_arr[$key]['match'] = true;
                        unset($importData_arr[$key]);
                        unset($order_payments[$key2]);
                        $match_record_count += 1;
                        // $order_payments[$key2]->match = true;
                    }
                }
            }

            if ($ia[6] !== '') {
                continue;
            }

            if ($ia[8] !== '') {
                continue;
            }

            unset($importData_arr[$key]);
        }

        $data['csv_results'] = $importData_arr;
        $data['order_payments'] = $order_payments;
        $data['match_record_count'] = $match_record_count;

        return view('home', $data);
    }
}
