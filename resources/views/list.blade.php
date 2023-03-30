
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Bank Reconciliation Tool</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/tailwind.min.css" />
        <script src="js/alpine.js" defer></script>
        <script src="js/custom.js"></script>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <section class="main my-100">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-12 mb-5">
                    <div class="col-span-12">
                        <a class="font-medium text-md text-white bg-indigo-700 rounded-2xl text-center py-1 px-4" href="{{ route('home') }}">< Back</a>
                        <a class="font-medium text-md text-white bg-indigo-700 rounded-2xl text-center py-1 px-4 float-right" href="{{ route('download-order-payment-records') }}">Download Order Payment Records</a>
                        <a class="font-medium text-md text-white bg-indigo-700 rounded-2xl text-center py-1 px-4 float-right mr-5" href="{{ route('download-bank-records') }}">Download Bank Records</a>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6">
                    <div x-data="bank" class="col-span-12 md:col-span-6">
                        <h1 class="font-semibold text-3xl leading-none text-black mb-6">Bank CSV</h1>
                        <div class="flex">
                            <ul class="list-inside font-medium text-lg leading-none text-black mr-14">
                                <li class="mb-3 text-red-600">Unmatched Records : {{ isset($bank) ? count($bank) : 0 }}</li>
                                <li class="mb-3 text-green-600">Matched Records : {{ isset($matchRecordCount) ? $matchRecordCount : 0  }}</li>
                            </ul>
                            <ul class="list-inside font-medium text-lg leading-none text-black">
                                <li class="mb-3 text-red-600">Unaccounted Amount : {{ isset($unaccountedAmountBank) ? $unaccountedAmountBank : 0 }}</li>
                                <li class="mb-3 text-black">Total Records : {{ isset($totalRecordBank) ? $totalRecordBank : 0 }}</li>
                            </ul>
                        </div>
                        <div class="flex flex-col mt-8">
                            <div class="col-span-12 mb-4">
                                <button x-show="bankRecord.length > 0 ? true : false;" @click="hideBankData" class="font-medium text-md text-white bg-indigo-700 rounded-2xl text-center py-1 px-4" style="display: none;">Ignore</button>
                            </div>
                            <div class="overflow-auto sm:rounded-2xl table-border-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr class="border-none">
                                            <th></th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Date</th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Transaction Reference</th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 border-none">
                                        @if(isset($bank))
                                            @foreach ($bank as $key => $val)
                                                <tr class="border-none bank-record-hide{{ $key }}">
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <input value="{{ $key }}" x-model="bankRecord" type="checkbox">
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="ml-0">
                                                                <div class="font-medium text-base leading-none text-gray-600">{{ $val[0]}}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ trim($val[6],"'")  }}</div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ $val[8] }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div x-data="orderPayment" class="col-span-12 md:col-span-6">
                        <h1 class="font-semibold text-3xl leading-none text-black mb-6">Order Payments</h1>
                        <div class="flex">
                            <ul class="list-inside font-medium text-lg leading-none text-black mr-14">
                                <li class="mb-3 text-red-600">Unmatched Records : {{ isset($payment) ? count($payment) : 0 }}</li>
                                <li class="mb-3 text-green-600">Matched Records : {{ isset($matchRecordCount) ? $matchRecordCount : 0  }}</li>
                            </ul>
                            <ul class="list-inside font-medium text-lg leading-none text-black">
                                <li class="mb-3 text-red-600">Unaccounted Amount : {{ isset($unaccountedAmountPayment) ? $unaccountedAmountPayment : 0 }}</li>
                                <li class="mb-3 text-black">Total Records : {{ isset($totalRecordPayment) ? $totalRecordPayment : 0 }}</li>
                            </ul>
                        </div>
                        <div class="flex flex-col mt-8">
                            <div class="col-span-12 mb-4">
                                <button x-show="orderPaymentRecord.length > 0 ? true : false;" @click="hideOrderPaymentData" class="font-medium text-md text-white bg-indigo-700 rounded-2xl text-center py-1 px-4" style="display: none;">Ignore</button>
                            </div>
                            <div class="overflow-auto sm:rounded-2xl table-border-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr class="border-none">
                                            <th></th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Date</th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Order Id</th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Transaction Reference</th>
                                            <th scope="col" class="px-5 py-4 font-medium text-lg leading-none text-black text-left">Credit Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 border-none">
                                        @if(isset($payment))
                                            @foreach ($payment as $key => $val)
                                                <tr class="border-none order-payment-record-hide{{ $key }}">
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <input value="{{ $key }}" x-model="orderPaymentRecord" type="checkbox">
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ $val[3] }}</div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ $val[0] }}</div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ $val[1] }}</div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="font-medium text-base leading-none text-gray-600">{{ $val[2] }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
