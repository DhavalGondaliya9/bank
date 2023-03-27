<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'bank' => 'required|mimes:csv,xlsx,xls',
            'order_payment' => 'required|mimes:csv,xlsx,xls',
        ]);

        $this->uploadSelectedFiles($request);

        return to_route('list');
    }

    public function list(Request $request)
    {
        $session = $request->session();
        $bankFile = $session->get('bank');
        $orderPaymentsFile = $session->get('order_payment');
        $this->forgetSession($session);

        if (!$bankFile || !$orderPaymentsFile) {
            return to_route('home');
        }

        if (! file_exists('uploads/'.$bankFile)) {
            return to_route('home')->withErrors([
                'bank' => 'Bank File does not exit',
            ]);
        }

        if (! file_exists('uploads/'.$orderPaymentsFile)) {
            return to_route('home')->withErrors([
                'order_payment' => 'Order Payment File does not exit',
            ]);
        }

        $bankFilePath = public_path('uploads/'.$bankFile);
        $paymentFilePath = public_path('uploads/'.$orderPaymentsFile);
        $bankArray = Excel::toArray([], $bankFilePath);
        $paymentArray = Excel::toArray([], $paymentFilePath);
        $this->unlinkFile([$bankFilePath, $paymentFilePath]);
        unset($bankArray[0][0]);
        unset($paymentArray[0][0]);
        $data = $this->compareFileData($bankArray[0], $paymentArray[0]);

        return view('list', $data);
    }

    private function forgetSession($session): void
    {
        $session->forget('bank');
        $session->forget('order_payment');
    }

    private function unlinkFile(array $filePath): void
    {
        foreach ($filePath as $path) {
            unlink($path);
        }
    }

    /**
     * @return mixed[]
     */
    private function compareFileData($bankArr, $paymentArr): array
    {
        $data = [];
        $matchRecordCount = 0;
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

        $unaccountedAmountBank = collect();
        $unaccountedAmountPayment = collect();
        foreach ($bankArr as $ia) {
            $bankAmount = number_format((float) str_replace(',', '', (string) $ia[8]), 2, '.', '');
            $unaccountedAmountBank->push($bankAmount);
        }

        foreach ($paymentArr as $op) {
            $paymentAmount = number_format((float) str_replace(',', '', (string) $op[2]), 2, '.', '');
            $unaccountedAmountPayment->push($paymentAmount);
        }

        $data['bank'] = $bankArr;
        $data['payment'] = $paymentArr;
        $data['matchRecordCount'] = $matchRecordCount;
        $data['totalRecordBank'] = $matchRecordCount + (is_countable($bankArr) ? count($bankArr) : 0);
        $data['totalRecordPayment'] = $matchRecordCount + (is_countable($paymentArr) ? count($paymentArr) : 0);
        $data['unaccountedAmountBank'] = $unaccountedAmountBank->sum();
        $data['unaccountedAmountPayment'] = $unaccountedAmountPayment->sum();

        return $data;
    }

    private function uploadSelectedFiles(Request $request): void
    {
        foreach ($request->file() as $key => $file) {
            $request->session()->put($key, $file->getClientOriginalName());
            $file->move('uploads', $file->getClientOriginalName());
        }
    }
}
