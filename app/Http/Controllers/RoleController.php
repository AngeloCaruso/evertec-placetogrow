<?php

namespace App\Http\Controllers;

use App\Actions\Roles\DestroyRoleAction;
use App\Actions\Roles\GetAllRolesAction;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllRolesAction $action)
    {
        $roles = $action->exec(new Request(), new Role());
        return view('livewire.roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('livewire.roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, DestroyRoleAction $action)
    {
        $action->exec(request(), $role);
        return redirect()->route('roles.index');
    }
}
