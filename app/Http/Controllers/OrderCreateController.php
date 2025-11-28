<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Customer;
use App\Enums\OrderStatus;
use App\Enums\OrderPaymentType;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderCreateController extends Controller
{
    /**
     * Show the form for creating a new order.
     */
    public function create(): Response
    {
        $services = Service::active()
            ->ordered()
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'code' => $service->code,
                    'name' => [
                        'en' => $service->getNameEn(),
                        'ar' => $service->getNameAr(),
                    ],
                    'unit_price' => $service->unit_price,
                    'unit' => $service->unit,
                    'formatted_price' => $service->formatted_price,
                ];
            });

        return Inertia::render('Orders/Create', [
            'services' => $services,
        ]);
    }

    /**
     * Show customer selection page for order creation.
     */
    public function createDetails(Request $request): Response
    {
        $query = Customer::active();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'address' => $customer->address,
                    'city' => $customer->city,
                    'type' => $customer->type,
                    'company_name' => $customer->company_name,
                ];
            });

        return Inertia::render('Orders/CreateDetails', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the price form for creating an order.
     */
    public function createPrices(): Response
    {
        return Inertia::render('Orders/CreatePrices');
    }

    /**
     * Show the order preview page.
     */
    public function createPreview(): Response
    {
        return Inertia::render('Orders/CreatePreview');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Prepare order data
        $orderData = [
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'order_date' => $validated['order_date'] ?? now(),
            'status' => $validated['status'] ?? OrderStatus::DRAFT,
        ];

        // Pre-fetch all services in one query
        $serviceIds = array_column($validated['items'], 'service_id');
        $services = Service::whereIn('id', $serviceIds)
            ->select('id', 'name', 'unit', 'unit_price')
            ->get()
            ->keyBy('id');

        // Prepare order items data
        $orderItemsData = [];
        $now = now();
        $subtotal = 0;

        foreach ($validated['items'] as $index => $item) {
            $service = $services->get($item['service_id']);
            $amount = $item['quantity'] * $item['unit_price'];
            $subtotal += $amount;

            // Get service name as JSON (simplified)
            $description = $service
                ? json_encode([
                    'en' => $service->getTranslation('name', 'en', false) ?: '',
                    'ar' => $service->getTranslation('name', 'ar', false) ?: '',
                ])
                : json_encode(['en' => '', 'ar' => '']);

            $orderItemsData[] = [
                'order_id' => 0, // Will be set after order creation
                'service_id' => $item['service_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $amount,
                'sort_order' => $index + 1,
                'unit' => $service->unit ?? 'pcs',
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Calculate amounts
        $subtotal = $validated['subtotal'] ?? $subtotal;
        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $total = $validated['total'] ?? ($subtotal - $discount + $tax);

        // Prepare payments data
        $paymentsData = [];
        $userId = auth()->id();
        $paymentDate = $now;

        if ($subtotal > 0) {
            $paymentsData[] = [
                'order_id' => 0, // Will be set after order creation
                'payment_type' => OrderPaymentType::SUBTOTAL->value,
                'amount' => $subtotal,
                'notes' => 'Order subtotal',
                'payment_date' => $paymentDate,
                'user_id' => $userId,
                'is_paid' => false,
                'is_payable' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($discount > 0) {
            $paymentsData[] = [
                'order_id' => 0,
                'payment_type' => OrderPaymentType::DISCOUNT->value,
                'amount' => $discount,
                'notes' => 'Order discount',
                'payment_date' => $paymentDate,
                'user_id' => $userId,
                'is_paid' => false,
                'is_payable' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($tax > 0) {
            $paymentsData[] = [
                'order_id' => 0,
                'payment_type' => OrderPaymentType::TAX->value,
                'amount' => $tax,
                'notes' => 'Order tax',
                'payment_date' => $paymentDate,
                'user_id' => $userId,
                'is_paid' => false,
                'is_payable' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($total > 0) {
            $paymentsData[] = [
                'order_id' => 0,
                'payment_type' => OrderPaymentType::TOTAL->value,
                'amount' => $total,
                'notes' => 'Order total',
                'payment_date' => $paymentDate,
                'user_id' => $userId,
                'is_paid' => false,
                'is_payable' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Prepare invoice data
        $invoiceData = [
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'payment_gateway' => PaymentGateway::CASH,
            'total_amount' => $total,
            'paid_amount' => 0,
            'invoice_date' => $now,
            'status' => PaymentStatus::PENDING,
        ];

        // Generate order number manually to avoid trait loop
        $orderNumber = $this->generateOrderNumber();

        // Use optimized model method
        $order = DB::transaction(function () use ($orderData, $orderItemsData, $paymentsData, $invoiceData, $orderNumber) {
            // Create order with pre-generated number to avoid GeneratesUniqueNumber loop
            $order = Order::withoutEvents(function () use ($orderData, $orderNumber) {
                $orderData['order_number'] = $orderNumber;
                return Order::create($orderData);
            });

            // Set order_id for items and payments
            $orderId = $order->id;
            foreach ($orderItemsData as &$item) {
                $item['order_id'] = $orderId;
            }
            unset($item);

            foreach ($paymentsData as &$payment) {
                $payment['order_id'] = $orderId;
            }
            unset($payment);

            // Bulk insert items
            if (!empty($orderItemsData)) {
                DB::table('order_items')->insert($orderItemsData);
            }

            // Bulk insert payments
            if (!empty($paymentsData)) {
                DB::table('order_payments')->insert($paymentsData);
            }

            // Generate invoice number manually
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create invoice with pre-generated number
            $invoice = Invoice::withoutEvents(function () use ($invoiceData, $orderId, $invoiceNumber) {
                $invoiceData['invoice_number'] = $invoiceNumber;
                return Invoice::create(array_merge($invoiceData, [
                    'order_id' => $orderId,
                ]));
            });

            // Link payments to invoice
            DB::table('order_payments')
                ->where('order_id', $orderId)
                ->update(['invoice_id' => $invoice->id]);

            return $order;
        });

        // Get the invoice ID to redirect to payment page
        $invoice = $order->invoices()->first();

        if ($invoice) {
            // Redirect directly to payment page by invoice ID
            return redirect()->route('invoices.payment', $invoice->id)
                ->with('success', 'Order created successfully.');
        }

        // Fallback to order show page if no invoice
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Generate a unique order number
     */
    private function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $year = date('Y');
        $month = date('m');
        $column = 'order_number';
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $random = str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $number = $prefix . $year . $month . $random;

            // Check if number exists using DB facade for speed
            $exists = DB::table('orders')
                ->where($column, $number)
                ->exists();

            if (!$exists) {
                return $number;
            }
        }

        // Fallback: use timestamp-based number
        return $prefix . $year . $month . str_pad((string) time(), 10, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        $column = 'invoice_number';
        $maxAttempts = 10;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $random = str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $number = $prefix . $year . $month . $random;

            // Check if number exists using DB facade for speed
            $exists = DB::table('invoices')
                ->where($column, $number)
                ->exists();

            if (!$exists) {
                return $number;
            }
        }

        // Fallback: use timestamp-based number
        return $prefix . $year . $month . str_pad((string) time(), 10, '0', STR_PAD_LEFT);
    }
}

