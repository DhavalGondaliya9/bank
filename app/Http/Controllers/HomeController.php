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
        $session = $request->session();
        $bankSession = $session->get('bank');
        $orderPaymentSession = $session->get('order_payment');
        $this->forgetSession($session);
        if ($bankSession && $orderPaymentSession) {
            if (!file_exists('uploads/'.$bankSession) || !file_exists('uploads/'.$orderPaymentSession)) {
                return to_route('home')->withErrors(['error' => 'File does not exit']);
            }
            $bankFilepath = public_path('uploads/'.$bankSession);
            $PaymentFilepath = public_path('uploads/'.$orderPaymentSession);
            $bankArr = Excel::toArray([], $bankFilepath);
            $paymentArr = Excel::toArray([], $PaymentFilepath);
            $this->unlinkFile([$bankFilepath,$PaymentFilepath]);
            // remove first index array
            unset($bankArr[0][0]);
            unset($paymentArr[0][0]);
            $data = $this->compareFileData($bankArr[0],$paymentArr[0]);
        }
        return view('home', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validation($request);
        $this->fileMove($request);
        return to_route('home');
    }

    private function forgetSession($session)
    {
        $session->forget('bank');
        $session->forget('order_payment');
    }

    private function unlinkFile($filePath)
    {
        foreach ($filePath as $path) {
            unlink($path);
        }
    }

    private function compareFileData($bankArr,$paymentArr)
    {
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
            $data['bank'] = $bankArr;
            $data['payment'] = $paymentArr;
            $data['match_record_count'] = $matchRecordCount;
            return $data;
    }

    private function validation($request)
    {
        $request->validate([
            'bank' => 'required|mimes:csv,xlsx,xls',
            'order_payment' => 'required|mimes:csv,xlsx,xls',
        ]);
    }

    private function fileMove($request)
    {
        foreach ($request->file() as $key =>$file) {
            $request->session()->put($key, $file->getClientOriginalName());
            $file->move('uploads', $file->getClientOriginalName());
        }
    }
}
