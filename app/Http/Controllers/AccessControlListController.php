<?php

namespace App\Http\Controllers;

use App\Models\AccessControlList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessControlListController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', AccessControlList::class);
        $acls = AccessControlList::all();
        return view('livewire.access-control-list.views.index', compact('acls'));
    }

    public function create()
    {
        Gate::authorize('create', AccessControlList::class);
        return view('livewire.access-control-list.views.create');
    }

    public function edit(AccessControlList $acl)
    {
        Gate::authorize('update', $acl);
        return view('livewire.access-control-list.views.edit', compact('acl'));
    }
}
