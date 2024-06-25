<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\GetAllMicrositesAction;
use App\Actions\Microsites\StoreMicrositeAction;
use App\Actions\Microsites\UpdateMicrositeAction;
use App\Http\Requests\StoreMicrositeRequest;
use App\Http\Requests\UpdateMicrositeRequest;
use App\Models\Microsite;
use Inertia\Inertia;

class MicrositeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllMicrositesAction $action)
    {
        $sites = $action->exec(request(), new Microsite());
        return view('livewire.microsites.index', [
            'sites' => $sites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Microsites/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMicrositeRequest $request, StoreMicrositeAction $action)
    {
        $action->exec($request, new Microsite());
        return redirect()->route('microsites.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Microsite $microsite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Microsite $microsite)
    {
        return Inertia::render('Microsites/Edit', [
            'site' => $microsite,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMicrositeRequest $request, Microsite $microsite, UpdateMicrositeAction $action)
    {
        $action->exec($request, $microsite);
        return redirect()->route('microsites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Microsite $microsite)
    {
        //
    }
}
