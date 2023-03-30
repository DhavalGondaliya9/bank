<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\RedirectHomeWithErrorException;
use App\Exports\BankRecordsExport;
use App\Exports\OrderPaymentRecordsExport;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->uploadSelectedFiles($request);
        return to_route('list');
    }

    public function list(Request $request)
    {
        $session = $request->session();

        $bankFile = $session->get('bank');
        $orderPaymentsFile = $session->get('order_payment');

        $this->forgetSession($session);

        $this->checkFilesValidity($bankFile, $orderPaymentsFile);

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

    public function ignoreRecord(Request $request)
    {
        $type = $request->input('type');

        $ignoredRecords = [];
        $unmatchRecords = [];

        if ($type == 'bank') {
            $bankUnmatchRecord = session()->get('bankUnmatchRecord');

            $bankRecordKey = array_flip($request->bankRecordKey);

            $ignoredRecords = array_intersect_key($bankUnmatchRecord, $bankRecordKey);

            $unmatchRecords = array_diff_key($bankUnmatchRecord, $bankRecordKey);

            session()->put('bankIgnoreRecord', $ignoredRecords);
            session()->put('bankUnmatchRecord', $unmatchRecords);
        }

        if ($type == 'order-payment') {
            $orderPaymentUnmatchRecord = session()->get('orderPaymentUnmatchRecord');

            $orderPaymentRecordKey = array_flip($request->orderPaymentRecordKey);

            $ignoredRecords = array_intersect_key($orderPaymentUnmatchRecord, $orderPaymentRecordKey);

            $unmatchRecords = array_diff_key($orderPaymentUnmatchRecord, $orderPaymentRecordKey);

            session()->put('orderPaymentIgnoreRecord', $ignoredRecords);
            session()->put('orderPaymentUnmatchRecord', $unmatchRecords);
        }

        return response()->json(['success' => true]);
    }

    public function downloadBankRecords()
    {
        return $this->downloadRecords('bank', BankRecordsExport::class, 'bank-records.xlsx');
    }

    public function downloadOrderPaymentRecords()
    {
        return $this->downloadRecords('orderPayment', OrderPaymentRecordsExport::class, 'order-payment-records.xlsx');
    }

    private function downloadRecords($records, $exportClass, $fileName)
    {
        $data = [
            $records . 'MatchRecord'   => session()->get($records . 'MatchRecord') ?? [],
            $records . 'UnmatchRecord' => session()->get($records . 'UnmatchRecord') ?? [],
            $records . 'IgnoreRecord'  => session()->get($records . 'IgnoreRecord') ?? [],
        ];

        return (new $exportClass($data))->download($fileName);
    }

    private function checkFilesValidity($bankFile, $orderPaymentsFile)
    {
        if (! $bankFile) {
            throw new RedirectHomeWithErrorException();
        }

        if (! $orderPaymentsFile) {
            throw new RedirectHomeWithErrorException();
        }

        if (! file_exists('uploads/'.$bankFile)) {
            abort(403, 'Bank File not found');
        }

        if (! file_exists('uploads/'.$orderPaymentsFile)) {
            abort(403, 'Order payment File not found');
        }
    }

    private function forgetSession($session): void
    {
        $session->forget('bank');
        $session->forget('bankIgnoreRecord');
        $session->forget('bankMatchRecord');
        $session->forget('bankUnmatchRecord');

        $session->forget('order_payment');
        $session->forget('orderPaymentIgnoreRecord');
        $session->forget('orderPaymentMatchRecord');
        $session->forget('orderPaymentUnmatchRecord');
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
    private function compareFileData($bankArray, $paymentArray): array
    {
        $data = [];
        $matchRecordCount = 0;

        $bankMatchRecord = [];
        $OrderPaymentMatchRecord = [];

        foreach ($bankArray as $bankKey => $bankValue) {
            if ($bankValue[6] === null || $bankValue[8] === null) {
                unset($bankArray[$bankKey]);

                continue;
            }

            $bankAmount = number_format((float) str_replace(',', '', (string) $bankValue[8]), 2, '.', '');
            $bankArray[$bankKey][8] = $bankAmount;

            foreach ($paymentArray as $paymentKey => $paymentValue) {
                $paymentAmount = number_format((float) str_replace(',', '', (string) $paymentValue[2]), 2, '.', '');
                $paymentArray[$paymentKey][2] = $paymentAmount;

                if (trim($bankValue[6], "'") === $paymentValue[1] && $bankAmount === $paymentAmount) {
                    $bankMatchRecord[] = $bankArray[$bankKey];
                    $OrderPaymentMatchRecord[] = $paymentArray[$paymentKey];

                    unset($bankArray[$bankKey]);
                    unset($paymentArray[$paymentKey]);

                    $matchRecordCount += 1;
                }
            }
        }

        session()->put('bankMatchRecord', $bankMatchRecord);
        session()->put('orderPaymentMatchRecord', $OrderPaymentMatchRecord);

        session()->put('bankUnmatchRecord', $bankArray);
        session()->put('orderPaymentUnmatchRecord', $paymentArray);

        $bankAmountColumn = array_column($bankArray, '8');
        $bankTotalAmount = array_sum($bankAmountColumn);

        $paymentAmountColumn = array_column($paymentArray, '2');
        $paymentTotalAmount = array_sum($paymentAmountColumn);

        $data['bank'] = $bankArray;
        $data['payment'] = $paymentArray;
        $data['matchRecordCount'] = $matchRecordCount;
        $data['totalRecordBank'] = $matchRecordCount + (is_countable($bankArray) ? count($bankArray) : 0);
        $data['totalRecordPayment'] = $matchRecordCount + (is_countable($paymentArray) ? count($paymentArray) : 0);
        $data['unaccountedAmountBank'] = $bankTotalAmount;
        $data['unaccountedAmountPayment'] = $paymentTotalAmount;

        return $data;
    }

    private function uploadSelectedFiles(Request $request): void
    {
        $bankFile = $request->file('bank');
        $orderPayment = $request->file('order_payment');

        $request->session()->put('bank', $bankFile->getClientOriginalName());
        $bankFile->move('uploads', $bankFile->getClientOriginalName());

        $request->session()->put('order_payment', $orderPayment->getClientOriginalName());
        $orderPayment->move('uploads', $orderPayment->getClientOriginalName());
    }
}
