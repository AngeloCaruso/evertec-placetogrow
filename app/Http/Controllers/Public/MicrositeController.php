<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Microsite;
use Inertia\Inertia;

class MicrositeController extends Controller
{
    public function index()
    {
        $sites = Microsite::all();

        return Inertia::render('Microsite/Index', [
            'sites' => $sites
        ]);
    }
}
