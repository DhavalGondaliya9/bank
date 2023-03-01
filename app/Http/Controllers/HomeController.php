<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// use App\Models\OrderPayments;
use Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $bankArr = [];
        $paymentArr = [];
        $matchRecordCount = 0;
        $bankSession = $request->session()->get('bank');
        $orderPaymentSession = $request->session()->get('order_payment');
        $request->session()->forget('bank');
        $request->session()->forget('order_payment');
        if ($bankSession && $orderPaymentSession) {
            $bankFilepath = public_path('uploads/'.$bankSession);
            $PaymentFilepath = public_path('uploads/'.$orderPaymentSession);
            $bankFile = Excel::toArray([], $bankFilepath);
            $paymentFile = Excel::toArray([], $PaymentFilepath);
            unlink($bankFilepath);
            unlink($PaymentFilepath);
            $bankArr = $bankFile[0];
            $paymentArr = $paymentFile[0];
            unset($bankArr[0]);
            unset($paymentArr[0]);

            foreach ($bankArr as $key => $ia) {
                if ($ia[6] === null || $ia[8] === null) {
                    unset($bankArr[$key]);
                    continue;
                }

                foreach ($paymentArr as $key2 => $op) {
                    if (trim($ia[6], "'") === $op[1]) {
                        $bankAmount = number_format((float) str_replace(',', '', (string) $ia[8]), 2, '.', '');
                        $paymentAmount = number_format((float) str_replace(',', '', (string) $op[2]), 2, '.', '');
                        if ($bankAmount === $paymentAmount) {
                            unset($bankArr[$key]);
                            unset($paymentArr[$key2]);
                            $matchRecordCount += 1;
                        }
                    }
                }
            }

        }

        $data['bank'] = $bankArr;
        $data['payment'] = $paymentArr;
        $data['match_record_count'] = $matchRecordCount;

        return view('home', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'bank' => 'required|mimes:csv,xlsx,xls',
            'order_payment' => 'required|mimes:csv,xlsx,xls',
        ]);
        $bankFile = $request->file('bank');
        $orderPaymentFile = $request->file('order_payment');
        $destinationPath = 'uploads';
        $bankSession = $request->session()->get('bank');
        $orderPaymentSession = $request->session()->get('order_payment');
        if ($bankSession) {
            unlink($destinationPath.'/'.$bankSession);
        }

        if ($orderPaymentSession) {
            unlink($destinationPath.'/'.$orderPaymentSession);
        }

        $request->session()->put('bank', $bankFile->getClientOriginalName());
        $request->session()->put('order_payment', $orderPaymentFile->getClientOriginalName());
        $bankFile->move($destinationPath, $bankFile->getClientOriginalName());
        $orderPaymentFile->move($destinationPath, $orderPaymentFile->getClientOriginalName());

        return to_route('home');
    }
}
