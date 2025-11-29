<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Reports/Index', [
            'reports' => [],
        ]);
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(): Response
    {
        return Inertia::render('Reports/Create');
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement report creation logic
        return redirect()->route('reports.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(string $id): Response
    {
        return Inertia::render('Reports/Show', [
            'report' => [
                'id' => $id,
            ],
        ]);
    }
}


