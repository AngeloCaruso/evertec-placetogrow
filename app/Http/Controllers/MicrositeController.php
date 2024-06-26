<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Actions\Microsites\GetAllMicrositesAction;
use App\Models\Microsite;

class MicrositeController extends Controller
{
    public function index()
    {
        $sites = GetAllMicrositesAction::exec([], new Microsite());
        return view('livewire.microsites.index', [
            'sites' => $sites,
        ]);
    }

    public function create()
    {
        return view('livewire.microsites.create');
    }

    public function edit(Microsite $microsite)
    {
        return view('livewire.microsites.edit', [
            'site' => $microsite,
        ]);
    }

    public function destroy(Microsite $microsite)
    {
        DestroyMicrositeAction::exec([], $microsite);
        return redirect()->route('microsites.index');
    }
}
