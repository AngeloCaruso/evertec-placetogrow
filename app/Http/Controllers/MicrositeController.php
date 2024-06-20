<?php

namespace App\Http\Controllers;

use App\Actions\Microsites\GetAllMicrositesAction;
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
        return Inertia::render('Microsites/Index', [
            'sites' => $sites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMicrositeRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMicrositeRequest $request, Microsite $microsite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Microsite $microsite)
    {
        //
    }
}
