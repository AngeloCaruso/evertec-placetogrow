<?php

namespace App\Http\Controllers;

use App\Actions\Roles\DestroyRoleAction;
use App\Actions\Roles\GetAllRolesAction;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = GetAllRolesAction::exec([], new Role());
        return view('livewire.roles.index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('livewire.roles.create');
    }

    public function edit(Role $role)
    {
        return view('livewire.roles.edit', [
            'role' => $role,
        ]);
    }

    public function destroy(Role $role, DestroyRoleAction $action)
    {
        DestroyRoleAction::exec([], $role);
        return redirect()->route('roles.index');
    }
}
