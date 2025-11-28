<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(Request $request): Response
    {
        $query = Service::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereJsonContains('name->en', $search)
                    ->orWhereJsonContains('name->ar', $search);
            });
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Order by sort_order, then by name
        $services = $query->ordered()->paginate(15);

        // Transform services data for frontend
        $services->getCollection()->transform(function ($service) {
            return [
                'id' => $service->id,
                'code' => $service->code,
                'name' => [
                    'en' => $service->getNameEn(),
                    'ar' => $service->getNameAr(),
                ],
                'unit_price' => $service->unit_price,
                'unit' => $service->unit,
                'is_active' => $service->is_active,
                'sort_order' => $service->sort_order,
            ];
        });

        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): Response
    {
        return Inertia::render('Services/Create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Prepare translatable fields
        $translatableData = [
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
        ];

        if (isset($validated['description'])) {
            $translatableData['description'] = [
                'en' => $validated['description']['en'] ?? null,
                'ar' => $validated['description']['ar'] ?? null,
            ];
        }

        // Prepare non-translatable data
        $serviceData = [
            'code' => $validated['code'] ?? null,
            'unit_price' => $validated['unit_price'],
            'unit' => $validated['unit'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        // Merge translatable data
        $serviceData = array_merge($serviceData, $translatableData);

        Service::create($serviceData);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): Response
    {
        return Inertia::render('Services/Show', [
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service): Response
    {
        return Inertia::render('Services/Edit', [
            'service' => [
                'id' => $service->id,
                'code' => $service->code,
                'name' => [
                    'en' => $service->getNameEn(),
                    'ar' => $service->getNameAr(),
                ],
                'description' => [
                    'en' => $service->getDescriptionEn(),
                    'ar' => $service->getDescriptionAr(),
                ],
                'unit_price' => $service->unit_price,
                'unit' => $service->unit,
                'is_active' => $service->is_active,
                'sort_order' => $service->sort_order,
            ],
        ]);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();

        // Prepare translatable fields
        $translatableData = [
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
        ];

        if (isset($validated['description'])) {
            $translatableData['description'] = [
                'en' => $validated['description']['en'] ?? null,
                'ar' => $validated['description']['ar'] ?? null,
            ];
        }

        // Prepare non-translatable data
        $serviceData = [
            'code' => $validated['code'] ?? null,
            'unit_price' => $validated['unit_price'],
            'unit' => $validated['unit'],
            'is_active' => $validated['is_active'] ?? $service->is_active,
            'sort_order' => $validated['sort_order'] ?? $service->sort_order,
        ];

        // Merge translatable data
        $serviceData = array_merge($serviceData, $translatableData);

        $service->update($serviceData);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}

