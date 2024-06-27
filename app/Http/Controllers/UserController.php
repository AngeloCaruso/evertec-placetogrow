<?php

namespace App\Http\Controllers;

use App\Actions\Users\DestroyUserAction;
use App\Actions\Users\GetAllUsersAction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = GetAllUsersAction::exec([], new User());
        return view('livewire.users.views.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('create', User::class);
        return view('livewire.users.views.create');
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        return view('livewire.users.views.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        DestroyUserAction::exec([], $user);
        return redirect()->route('users.index');
    }
}
