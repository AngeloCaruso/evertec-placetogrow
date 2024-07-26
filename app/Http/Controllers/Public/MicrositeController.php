<?php

namespace App\Http\Controllers\Public;

use App\Actions\Microsites\GetAllMicrositesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\MicrositeResource;
use App\Models\Microsite;
use Inertia\Inertia;

class MicrositeController extends Controller
{
    public function index()
    {
        $sites = GetAllMicrositesAction::exec([], new Microsite());

        return Inertia::render('Microsite/Index', [
            'sites' => MicrositeResource::collection($sites),
        ]);
    }

    public function show (Microsite $microsite)
    {
        return Inertia::render('Microsite/PaymentForm', [
            'site' => new MicrositeResource($microsite),
        ]);
    }
}
