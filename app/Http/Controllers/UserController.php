<?php

namespace App\Http\Controllers;

use App\Actions\Users\DestroyUserAction;
use App\Actions\Users\GetAllUsersAction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllUsersAction $action)
    {
        $users = $action->exec(new Request(), new User());
        return view('livewire.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.users.create');
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('livewire.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DestroyUserAction $action)
    {
        $action->exec(request(), $user);
        return redirect()->route('users.index');
    }
}
