<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $recentSearches = Auth::user()->isClerk() ? session('clerk_recent_searches', []) : [];

        return view('dashboard', compact('recentSearches'));
    }
}
