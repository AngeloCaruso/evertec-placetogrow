<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function changeLocale(string $locale): RedirectResponse
    {
        Session::put('locale', $locale);
        return redirect()->back();
    }
}
