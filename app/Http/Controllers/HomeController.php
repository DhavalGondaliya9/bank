<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPayments;

class HomeController extends Controller
{
    public function index(){
        $order_payments = OrderPayments::get();
        $filepath   = public_path('uploads/RHB PTBN.csv');
        $file       = fopen($filepath, "r");
        $importData_arr = array();
        $i = 0;
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            $num = count($filedata);
            if ($i <= 2) {
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
        foreach ($importData_arr as $key => $ia) {
            foreach ($order_payments as $key2 => $op) {
                if (trim($ia[6],"'") == $op->transaction_reference) {
                    if (str_replace(',', '', $ia[8]) == $op->amount) {
                        $importData_arr[$key]['match'] = true;
                        $order_payments[$key2]->match = true;
                    }
                }
            }
        }
        $data['csv_results'] = $importData_arr;
        $data['order_payments'] = $order_payments;
        return view('home', $data);
    }
}
