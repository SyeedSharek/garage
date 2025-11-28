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
            <div class="service-menu-header__fields">
                <div class="service-menu-header__field">
                    <label class="service-menu-header__label">Car Number:</label>
                    <label class="service-menu-header__label-ar">رقم السيارة:</label>
                    <div class="service-menu-header__input-line"></div>
                </div>
                <div class="service-menu-header__field">
                    <label class="service-menu-header__label">Date:</label>
                    <label class="service-menu-header__label-ar">التاريخ:</label>
                    <div class="service-menu-header__input-line"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Service Table Section -->
    @if($services->isNotEmpty())
        <div class="service-menu-table">
            <table class="service-table">
                <thead>
                    <tr>
                        <th class="service-table__col-no">No. / رقم</th>
                        <th class="service-table__col-name">Service Name</th>
                        <th class="service-table__col-name-ar">اسم الخدمة</th>
                        <th class="service-table__col-qty">Qty / الكمية</th>
                        <th class="service-table__col-price">Unit Price / سعر الوحدة</th>
                        <th class="service-table__col-amount">Amount / المبلغ</th>
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
                            <td class="service-table__col-price"></td>
                            <td class="service-table__col-amount"></td>
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
            <div class="service-menu-footer__content">
                <div class="service-menu-footer__notes">
                    <div class="service-menu-footer__notes-label">
                        <span>Notes:</span>
                        <span class="service-menu-footer__notes-label-ar">ملاحظات:</span>
                    </div>
                    <div class="service-menu-footer__notes-line"></div>
                </div>

                <div class="service-menu-footer__total">
                    <div class="service-menu-footer__total-label">
                        <span>Total Amount {{ CurrencyHelper::symbol() }}</span>
                        <span class="service-menu-footer__total-label-ar">المبلغ الإجمالي ريال قطري</span>
                    </div>
                    <div class="service-menu-footer__total-line"></div>
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
    padding-bottom: 15px;
}

.service-menu-header__fields {
    display: flex;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 20px;
}

.service-menu-header__field {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.service-menu-header__label {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 5px;
    text-align: left;
}

.service-menu-header__label-ar {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 5px;
    text-align: right;
    direction: rtl;
}

.service-menu-header__input-line {
    border-bottom: 1px solid #000;
    height: 25px;
    width: 100%;
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
    width: 6%;
    text-align: center;
}

.service-table__col-name {
    width: 22%;
    text-align: left;
}

.service-table__col-name-ar {
    width: 22%;
    text-align: right;
    direction: rtl;
}

.service-table__col-qty {
    width: 8%;
    text-align: center;
}

.service-table__col-price {
    width: 12%;
    text-align: center;
}

.service-table__col-amount {
    width: 12%;
    text-align: center;
}

.service-menu-footer {
    margin-top: 30px;
}

.service-menu-footer__content {
    display: flex;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 20px;
}

.service-menu-footer__notes {
    flex: 1;
}

.service-menu-footer__total {
    flex: 1;
}

.service-menu-footer__total-label,
.service-menu-footer__notes-label {
    display: flex;
    flex-direction: column;
    gap: 3px;
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 12px;
}

.service-menu-footer__total-label-ar,
.service-menu-footer__notes-label-ar {
    direction: rtl;
    text-align: right;
}

.service-menu-footer__total-line,
.service-menu-footer__notes-line {
    border-top: 1px solid #000;
    height: 30px;
    width: 100%;
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

