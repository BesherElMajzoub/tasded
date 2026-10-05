<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LegalPageController extends Controller
{
    public function privacy(): View
    {
        return view('legal.privacy', ['landing' => config('landing')]);
    }

    public function terms(): View
    {
        return view('legal.terms', ['landing' => config('landing')]);
    }
}
