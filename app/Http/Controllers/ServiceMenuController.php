<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceMenuController extends Controller
{
    /**
     * Display the service menu blade view.
     */
    public function index(): View
    {
        $services = Service::ordered()->get();

        return view('service-menu', [
            'services' => $services,
        ]);
    }
}

