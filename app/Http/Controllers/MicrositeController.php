<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Actions\Microsites\GetAllMicrositesAction;
use App\Models\Microsite;
use Illuminate\Support\Facades\Gate;

class MicrositeController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Microsite::class);
        $sites = GetAllMicrositesAction::exec([], new Microsite());
        return view('livewire.microsites.index', compact('sites'));
    }

    public function create()
    {
        Gate::authorize('create', Microsite::class);
        return view('livewire.microsites.create');
    }

    public function edit(Microsite $microsite)
    {
        Gate::authorize('update', $microsite);
        return view('livewire.microsites.edit', ['site' => $microsite]);
    }

    public function destroy(Microsite $microsite)
    {
        Gate::authorize('delete', $microsite);
        DestroyMicrositeAction::exec([], $microsite);
        return redirect()->route('microsites.index');
    }
}
