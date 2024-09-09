<?php

namespace App\Http\Controllers;

use App\Actions\DataImports\GetAllImportsAction;
use App\Models\DataImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DataImportController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', DataImport::class);
        $imports = GetAllImportsAction::exec([], new DataImport());
        return view('livewire.data-imports.views.index', compact('imports'));
    }

    public function create()
    {
        Gate::authorize('create', DataImport::class);
        return view('livewire.data-imports.views.create');
    }

    public function show(DataImport $data_import)
    {
        Gate::authorize('show', $data_import);
        $import = $data_import;
        return view('livewire.data-imports.views.show', compact('import'));
    }
}
