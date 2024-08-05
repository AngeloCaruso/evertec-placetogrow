<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Roles\DestroyRoleAction;
use App\Actions\Roles\GetAllRolesAction;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Role::class);
        $roles = GetAllRolesAction::exec([], new Role());
        return view('livewire.roles.views.index', compact('roles'));
    }

    public function create(): View
    {
        Gate::authorize('create', Role::class);
        return view('livewire.roles.views.create');
    }

    public function show(Role $role): View
    {
        Gate::authorize('show', $role);
        return view('livewire.roles.views.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        Gate::authorize('update', $role);
        return view('livewire.roles.views.edit', compact('role'));
    }

    public function destroy(Role $role, DestroyRoleAction $action): RedirectResponse
    {
        Gate::authorize('delete', $role);
        DestroyRoleAction::exec([], $role);
        return redirect()->route('roles.index');
    }
}
