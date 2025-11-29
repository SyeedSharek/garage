<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al-Hilal Automotive Services</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header-left {
            text-align: left;
            flex: 1;
        }
        .header-middle {
            text-align: center;
            flex: 2;
        }
        .header-right {
            text-align: right;
            flex: 1;
        }
        .header-middle h1, .header-middle h2, .header-middle h3 {
            margin: 5px 0;
        }
        .company-info {
            margin-bottom: 15px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .service-table th,
        .service-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .service-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .service-name {
            text-align: right;
            width: 40%;
        }
        .footer {
            margin-top: 30px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .total-section {
            text-align: right;
            margin-bottom: 20px;
        }
        .signature-section {
            text-align: right;
            margin-top: 40px;
        }
        .bilingual {
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
        }
        .arabic {
            text-align: right;
            flex: 1;
        }
        .english {
            text-align: left;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="header">
            <div class="header-left">
                <p>جوال : ٥٠۱٩٧٢٤٨</p>
                <p>ست : ۱۹۹۹۸۹</p>
                <p>الهلال نعيجة</p>
                <p>الدوحة - قطر</p>
            </div>
            <div class="header-middle">
                <h1>شهباز لخدمات السيارات والخدمات</h1>
                <h2>SHAHBAZ AUTO SERVICE & TRADING</h2>
                <h3>فاتورة نقدا على / الحساب Invoice/Credit/Cash</h3>
            </div>
            <div class="header-right">
                <p>Mob: 50197248</p>
                <p>C.R. No: 199989</p>
                <p>Al Hilal, Nuaija</p>
                <p>DOHA-QATAR</p>
                <p>No: 0444</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bilingual">
                <span class="">____________<strong>:Date</strong> </span>
                <span class="arabic"><strong>التاريخ:</strong> </span>
            </div>
            <div class="bilingual">
                <span class="">____________<strong>: Car Number </strong> </span>
                <span class="arabic"><strong>رقم السيارة:</strong> </span>
            </div>
        </div>

        <!-- Services Table -->
        <table class="service-table">
            <thead>
                <tr>
                    <th>المبلغ / Amount</th>
                    <th>سعر الوحدة / Unit Price</th>
                    <th>الكمية / Qty.</th>
                    <th class="service-name">اسم الخدمة / Service Name</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="service-name">
                        <div class="arabic">{{ $service['name_ar'] }}</div>
                        <div class="english">{{ $service['name_en'] }}</div>
                    </td>
                    <td>{{ $service['id'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <div class="total-section">
                <div class="bilingual">
                    <span class="arabic"><strong>المبلغ الإجمالي بالريال:</strong> ______</span>
                    <span class="english"><strong>Total Amount QR:</strong> ______</span>
                </div>
            </div>

            <div class="signature-section">
                <div class="bilingual">
                    <span class="arabic"><strong>توقيع المسؤول:</strong> ________________</span>
                    <span class="english"><strong>Authorized Signature:</strong> ________________</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
