<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Users\DestroyUserAction;
use App\Actions\Users\GetAllUsersAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);
        $users = GetAllUsersAction::exec([], new User());
        return view('livewire.users.views.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);
        return view('livewire.users.views.create');
    }

    public function show(User $user): View
    {
        Gate::authorize('show', $user);
        return view('livewire.users.views.show', compact('user'));
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);
        return view('livewire.users.views.edit', compact('user'));
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);
        DestroyUserAction::exec([], $user);
        return redirect()->route('users.index');
    }
}
