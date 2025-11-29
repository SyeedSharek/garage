<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('create page displays services and customers', function () {
    $service = Service::factory()->create([
        'is_custom' => false,
        'is_active' => true,
    ]);

    $customer = Customer::factory()->create([
        'is_active' => true,
    ]);

    $response = $this->withoutVite()->get(route('orders.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Orders/Create')
        ->has('services', 1)
        ->has('customers', 1)
    );
});

test('create page excludes custom services', function () {
    Service::factory()->create([
        'is_custom' => false,
        'is_active' => true,
    ]);

    Service::factory()->create([
        'is_custom' => true,
        'is_active' => true,
    ]);

    $response = $this->withoutVite()->get(route('orders.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Orders/Create')
        ->has('services', 1) // Only non-custom service
    );
});

test('store creates order with regular service items', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create([
        'is_custom' => false,
        'unit_price' => 100.00,
    ]);

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 2,
                'unit_price' => 100.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 200.00,
        'discount' => 0,
        'tax' => 0,
        'total' => 200.00,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'customer_id' => $customer->id,
        'status' => OrderStatus::PENDING->value,
    ]);

    $order = Order::where('customer_id', $customer->id)->first();

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'service_id' => $service->id,
        'quantity' => 2,
        'unit_price' => 100.00,
        'amount' => 200.00,
    ]);

    $this->assertDatabaseHas('invoices', [
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'total_amount' => 200.00,
        'status' => PaymentStatus::DUE->value,
    ]);
});

test('store creates custom service when service_id is null', function () {
    $customer = Customer::factory()->create();

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => null,
                'description' => 'Custom Service Name',
                'is_custom' => true,
                'quantity' => 1,
                'unit_price' => 50.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 50.00,
        'discount' => 0,
        'tax' => 0,
        'total' => 50.00,
    ]);

    $response->assertRedirect();

    // Check that custom service was created
    $this->assertDatabaseHas('services', [
        'name->en' => 'Custom Service Name',
        'is_custom' => true,
    ]);

    $customService = Service::where('is_custom', true)->first();

    $order = Order::where('customer_id', $customer->id)->first();

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'service_id' => $customService->id,
        'quantity' => 1,
        'unit_price' => 50.00,
    ]);
});

test('store creates order with multiple items', function () {
    $customer = Customer::factory()->create();
    $service1 = Service::factory()->create(['unit_price' => 100.00]);
    $service2 = Service::factory()->create(['unit_price' => 200.00]);

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => $service1->id,
                'quantity' => 2,
                'unit_price' => 100.00,
                'unit' => 'pcs',
            ],
            [
                'service_id' => $service2->id,
                'quantity' => 1,
                'unit_price' => 200.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 400.00,
        'discount' => 50.00,
        'tax' => 20.00,
        'total' => 370.00,
    ]);

    $response->assertRedirect();

    $order = Order::where('customer_id', $customer->id)->first();

    expect($order->items()->count())->toBe(2);
    expect($order->items->sum('amount'))->toBe(400.00);
});

test('store creates invoice with correct data', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create(['unit_price' => 100.00]);

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 1,
                'unit_price' => 100.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 100.00,
        'discount' => 0,
        'tax' => 0,
        'total' => 100.00,
    ]);

    $response->assertRedirect();

    $order = Order::where('customer_id', $customer->id)->first();
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull();
    expect($invoice->customer_id)->toBe($customer->id);
    // Invoice total_amount comes from validated total, not order->total_amount
    // Since order->total_amount is calculated from payments (which don't exist yet),
    // the controller should use $validated['total'] which is 100.00
    expect((float) $invoice->total_amount)->toBe(100.00);
    expect((float) $invoice->paid_amount)->toBe(0.00);
    expect($invoice->status)->toBe(PaymentStatus::DUE);
    expect($invoice->invoice_number)->not->toBeNull();
});

test('store redirects to invoice payment page on success', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create(['unit_price' => 100.00]);

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 1,
                'unit_price' => 100.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 100.00,
        'discount' => 0,
        'tax' => 0,
        'total' => 100.00,
    ]);

    $order = Order::where('customer_id', $customer->id)->first();
    $invoice = Invoice::where('order_id', $order->id)->first();

    $response->assertRedirect(route('invoices.payment', $invoice));
});

test('store validates required fields', function () {
    $response = $this->post(route('orders.store'), []);

    $response->assertSessionHasErrors(['customer_id', 'items']);
});

test('store validates customer exists', function () {
    $response = $this->post(route('orders.store'), [
        'customer_id' => 99999,
        'items' => [],
    ]);

    $response->assertSessionHasErrors(['customer_id']);
});

test('store validates items array is not empty', function () {
    $customer = Customer::factory()->create();

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'items' => [],
    ]);

    $response->assertSessionHasErrors(['items']);
});

test('store validates item quantity is required and minimum 1', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create();

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 0, // Invalid
                'unit_price' => 100.00,
            ],
        ],
    ]);

    $response->assertSessionHasErrors(['items.0.quantity']);
});

test('store validates item unit_price is required and non-negative', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create();

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 1,
                'unit_price' => -10, // Invalid
            ],
        ],
    ]);

    $response->assertSessionHasErrors(['items.0.unit_price']);
});

test('store creates order items with correct description format', function () {
    $customer = Customer::factory()->create();
    $service = Service::factory()->create();

    $response = $this->post(route('orders.store'), [
        'customer_id' => $customer->id,
        'order_date' => now()->format('Y-m-d'),
        'items' => [
            [
                'service_id' => $service->id,
                'quantity' => 1,
                'unit_price' => 100.00,
                'unit' => 'pcs',
            ],
        ],
        'subtotal' => 100.00,
        'discount' => 0,
        'tax' => 0,
        'total' => 100.00,
    ]);

    $response->assertRedirect();

    $order = Order::where('customer_id', $customer->id)->first();
    $orderItem = OrderItem::where('order_id', $order->id)->first();

    // Description should be translatable (array format when accessed)
    // The model's HasTranslations trait handles JSON conversion
    expect($orderItem->description)->not->toBeNull();
    // When accessing via getTranslation or as attribute, it should work
    $description = $orderItem->getTranslation('description', 'en');
    expect($description)->not->toBeEmpty();
});

test('store handles validation exception and redirects with errors', function () {
    $response = $this->post(route('orders.store'), [
        'customer_id' => null, // Invalid
        'items' => [], // Invalid
    ]);

    $response->assertSessionHasErrors(['customer_id', 'items']);
    // May redirect to orders.create or back, both are valid
    expect($response->status())->toBe(302);
});

test('create preview page is accessible', function () {
    $response = $this->withoutVite()->get(route('orders.create-preview'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Orders/CreatePreview')
    );
});

