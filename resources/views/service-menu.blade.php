@props(['services' => collect(), 'garageDetails' => null, 'showHeader' => true, 'showFooter' => true, 'onlyActive' => false])

@php
    use App\Models\GarageDetail;
    use App\Helpers\CurrencyHelper;

    $locale = app()->getLocale();
    $garageDetails = $garageDetails ?? GarageDetail::getDetails();

    // Show all services by default, or filter by active status if requested
    if ($onlyActive) {
        $services = $services->where('is_active', true);
    }

    $services = $services->sortBy('sort_order');
@endphp

<div class="service-menu-invoice" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
    @if($showHeader)
        <!-- Header Section -->
        <div class="service-menu-header">
            <div class="service-menu-header__top">
                <div class="service-menu-header__company">
                    <h1 class="service-menu-header__name">
                        {{ $garageDetails->getNameEn() }}
                    </h1>
                    @if($garageDetails->getNameAr())
                        <h2 class="service-menu-header__name-ar">
                            {{ $garageDetails->getNameAr() }}
                        </h2>
                    @endif
                </div>
            </div>

            <div class="service-menu-header__info">
                @if($garageDetails->mobile)
                    <div class="service-menu-header__info-item">
                        <span class="service-menu-header__label">Mob:</span>
                        <span class="service-menu-header__value">{{ $garageDetails->mobile }}</span>
                    </div>
                @endif

                @if($garageDetails->cr_number)
                    <div class="service-menu-header__info-item">
                        <span class="service-menu-header__label">C.R. No:</span>
                        <span class="service-menu-header__value">{{ $garageDetails->cr_number }}</span>
                    </div>
                @endif

                @if($garageDetails->address)
                    <div class="service-menu-header__info-item">
                        <span class="service-menu-header__value">{{ $garageDetails->address }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Service Table Section -->
    @if($services->isNotEmpty())
        <div class="service-menu-table">
            <table class="service-table">
                <thead>
                    <tr>
                        <th class="service-table__col-no">No.</th>
                        <th class="service-table__col-name">Service Name</th>
                        <th class="service-table__col-name-ar">اسم الخدمة</th>
                        <th class="service-table__col-qty">Qty</th>
                        <th class="service-table__col-qty-ar">الكمية</th>
                        <th class="service-table__col-price">Unit Price</th>
                        <th class="service-table__col-price-ar">سعر الوحدة</th>
                        <th class="service-table__col-amount">Amount</th>
                        <th class="service-table__col-amount-ar">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $index => $service)
                        <tr>
                            <td class="service-table__col-no">{{ $index + 1 }}</td>
                            <td class="service-table__col-name">
                                {{ $service->getTranslation('name', 'en') }}
                            </td>
                            <td class="service-table__col-name-ar">
                                {{ $service->getTranslation('name', 'ar') }}
                            </td>
                            <td class="service-table__col-qty"></td>
                            <td class="service-table__col-qty-ar"></td>
                            <td class="service-table__col-price">
                                {{ CurrencyHelper::format($service->unit_price, false) }}
                            </td>
                            <td class="service-table__col-price-ar"></td>
                            <td class="service-table__col-amount"></td>
                            <td class="service-table__col-amount-ar"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="service-menu-empty">
            <p>No services available.</p>
        </div>
    @endif

    @if($showFooter)
        <!-- Footer Section -->
        <div class="service-menu-footer">
            <div class="service-menu-footer__total">
                <div class="service-menu-footer__total-label">
                    <span>Total Amount {{ CurrencyHelper::symbol() }}</span>
                    <span class="service-menu-footer__total-label-ar">المبلغ الإجمالي ريال قطري</span>
                </div>
                <div class="service-menu-footer__total-line"></div>
            </div>

            <div class="service-menu-footer__notes">
                <div class="service-menu-footer__notes-label">
                    <span>Notes:</span>
                    <span class="service-menu-footer__notes-label-ar">ملاحظات:</span>
                </div>
                <div class="service-menu-footer__notes-line"></div>
            </div>

            <div class="service-menu-footer__signatures">
                <div class="service-menu-footer__signature">
                    <div class="service-menu-footer__signature-label">
                        <span>Authorized Signature</span>
                        <span class="service-menu-footer__signature-label-ar">توقيع معتمد</span>
                    </div>
                    <div class="service-menu-footer__signature-line"></div>
                </div>

                <div class="service-menu-footer__signature">
                    <div class="service-menu-footer__signature-label">
                        <span>Authorized Signature</span>
                        <span class="service-menu-footer__signature-label-ar">توقيع العميل</span>
                    </div>
                    <div class="service-menu-footer__signature-line"></div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.service-menu-invoice {
    font-family: Arial, sans-serif;
    max-width: 210mm;
    margin: 0 auto;
    padding: 20px;
    background: white;
}

.service-menu-header {
    margin-bottom: 20px;
    border-bottom: 2px solid #000;
    padding-bottom: 15px;
}

.service-menu-header__top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.service-menu-header__company {
    flex: 1;
}

.service-menu-header__name {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 5px 0;
    text-align: left;
}

.service-menu-header__name-ar {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
    text-align: right;
    direction: rtl;
}

.service-menu-header__info {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 12px;
}

.service-menu-header__info-item {
    display: flex;
    gap: 5px;
}

.service-menu-header__label {
    font-weight: bold;
}

.service-menu-table {
    margin: 20px 0;
}

.service-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.service-table thead {
    background-color: #f0f0f0;
}

.service-table th {
    border: 1px solid #000;
    padding: 8px 4px;
    text-align: center;
    font-weight: bold;
}

.service-table td {
    border: 1px solid #000;
    padding: 6px 4px;
    text-align: center;
}

.service-table__col-no {
    width: 5%;
}

.service-table__col-name {
    width: 20%;
    text-align: left;
}

.service-table__col-name-ar {
    width: 20%;
    text-align: right;
    direction: rtl;
}

.service-table__col-qty,
.service-table__col-qty-ar {
    width: 5%;
}

.service-table__col-price,
.service-table__col-price-ar {
    width: 8%;
}

.service-table__col-amount,
.service-table__col-amount-ar {
    width: 8%;
}

.service-menu-footer {
    margin-top: 30px;
}

.service-menu-footer__total,
.service-menu-footer__notes {
    margin-bottom: 20px;
}

.service-menu-footer__total-label,
.service-menu-footer__notes-label {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    margin-bottom: 5px;
}

.service-menu-footer__total-label-ar,
.service-menu-footer__notes-label-ar {
    direction: rtl;
}

.service-menu-footer__total-line,
.service-menu-footer__notes-line {
    border-top: 1px solid #000;
    height: 30px;
}

.service-menu-footer__signatures {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
}

.service-menu-footer__signature {
    width: 45%;
}

.service-menu-footer__signature-label {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 12px;
}

.service-menu-footer__signature-label-ar {
    direction: rtl;
}

.service-menu-footer__signature-line {
    border-top: 1px solid #000;
    height: 50px;
}

.service-menu-empty {
    text-align: center;
    padding: 40px;
    color: #666;
}

@media print {
    .service-menu-invoice {
        padding: 0;
    }
}
</style>

