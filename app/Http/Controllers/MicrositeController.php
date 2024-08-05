<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Actions\Microsites\GetAllMicrositesAction;
use App\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MicrositeController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Microsite::class);
        $sites = GetAllMicrositesAction::exec([], new Microsite());
        return view('livewire.microsites.views.index', compact('sites'));
    }

    public function create(): View
    {
        Gate::authorize('create', Microsite::class);
        return view('livewire.microsites.views.create');
    }

    public function show(Microsite $microsite): View
    {
        Gate::authorize('show', $microsite);
        return view('livewire.microsites.views.show', ['site' => $microsite]);
    }

    public function edit(Microsite $microsite): View
    {
        Gate::authorize('update', $microsite);
        return view('livewire.microsites.views.edit', ['site' => $microsite]);
    }

    public function destroy(Microsite $microsite): RedirectResponse
    {
        Gate::authorize('delete', $microsite);
        DestroyMicrositeAction::exec([], $microsite);
        return redirect()->route('microsites.index');
    }
}
