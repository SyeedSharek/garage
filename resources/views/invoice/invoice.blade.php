<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shahbaz Auto Service Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 10px;
            color: #333;
            font-size: 11px;
        }

        .invoice-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background-color: white;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header-left {
            text-align: right;
            direction: rtl;
            flex: 1;
        }

        .header-left p {
            margin-bottom: 2px;
            font-size: 10px;
            line-height: 1.2;
        }

        .header-middle {
            flex: 2;
            text-align: center;
        }

        .header-middle h1 {
            font-size: 14px;
            margin-bottom: 3px;
            color: #2c3e50;
            direction: rtl;
            line-height: 1.2;
        }

        .header-middle h2 {
            font-size: 12px;
            margin-bottom: 3px;
            color: #2c3e50;
            line-height: 1.2;
        }

        .header-middle h3 {
            font-size: 10px;
            color: #2c3e50;
            font-weight: normal;
            line-height: 1.2;
        }

        .header-right {
            flex: 1;
            text-align: left;
        }

        .header-right p {
            margin-bottom: 2px;
            font-size: 10px;
            line-height: 1.2;
        }

        .customer-section {
            margin: 15px 0;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 2px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #2c3e50;
            text-align: center;
        }

        .bilingual-title {
            display: flex;
            justify-content: space-between;
        }

        .bilingual-title span:last-child {
            direction: rtl;
        }

        .customer-details {
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .customer-info {
            flex: 1;
        }

        .customer-info p {
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .invoice-info {
            flex: 1;
            text-align: right;
        }

        .invoice-info p {
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            table-layout: fixed;
            font-size: 9px;
        }

        .invoice-table th {
            background-color: #2c3e50;
            color: white;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #ddd;
            font-weight: 600;
            line-height: 1.1;
        }

        .invoice-table td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
            line-height: 1.2;
            vertical-align: middle;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .bilingual-header {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .bilingual-header .english {
            font-weight: 600;
            font-size: 8px;
        }

        .bilingual-header .arabic {
            font-weight: 600;
            direction: rtl;
            font-size: 8px;
        }

        .footer {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }

        .service-info {
            flex: 1;
        }

        .service-info p {
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .signature-area {
            flex: 1;
            text-align: right;
        }

        .bilingual-text {
            display: flex;
            justify-content: space-between;
            margin-top: 6px;
            font-size: 9px;
        }

        .bilingual-text span:last-child {
            direction: rtl;
        }

        .signature-line {
            margin-top: 25px;
            width: 180px;
            border-top: 1px solid #333;
            margin-left: auto;
            text-align: center;
            padding-top: 3px;
            font-size: 9px;
        }

        .total-row {
            font-weight: 700;
            background-color: #e8f4fc !important;
        }

        .print-btn {
            display: block;
            margin: 15px auto 0;
            padding: 8px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: #1a252f;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                background-color: white;
                padding: 0;
                font-size: 11px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-container {
                box-shadow: none;
                padding: 0;
                width: 100%;
                min-height: auto;
                margin: 0;
            }

            .print-btn {
                display: none;
            }

            .invoice-table {
                page-break-inside: avoid;
            }

            .header {
                page-break-after: avoid;
            }

            .customer-section {
                page-break-after: avoid;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .header-left, .header-right {
                text-align: center;
                margin-bottom: 15px;
            }

            .header-left {
                direction: ltr;
                flex-direction: column;
            }

            .customer-details {
                flex-direction: column;
            }

            .invoice-info {
                text-align: left;
                margin-top: 15px;
            }

            .footer {
                flex-direction: column;
            }

            .signature-area {
                text-align: left;
                margin-top: 15px;
            }

            .signature-line {
                margin-left: 0;
            }

            .invoice-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="content-area">
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

        <div class="customer-section">
            <div class="section-title">
                <div class="bilingual-title">
                    <span>Customer Name & Address</span>
                    <span>اسم وعنوان العميل</span>
                </div>
            </div>
            <div class="customer-details">
                <div class="customer-info">
                    <p><strong>Customer:</strong> SHAHBAZ AUTO HILAL</p>
                    <p><strong>Address:</strong> Industrial Area, Street 45, Doha</p>
                </div>
                <div class="invoice-info">
                    <p><strong>Invoice No:</strong> INV-2023-0444</p>
                    <p><strong>Date:</strong> 15 November 2023</p>
                </div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th width="6%">
                        <div class="bilingual-header">
                            <div class="english">SL. No.</div>
                            <div class="arabic">التسلسل</div>
                        </div>
                    </th>
                    <th width="45%">
                        <div class="bilingual-header">
                            <div class="english">DESCRIPTION</div>
                            <div class="arabic">الوصف</div>
                        </div>
                    </th>
                    <th width="7%">
                        <div class="bilingual-header">
                            <div class="english">QTY.</div>
                            <div class="arabic">الكمية</div>
                        </div>
                    </th>
                    <th width="7%">
                        <div class="bilingual-header">
                            <div class="english">UNIT</div>
                            <div class="arabic">وحدة</div>
                        </div>
                    </th>
                    <th width="15%">
                        <div class="bilingual-header">
                            <div class="english">UNIT PRICE</div>
                            <div class="arabic">سعر الوحدة</div>
                        </div>
                    </th>
                    <th width="15%">

                        <div class="bilingual-header">
                            <div class="english">TOTAL AMOUNT</div>
                            <div class="arabic">المبلغ الإجمالي</div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>MN: 25871/MRLANCERQTUP LOWER SMALL BUSH (MR4034K)</td>
                    <td>2</td>
                    <td>PCS</td>
                    <td>50.00</td>
                    <td>100.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>MN: 84133-TABLANCERQTUP COSTER BUSH (MR-4034F)</td>
                    <td>2</td>
                    <td>PCS</td>
                    <td>30.00</td>
                    <td>60.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>BUSH FIXING BUSH & LATHE SERVICE CHARGES</td>
                    <td>4</td>
                    <td>PCS</td>
                    <td>10.00</td>
                    <td>40.00</td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="total-section" style="margin-top: auto; padding: 10px 0; border-top: 2px solid #333; margin-bottom: 10px;">
            <div style="text-align: right; font-size: 12px; font-weight: bold ;">
                <span>Sub Total: QR 200.00</span>
            </div>
            <div style="text-align: right; font-size: 12px; font-weight: bold mb-1;">
                <span>Discount: QR 200.00</span>
            </div>
            <div style="text-align: right; font-size: 12px; font-weight: bold   ;">
                <span>Total Amount: QR 200.00</span>
            </div>
        </div>

        <div class="footer">
            <div class="service-info">
                <p><strong>Autohub Cars Service And Bush Fixing, Shop No. 9.</strong></p>
                <p>Bush Fixing & Car Service Available</p>
                <p>Thank you for your business!</p>
            </div>

            <div class="signature-area">
                <div class="bilingual-text">
                    <span>Receiver's Sign :</span>
                    <span>توقيع المستلم</span>
                </div>
                <div class="bilingual-text">
                    <span>Date</span>
                    <span>التاريخ</span>
                </div>
                <div class="signature-line">Signature</div>
            </div>
        </div>

        <button class="print-btn" onclick="window.print()">Print Invoice</button>
    </div>

    <script>
        // Add current date to the invoice
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = today.toLocaleDateString('en-US', options);

            // Update the date in the invoice info
            document.querySelector('.invoice-info p:nth-child(2)').innerHTML = `<strong>Date:</strong> ${dateString}`;
        });
    </script>
</body>
</html>
