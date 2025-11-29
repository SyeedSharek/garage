<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Enums\PaymentStatus;
use App\Enums\PaymentGateway;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request): Response
    {
        $query = Invoice::with(['customer', 'order', 'user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment gateway
        if ($request->has('payment_gateway') && $request->payment_gateway !== 'all') {
            $query->where('payment_gateway', $request->payment_gateway);
        }

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortColumn, $sortDirection);

        $invoices = $query->paginate(15);

        // Transform invoices data for frontend
        $invoices->getCollection()->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'order_id' => $invoice->order_id,
                'order_number' => $invoice->order?->order_number,
                'customer_id' => $invoice->customer_id,
                'customer_name' => $invoice->customer?->name,
                'customer_phone' => $invoice->customer?->phone,
                'payment_gateway' => $invoice->payment_gateway->value,
                'payment_gateway_label' => $invoice->payment_gateway->getLabel(),
                'status' => $invoice->status->value,
                'status_label' => $invoice->status->getLabel(),
                'total_amount' => (float) $invoice->total_amount,
                'paid_amount' => (float) $invoice->paid_amount,
                'remaining_amount' => $invoice->remaining_amount,
                'formatted_total_amount' => $invoice->formatted_total_amount,
                'formatted_paid_amount' => $invoice->formatted_paid_amount,
                'formatted_remaining_amount' => $invoice->formatted_remaining_amount,
                'invoice_date' => $invoice->invoice_date?->format('Y-m-d H:i:s'),
                'due_date' => $invoice->due_date?->format('Y-m-d'),
                'paid_at' => $invoice->paid_at?->format('Y-m-d H:i:s'),
                'created_at' => $invoice->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'status', 'payment_gateway', 'customer_id']),
            'statusOptions' => array_map(fn($case) => [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ], PaymentStatus::cases()),
            'paymentGatewayOptions' => array_map(fn($case) => [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ], PaymentGateway::cases()),
        ]);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(): Response
    {
        return Inertia::render('Invoices/Create');
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['nullable', 'exists:orders,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_gateway' => ['required', 'in:' . implode(',', array_column(PaymentGateway::cases(), 'value'))],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:' . implode(',', array_column(PaymentStatus::cases(), 'value'))],
        ]);

        $invoice = Invoice::create([
            'order_id' => $validated['order_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'payment_gateway' => $validated['payment_gateway'],
            'total_amount' => $validated['total_amount'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'] ?? PaymentStatus::DRAFT,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice): Response
    {
        $invoice->load(['customer', 'order', 'user', 'payments']);

        return Inertia::render('Invoices/Show', [
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'order' => $invoice->order ? [
                    'id' => $invoice->order->id,
                    'order_number' => $invoice->order->order_number,
                ] : null,
                'customer' => $invoice->customer ? [
                    'id' => $invoice->customer->id,
                    'name' => $invoice->customer->name,
                    'phone' => $invoice->customer->phone,
                    'email' => $invoice->customer->email,
                    'address' => $invoice->customer->address,
                ] : null,
                'payment_gateway' => $invoice->payment_gateway->value,
                'payment_gateway_label' => $invoice->payment_gateway->getLabel(),
                'status' => $invoice->status->value,
                'status_label' => $invoice->status->getLabel(),
                'total_amount' => (float) $invoice->total_amount,
                'paid_amount' => (float) $invoice->paid_amount,
                'remaining_amount' => $invoice->remaining_amount,
                'formatted_total_amount' => $invoice->formatted_total_amount,
                'formatted_paid_amount' => $invoice->formatted_paid_amount,
                'formatted_remaining_amount' => $invoice->formatted_remaining_amount,
                'invoice_date' => $invoice->invoice_date?->format('Y-m-d H:i:s'),
                'due_date' => $invoice->due_date?->format('Y-m-d'),
                'paid_at' => $invoice->paid_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice): Response
    {
        return Inertia::render('Invoices/Edit', [
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'order_id' => $invoice->order_id,
                'customer_id' => $invoice->customer_id,
                'payment_gateway' => $invoice->payment_gateway->value,
                'status' => $invoice->status->value,
                'total_amount' => (float) $invoice->total_amount,
                'invoice_date' => $invoice->invoice_date?->format('Y-m-d'),
                'due_date' => $invoice->due_date?->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['nullable', 'exists:orders,id'],
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'payment_gateway' => ['sometimes', 'in:' . implode(',', array_column(PaymentGateway::cases(), 'value'))],
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'invoice_date' => ['sometimes', 'date'],
            'due_date' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:' . implode(',', array_column(PaymentStatus::cases(), 'value'))],
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }



    public function showInvoice()
    {
        $services = InvoiceService::getServices();
        return view('invoice.invoice', compact('services'));
    }



















}

