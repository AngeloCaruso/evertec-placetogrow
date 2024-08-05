<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AccessControlList;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AccessControlListController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', AccessControlList::class);
        $acls = AccessControlList::all();
        return view('livewire.access-control-list.views.index', compact('acls'));
    }

    public function create(): View
    {
        Gate::authorize('create', AccessControlList::class);
        return view('livewire.access-control-list.views.create');
    }

    public function edit(AccessControlList $acl): View
    {
        Gate::authorize('update', $acl);
        return view('livewire.access-control-list.views.edit', compact('acl'));
    }
}
