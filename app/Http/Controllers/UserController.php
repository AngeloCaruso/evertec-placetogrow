<?php

namespace App\Http\Controllers;

use App\Actions\Users\DestroyUserAction;
use App\Actions\Users\GetAllUsersAction;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = GetAllUsersAction::exec([], new User());
        return view('livewire.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('livewire.users.create');
    }

    public function edit(User $user)
    {
        return view('livewire.users.edit', [
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        DestroyUserAction::exec([], $user);
        return redirect()->route('users.index');
    }
}
