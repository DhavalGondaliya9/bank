
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
        <script src="js/jquery.slim.min.js"></script>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <section class="main my-100">
            <div class="container mx-auto px-4">
                <div class="title-subtitle-part">
                    <h1 class="mb-6 font-semibold text-4xl leading-none text-center text-black">Bank Reconciliation Tool</h1>
                    <h2 class="mb-4 font-normal text-lg leading-7 text-center text-gray-500">Are you tired of losing money from unaccounted online payments?</h2>
                    <h2 class="mb-4 font-normal text-lg leading-7 text-center text-gray-500">
                        Businesses like yours have thousands of online transactions every day. POS systems cannot track all of them. Who checks whether the POS revenue number matches what the bank statement says?
                    </h2>
                    <h2 class="mb-4 font-normal text-lg leading-7 text-center text-gray-500">Getting people to reconcile manually can be time-consuming, prone to errors, and a huge waste of resources. There's a better way...</h2>
                    <h2 class="mb-4 font-normal text-lg leading-7 text-center text-gray-500">
                        Try this tool instead. It offers a hassle-free solution for your business. Simply upload your bank account passbook, and our tool will compare the entries with your records in POS, identifying any discrepancies that need
                        attention.
                    </h2>
                    <h2 class="mb-10 font-normal text-lg leading-7 text-center text-gray-500">
                        Say goodbye to the tedious process of manual reconciliation and hello to accurate and efficient record keeping. Try our tool today and start saving time and money!
                    </h2>
                </div>
                <form action="{{ route('store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-24">
                        <div class="mx-auto 2xl:mx-0 xl:mx-0 lg:mx-0 md:mx-auto sm:mx-auto">
                            <div class="flex float-none 2xl:float-right xl:float-right lg:float-right bank-csv relative">
                                <input class="custom-file-label font-normal text-lg leading-none text-black h-14 border rounded-lg pr-14 py-2 px-5 mr-2 focus:outline-none focus:shadow-outline placeholder-black px-2 w-48 2xl:w-64 xl:w-60 lg:w-60 md:w-60 sm:w-60" id="BankCSV" type="text" placeholder="Bank CSV" />
                                <div class="relative bg-gray-200 font-medium text-lg leading-7 text-center text-black rounded-lg h-14 flex justify-center items-center text-center px-2 2xl:px-10 xl:px-10 lg:px-5 md:px-3 sm:px-2">
                                    Browse<input class="absolute right-0 top-0 opacity-0 overflow-hidden h-14 custom-file-input" id="file" type="file" name="bank" />
                                </div>
                            </div>
                            @error('bank')
                            <div class="flex float-none 2xl:float-right xl:float-right lg:float-right">
                                <div class="mt-5 text-red-700">
                                    <span>{{ $message }}</span>
                                </div>
                            </div>
                            @enderror

                        </div>
                        <div class="mx-auto 2xl:mx-0 xl:mx-0 lg:mx-0 md:mx-auto sm:mx-auto">
                            <div class="flex float-none 2xl:float-left xl:float-left lg:float-left">
                                <input class="custom-file-label font-normal text-lg leading-none text-black h-14 border rounded-lg pr-14 py-2 px-5 mr-2 focus:outline-none focus:shadow-outline placeholder-black px-2 w-48 2xl:w-64 xl:w-60 lg:w-60 md:w-60 sm:w-60" id="OrderPaymentCSV" type="text" placeholder="Order Payment CSV" />
                                <div class="relative bg-gray-200 font-medium text-lg leading-7 text-center text-black rounded-lg h-14 flex justify-center items-center text-center px-2 2xl:px-10 xl:px-10 lg:px-5 md:px-3 sm:px-2">
                                    Browse<input class="absolute right-0 top-0 opacity-0 overflow-hidden h-14 custom-file-input" id="file" type="file" name="order_payment" />
                                </div>
                            </div>
                            @error('order_payment')
                            <div class="flex float-none 2xl:float-left xl:float-left lg:float-left">
                                <div class="mt-5 text-red-700">
                                    <span>{{ $message }}</span>
                                </div>
                            </div>
                            @enderror
                        </div>
                    </div>
                    @error('error')
                        <h1 class="mt-6 font-semibold text-xl leading-none text-center text-red-700">{{ $message }}</h1>
                    @enderror
                    <div class="w-full text-center mt-16">
                        <button class="h-14 py-4 px-10 bg-indigo-700 not-italic font-medium text-lg leading-none rounded-lg text-center text-white focus:outline-none focus:shadow-outline text-center">Submit</button>
                    </div>
                </form>
            </div>
        </section>
        <script>
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).parent().parent().find(".custom-file-label").attr('placeholder',fileName);
            });
        </script>
    </body>
</html>
