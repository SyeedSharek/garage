<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $data['invoice_no'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap');

        :root {
            --primary-color: #0d1b41;
        }

        body {
            font-family: 'Arial', sans-serif; /* Arimo is close to Arial/Helvetica used in printers */
            font-size: 14px;
        }

        /* Make borders more visible */
        table,
        table td,
        table th {
            border-style: solid;
        }

        @media print {
            body {
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 12px;
            }
            .no-print { display: none; }
            @page { margin: 0; size: A4; }

            /* Adjust font sizes for print */
            .text-xs { font-size: 0.65rem !important; }
            .text-sm { font-size: 0.75rem !important; }
            .text-base { font-size: 0.875rem !important; }
            .text-lg { font-size: 1rem !important; }

            /* Ensure table text is readable */
            table {
                font-size: 0.7rem;
            }
            table th {
                font-size: 0.65rem;
            }
            table td {
                font-size: 0.7rem;
            }
        }


    </style>
</head>
<body>

    <div class="relative bg-white text-black p-[10mm] overflow-hidden text-sm leading-tight rounded-md">

        <div class="absolute inset-0 z-0 flex items-center justify-center opacity-20 pointer-events-none select-none overflow-hidden">
             <img src="{{ asset('assets/invoice-watermark.png') }}"
                  alt="Watermark"
                  class="w-full h-full object-contain"
                  style="max-width: 100%; max-height: 100%;">
        </div>

        <div class="relative z-10 h-full flex flex-col justify-between">

            <div>
                <div class="flex justify-between items-start mb-1">

                    <div class="w-[28%]  text-md text-gray-700 space-y-[1px] leading-tight">
                        <p>Mob: 50197248 </p>
                        <p>C.R. No: 199989</p>
                        <p>Al Hilal, Nuaija</p>
                        <p>DOHA-QATAR</p>


                    </div>
{{--
                    <div class="w-[44%] ">
                        <div class="flex justify-center items-center mb-2 ">
                            <img src="{{ asset('assets/full logo and name.png') }}" alt="Logo with Company Name" class="w-full h-auto object-contain max-h-36 mx-auto">
                        </div>
                    </div> --}}


                    <div class="w-[44%] ">
                        <div class=" mb-2 text-md font-bold text-center">
                            <p class="mb-1">شهباز لخدمات السيارات والخدمات</p>
                            <p class="mb-1">SHAHBAZ AUTO SERVICE & TRADING</p>
                            <p>Cash/Credit Invoice    فاتورة نقدا على / الحساب  </p>
                        </div>
                    </div>

                    <div class="w-[28%] text-xs text-right text-gray-700 space-y-[1px] leading-tight" dir="rtl">
                        <p>جوال :  ٥٠١٩٧٢٤٨</p>
                        <p> ست :  ۱۹۹۹۸۹</p>
                        <p>الهلال ,   نعيجة</p>
                        <p>الدوحة - قطر</p>
                    </div>
                </div>

                <h2 class="text-center text-lg font-bold underline decoration-1 underline-offset-2 mb-2 mt-4">Cash Invoice</h2>

                <!-- Invoice Table Container with Border Radius -->
                <div class="border-2 border-blue-700 rounded-md overflow-hidden mb-4">
                    <table class="w-full border-collapse">
                        <!-- Customer Info Section -->
                        <thead>
                            <tr>
                                <td colspan="6" class="p-0">
                                    <table class="w-full border-collapse">
                                        <tr>
                                            <td class="w-[65%] border-r border-b border-blue-700 p-0">
                                                <div class="flex flex-col">
                                                    <div class="flex justify-between bg-gray-100 border-b border-blue-700 px-2 py-1 font-bold text-xs">
                                                        <span>Customer Name & Address</span>
                                                        <span dir="rtl">اسم وعنوان العميل</span>
                                                    </div>
                                                    <div class="flex-grow flex items-center px-4 py-3 font-bold text-sm uppercase">
                                                        {{ $data['customer_name'] }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="w-[35%] border-b border-blue-700 p-0">
                                                <table class="w-full border-collapse">
                                                    <tr>
                                                        <td class="w-1/2 border-r border-b border-blue-700 p-0">
                                                            <div class="bg-gray-100 border-b border-blue-700 text-center py-1 font-bold text-xs">
                                                                Document No. <span dir="rtl">رقم الوثيقة</span>
                                                            </div>
                                                            <div class="flex items-center justify-center py-3 font-bold text-red-600 text-sm">
                                                                {{ $data['invoice_no'] }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/2 border-b border-blue-700 p-0">
                                                            <div class="bg-gray-100 border-b border-blue-700 text-center py-1 font-bold text-xs">
                                                                Date <span dir="rtl">التاريخ</span>
                                                            </div>
                                                            <div class="flex items-center justify-center py-3 font-bold text-xs">
                                                                {{ $data['date'] }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="border-b border-blue-700 px-2 py-1 bg-gray-50 text-xs">
                                                <span class="font-bold mr-4">Salesman:</span>
                                                <span class="uppercase font-bold">{{ $data['salesman'] }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </thead>

                        <!-- Items Table Header -->
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-blue-700 w-[5%] text-center font-bold py-2">
                                    <div class="flex flex-col text-[10px] leading-tight">
                                        <span>SL. No.</span>
                                        <span dir="rtl">رقم مسلسل</span>
                                    </div>
                                </th>
                                <th class="border border-blue-700 w-[55%] text-left px-2 font-bold py-2">
                                    <div class="flex justify-between items-center">
                                        <span>DESCRIPTION</span>
                                        <span dir="rtl">التفاصيل</span>
                                    </div>
                                </th>
                                <th class="border border-blue-700 w-[8%] text-center font-bold py-2">
                                    <div class="flex flex-col text-[10px] leading-tight">
                                        <span>QTY.</span>
                                        <span dir="rtl">الكمية</span>
                                    </div>
                                </th>
                                <th class="border border-blue-700 w-[8%] text-center font-bold py-2">
                                    <div class="flex flex-col text-[10px] leading-tight">
                                        <span>UNIT</span>
                                        <span dir="rtl">وحدة</span>
                                    </div>
                                </th>
                                <th class="border border-blue-700 w-[10%] text-center font-bold py-2">
                                    <div class="flex flex-col text-[10px] leading-tight">
                                        <span>UNIT PRICE</span>
                                        <span dir="rtl">سعر الوحدة</span>
                                    </div>
                                </th>
                                <th class="border border-blue-700 w-[14%] text-center font-bold py-2">
                                    <div class="flex flex-col text-[10px] leading-tight">
                                        <span>TOTAL AMOUNT</span>
                                        <span dir="rtl">الأجمالي</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <!-- Items Table Body -->
                        <tbody>
                            @foreach($data['items'] as $item)
                            <tr class="h-8">
                                <td class="border-r border-blue-700 text-center align-middle">{{ $item['sl'] }}</td>
                                <td class="border-r border-blue-700 px-2 align-middle font-bold text-xs">{{ $item['desc'] }}</td>
                                <td class="border-r border-blue-700 text-center align-middle font-bold">{{ $item['qty'] }}</td>
                                <td class="border-r border-blue-700 text-center align-middle">{{ $item['unit'] }}</td>
                                <td class="border-r border-blue-700 text-center align-middle">{{ number_format($item['price'], 2) }}</td>
                                <td class="border-blue-700 text-center align-middle bg-gray-50">{{ number_format($item['total'], 2) }}</td>
                            </tr>
                            @endforeach

                            @foreach($data['empty_rows'] as $row)
                            <tr class="h-8">
                                <td class="border-r border-blue-700"></td>
                                <td class="border-r border-blue-700"></td>
                                <td class="border-r border-blue-700"></td>
                                <td class="border-r border-blue-700"></td>
                                <td class="border-r border-blue-700"></td>
                                <td class="border-blue-700 bg-gray-50"></td>
                            </tr>
                            @endforeach
                        </tbody>

                        <!-- Totals Section -->
                        <tfoot>
                            <tr>
                                <td colspan="6" class="p-0 border-t border-blue-700">
                                    <table class="w-full border-collapse">
                                        <tr>
                                            <td class="w-[70%] border-r border-blue-700 p-2 h-28 align-bottom"></td>
                                            <td class="w-[30%] border-blue-700 p-0 align-top">
                                                <table class="w-full border-collapse">
                                                    <tr class="h-9 border-b border-blue-700">
                                                        <td class="w-[40%] border-r border-blue-700 bg-gray-50 font-bold text-[10px] text-center align-middle">
                                                            <div class="flex flex-col leading-tight">
                                                                <span>TOTAL</span>
                                                                <span dir="rtl">الأجمالي</span>
                                                            </div>
                                                        </td>
                                                        <td class="w-[60%] text-center font-bold align-middle">
                                                            {{ number_format($data['total'], 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr class="h-9 border-b border-blue-700">
                                                        <td class="w-[40%] border-r border-blue-700 bg-gray-50 font-bold text-[10px] text-center align-middle">
                                                            <div class="flex flex-col leading-tight">
                                                                <span>DISCOUNT</span>
                                                                <span dir="rtl">خصم</span>
                                                            </div>
                                                        </td>
                                                        <td class="w-[60%] text-center align-middle">
                                                        </td>
                                                    </tr>
                                                    <tr class="h-10">
                                                        <td class="w-[40%] border-r border-blue-700 bg-gray-50 font-bold text-[10px] text-center align-middle">
                                                            <div class="flex flex-col leading-tight">
                                                                <span>GRAND TOTAL</span>
                                                                <span dir="rtl">المبلغ الأجمالي</span>
                                                            </div>
                                                        </td>
                                                        <td class="w-[60%] text-center font-bold text-sm align-middle">
                                                            {{ number_format($data['grand_total'], 3) }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signature Section -->
                <div class="border-2 border-blue-700 rounded-md overflow-hidden mb-4">
                    <table class="w-full border-collapse">
                        <tbody>
                            <tr>
                                <td class="w-1/2 border-r border-blue-700 p-2 h-24 align-bottom">
                                    <div class="flex items-end">
                                        <span class="mr-2 text-xs">Receiver's Sign :</span>
                                        <span class="border-b border-dotted border-blue-700 w-40 inline-block"></span>
                                        <span class="ml-2 text-xs" dir="rtl">علامة المتلقي</span>
                                    </div>
                                </td>
                                <td class="w-1/2 border-blue-700 p-2 h-24 align-bottom text-right">
                                    <p class="mb-8 text-xs">Authorized Sign</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <div class="mt-2 flex justify-between items-center px-4 gap-2 grayscale opacity-60">
                @for($i = 1; $i <= 12; $i++)
                    <img src="{{ asset('assets/car-logos/car-' . $i . '.png') }}"
                         alt="Car Logo {{ $i }}"
                         class="h-8 w-auto object-contain flex-1"
                         onerror="this.style.display='none'">
                @endfor
            </div>

        </div>
    </div>

    <div class="fixed bottom-8 right-8 no-print z-20">
        <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-full shadow-lg flex items-center gap-2 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print
        </button>
    </div>

</body>
</html>
