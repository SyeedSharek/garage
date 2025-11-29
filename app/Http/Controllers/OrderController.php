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
use Inertia\ResponseFactory;

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
        $invoice = $order->invoices()->first();

        if (!$invoice) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Invoice not found for this order.');
        }

        $validated = $request->validate([
            'payment_type' => ['required', 'in:' . implode(',', array_column(PaymentType::cases(), 'value'))],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'max:' . ($invoice->total_amount - $invoice->paid_amount)],
        ]);

        $paymentType = PaymentType::from($validated['payment_type']);
        $paidAmount = $validated['paid_amount'] ?? null;

        if ($paymentType === PaymentType::CASH) {
            // Determine the payment amount (use provided amount or full remaining amount)
            $remainingAmount = $invoice->total_amount - $invoice->paid_amount;
            $paymentAmount = $paidAmount !== null ? (float) $paidAmount : $remainingAmount;
            $paymentAmount = min($paymentAmount, $remainingAmount);

            // Calculate new paid amount
            $newPaidAmount = $invoice->paid_amount + $paymentAmount;

            // Cash payment - mark as paid or partial
            $invoice->update([
                'payment_gateway' => PaymentGateway::CASH,
                'paid_amount' => $newPaidAmount,
                'status' => $newPaidAmount >= $invoice->total_amount ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
                'paid_at' => $newPaidAmount >= $invoice->total_amount ? now() : null,
            ]);
        } elseif ($paymentType === PaymentType::CARD) {
            // Card payment - process with paid amount if provided
            $remainingAmount = $invoice->total_amount - $invoice->paid_amount;
            $paymentAmount = $paidAmount !== null ? (float) $paidAmount : 0;
            $paymentAmount = min($paymentAmount, $remainingAmount);

            $newPaidAmount = $invoice->paid_amount + $paymentAmount;

            $invoice->update([
                'payment_gateway' => PaymentGateway::CARD,
                'paid_amount' => $newPaidAmount,
                'status' => $newPaidAmount >= $invoice->total_amount ? PaymentStatus::PAID : PaymentStatus::PENDING,
                'paid_at' => $newPaidAmount >= $invoice->total_amount ? now() : null,
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

        // Redirect to success page with invoice and order data
        $redirectData = [
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'total_amount' => (float) $invoice->total_amount,
                'paid_amount' => (float) $invoice->paid_amount,
                'status' => $invoice->status->value,
            ],
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer' => $order->customer ? [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                ] : null,
            ],
            'payment_type' => $validated['payment_type'],
        ];

        return redirect()->route('payments.success')
            ->with('payment_data', $redirectData);
    }

    /**
     * Process payment by invoice ID.
     */
    public function processPaymentByInvoice(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_type' => ['required', 'in:' . implode(',', array_column(PaymentType::cases(), 'value'))],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'max:' . ($invoice->total_amount - $invoice->paid_amount)],
        ]);

        try {
            return DB::transaction(function () use ($validated, $invoice) {
                $paymentType = PaymentType::from($validated['payment_type']);
                $paidAmount = $validated['paid_amount'] ?? null;

                // Update invoice payment gateway and status
                if ($paymentType === PaymentType::CASH) {
                    // Cash payment - create payment record
                    $invoice->update([
                        'payment_gateway' => PaymentGateway::CASH,
                    ]);

                    // Determine the payment amount (use provided amount or full remaining amount)
                    $remainingAmount = $invoice->total_amount - $invoice->paid_amount;
                    $paymentAmount = $paidAmount !== null ? (float) $paidAmount : $remainingAmount;

                    // Ensure payment amount doesn't exceed remaining amount
                    $paymentAmount = min($paymentAmount, $remainingAmount);

                    // Create payment record (only if invoice has an order)
                    if ($invoice->order_id && $paymentAmount > 0) {
                        OrderPayment::create([
                            'order_id' => $invoice->order_id,
                            'invoice_id' => $invoice->id,
                            'user_id' => auth()->id() ?? null,
                            'payment_type' => \App\Enums\OrderPaymentType::TOTAL,
                            'amount' => $paymentAmount,
                            'is_paid' => true,
                            'is_payable' => false,
                            'payment_date' => now(),
                        ]);

                        // Refresh invoice to reload relationships
                        $invoice->refresh();

                        // Update payment status (will calculate paid_amount from payments)
                        $invoice->updatePaymentStatus();
                    } else if ($paymentAmount > 0) {
                        // If no order, directly update invoice paid amount
                        $newPaidAmount = $invoice->paid_amount + $paymentAmount;
                        $invoice->update([
                            'paid_amount' => $newPaidAmount,
                            'status' => $newPaidAmount >= $invoice->total_amount ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
                            'paid_at' => $newPaidAmount >= $invoice->total_amount ? now() : null,
                        ]);
                    } else {
                        // If payment amount is 0, ensure status is set correctly
                        $invoice->refresh();
                        $currentPaidAmount = (float) $invoice->paid_amount;
                        if ($currentPaidAmount >= $invoice->total_amount) {
                            $invoice->update([
                                'status' => PaymentStatus::PAID,
                                'paid_at' => now(),
                            ]);
                        } elseif ($currentPaidAmount > 0) {
                            $invoice->update([
                                'status' => PaymentStatus::PARTIAL,
                            ]);
                        }
                    }
                } elseif ($paymentType === PaymentType::CARD) {
                    // Card payment - process with paid amount if provided
                    $remainingAmount = $invoice->total_amount - $invoice->paid_amount;
                    $paymentAmount = $paidAmount !== null ? (float) $paidAmount : 0;
                    $paymentAmount = min($paymentAmount, $remainingAmount);

                    $newPaidAmount = $invoice->paid_amount + $paymentAmount;

                    // Determine status: PAID if 100%, PARTIAL if partially paid, otherwise keep current status
                    if ($newPaidAmount >= $invoice->total_amount) {
                        $status = PaymentStatus::PAID;
                        $paidAt = now();
                    } elseif ($newPaidAmount > 0) {
                        $status = PaymentStatus::PARTIAL;
                        $paidAt = null;
                    } else {
                        // No payment made, keep current status or set to DUE
                        $status = $invoice->status;
                        $paidAt = null;
                    }

                    $invoice->update([
                        'payment_gateway' => PaymentGateway::CARD,
                        'paid_amount' => $newPaidAmount,
                        'status' => $status,
                        'paid_at' => $paidAt,
                    ]);
                } else {
                    // Pay later - mark as due
                    $invoice->update([
                        'payment_gateway' => PaymentGateway::CASH, // Default gateway
                        'paid_amount' => 0,
                        'status' => PaymentStatus::DUE,
                        'due_date' => $invoice->due_date ?? now()->addDays(30), // Set due date 30 days from now if not set
                    ]);
                }

                // Redirect to success page with invoice and order data
                $redirectData = [
                    'invoice' => [
                        'id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'total_amount' => (float) $invoice->total_amount,
                        'paid_amount' => (float) $invoice->paid_amount,
                        'status' => $invoice->status->value,
                    ],
                    'payment_type' => $validated['payment_type'],
                ];

                if ($invoice->order) {
                    $redirectData['order'] = [
                        'id' => $invoice->order->id,
                        'order_number' => $invoice->order->order_number,
                        'customer' => $invoice->order->customer ? [
                            'id' => $invoice->order->customer->id,
                            'name' => $invoice->order->customer->name,
                        ] : null,
                    ];
                }

                return redirect()->route('payments.success')
                    ->with('payment_data', $redirectData);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('invoices.payment', $invoice)
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    /**
     * Show payment success page.
     */
    public function paymentSuccess(Request $request): Response|RedirectResponse
    {
        // Get payment data from session (set during redirect)
        $paymentData = $request->session()->get('payment_data', []);

        // If no payment data in session, redirect to orders index
        if (empty($paymentData)) {
            return redirect()->route('orders.index')
                ->with('info', 'No payment data found.');
        }

        // Clear the session data
        $request->session()->forget('payment_data');

        return Inertia::render('Orders/PaymentSuccess', [
            'invoice' => $paymentData['invoice'] ?? null,
            'order' => $paymentData['order'] ?? null,
            'paymentType' => $paymentData['payment_type'] ?? 'cash',
        ]);
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

