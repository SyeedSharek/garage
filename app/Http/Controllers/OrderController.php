<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): Response
    {
        $query = Order::with(['customer', 'user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        $orders = $query->paginate(15);

        // Transform orders data for frontend
        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer?->name,
                'customer_phone' => $order->customer?->phone,
                'status' => $order->status->value,
                'status_label' => $order->status->getLabel(),
                'order_date' => $order->order_date?->format('Y-m-d H:i:s'),
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total_amount' => $order->total_amount,
                'formatted_total_amount' => $order->formatted_total_amount,
                'items_count' => $order->items()->count(),
                'created_at' => $order->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status', 'customer_id']),
            'statusOptions' => array_map(fn($case) => [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ], OrderStatus::cases()),
        ]);
    }

    /**
     * Show payment selection page for the order.
     */
    public function payment(Order $order): Response
    {
        $order->load(['customer', 'items.service', 'invoices']);

        $paymentTypes = PaymentType::cases();

        return Inertia::render('Orders/Payment', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer' => $order->customer ? [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'email' => $order->customer->email,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'service_name' => $item->description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'amount' => $item->amount,
                        'unit' => $item->unit,
                    ];
                }),
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total_amount' => $order->total_amount,
                'formatted_total_amount' => $order->formatted_total_amount,
                'invoice' => $order->invoices->first() ? [
                    'id' => $order->invoices->first()->id,
                    'invoice_number' => $order->invoices->first()->invoice_number,
                    'total_amount' => $order->invoices->first()->total_amount,
                    'paid_amount' => $order->invoices->first()->paid_amount,
                    'status' => $order->invoices->first()->status->value,
                ] : null,
            ],
            'paymentTypes' => array_map(fn($type) => [
                'value' => $type->value,
                'label' => $type->getLabel(),
            ], $paymentTypes),
        ]);
    }

    /**
     * Show payment selection page by invoice ID.
     */
    public function paymentByInvoice(Invoice $invoice): Response
    {
        $invoice->load(['order.customer', 'order.items.service']);

        if (!$invoice->order) {
            abort(404, 'Order not found for this invoice.');
        }

        $order = $invoice->order;
        $paymentTypes = PaymentType::cases();

        return Inertia::render('Orders/Payment', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer' => $order->customer ? [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'email' => $order->customer->email,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'service_name' => $item->description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'amount' => $item->amount,
                        'unit' => $item->unit,
                    ];
                }),
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total_amount' => $order->total_amount,
                'formatted_total_amount' => $order->formatted_total_amount,
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'paid_amount' => $invoice->paid_amount,
                    'status' => $invoice->status->value,
                    'payment_gateway' => $invoice->payment_gateway?->value,
                ],
            ],
            'paymentTypes' => array_map(fn($type) => [
                'value' => $type->value,
                'label' => $type->getLabel(),
            ], $paymentTypes),
            'invoiceId' => $invoice->id,
        ]);
    }

    /**
     * Process payment for the order.
     */
    public function processPayment(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_type' => ['required', 'in:' . implode(',', array_column(PaymentType::cases(), 'value'))],
        ]);

        $invoice = $order->invoices()->first();

        if (!$invoice) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Invoice not found for this order.');
        }

        $paymentType = PaymentType::from($validated['payment_type']);

        if ($paymentType === PaymentType::CASH) {
            // Cash payment - mark as paid
            $invoice->update([
                'payment_gateway' => PaymentGateway::CASH,
                'paid_amount' => $invoice->total_amount,
                'status' => PaymentStatus::PAID,
                'paid_at' => now(),
            ]);
        } elseif ($paymentType === PaymentType::CARD) {
            // Card payment - mark as pending (payment will be processed via gateway)
            $invoice->update([
                'payment_gateway' => PaymentGateway::CARD,
                'paid_amount' => 0,
                'status' => PaymentStatus::PENDING,
            ]);
        } else {
            // Pay later - mark as due
            $invoice->update([
                'payment_gateway' => PaymentGateway::CASH, // Default gateway
                'paid_amount' => 0,
                'status' => PaymentStatus::DUE,
                'due_date' => now()->addDays(30), // Set due date 30 days from now
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Process payment by invoice ID.
     */
    public function processPaymentByInvoice(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_type' => ['required', 'in:' . implode(',', array_column(PaymentType::cases(), 'value'))],
        ]);

        $paymentType = PaymentType::from($validated['payment_type']);

        if ($paymentType === PaymentType::CASH) {
            // Cash payment - mark as paid
            $invoice->update([
                'payment_gateway' => PaymentGateway::CASH,
                'paid_amount' => $invoice->total_amount,
                'status' => PaymentStatus::PAID,
                'paid_at' => now(),
            ]);
        } elseif ($paymentType === PaymentType::CARD) {
            // Card payment - mark as pending (payment will be processed via gateway)
            $invoice->update([
                'payment_gateway' => PaymentGateway::CARD,
                'paid_amount' => 0,
                'status' => PaymentStatus::PENDING,
            ]);
        } else {
            // Pay later - mark as due
            $invoice->update([
                'payment_gateway' => PaymentGateway::CASH, // Default gateway
                'paid_amount' => 0,
                'status' => PaymentStatus::DUE,
                'due_date' => now()->addDays(30), // Set due date 30 days from now
            ]);
        }

        if ($invoice->order) {
            return redirect()->route('orders.show', $invoice->order)
                ->with('success', 'Payment processed successfully.');
        }

        return redirect()->route('invoices.payment', $invoice)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): Response
    {
        $order->load(['customer', 'user', 'items.service', 'payments', 'invoices']);

        $invoiceId = session('invoice_id');
        if ($invoiceId) {
            session()->forget('invoice_id');
        }

        return Inertia::render('Orders/Show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer' => $order->customer ? [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'email' => $order->customer->email,
                    'address' => $order->customer->address,
                ] : null,
                'status' => $order->status->value,
                'status_label' => $order->status->getLabel(),
                'order_date' => $order->order_date?->format('Y-m-d H:i:s'),
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total_amount' => $order->total_amount,
                'items' => $order->items->map(fn($item) => [
                    'id' => $item->id,
                    'service_name' => $item->service?->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->amount,
                ]),
                'invoice' => $order->invoices->first() ? [
                    'id' => $order->invoices->first()->id,
                    'invoice_number' => $order->invoices->first()->invoice_number,
                ] : null,
            ],
            'invoiceId' => $invoiceId,
        ]);
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order): Response
    {
        return Inertia::render('Orders/Edit', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'status' => $order->status->value,
                'order_date' => $order->order_date?->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'order_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'in:' . implode(',', array_column(OrderStatus::cases(), 'value'))],
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}

