<?php

namespace App\Http\Controllers\Public;

use App\Actions\Microsites\GetAllMicrositesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\MicrositeResource;
use App\Http\Resources\MicrositesData;
use App\Models\Microsite;
use Inertia\Inertia;

class MicrositeController extends Controller
{
    public function index()
    {
        $sites = GetAllMicrositesAction::exec(request()->all(), new Microsite());

        return Inertia::render('Microsite/Index', [
            'sites' => MicrositeResource::collection($sites),
            'sites_data' => new MicrositesData($sites),
        ]);
    }

    public function show(Microsite $microsite)
    {
        return Inertia::render('Microsite/Form', [
            'site' => new MicrositeResource($microsite),
        ]);
    }
}
