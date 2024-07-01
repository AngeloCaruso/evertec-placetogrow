<?php

namespace App\Http\Controllers;

use App\Actions\Roles\DestroyRoleAction;
use App\Actions\Roles\GetAllRolesAction;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Role::class);
        $roles = GetAllRolesAction::exec([], new Role());
        return view('livewire.roles.views.index', compact('roles'));
    }

    public function create()
    {
        Gate::authorize('create', Role::class);
        return view('livewire.roles.views.create');
    }

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);
        return view('livewire.roles.views.edit', compact('role'));
    }

    public function destroy(Role $role, DestroyRoleAction $action)
    {
        Gate::authorize('delete', $role);
        DestroyRoleAction::exec([], $role);
        return redirect()->route('roles.index');
    }
}
