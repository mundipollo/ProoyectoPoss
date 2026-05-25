<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmployerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $isEmployer = $user?->hasRole('vendedor') || $user?->hasRole('empleador');

        abort_unless($isEmployer, 403);

        return view('employer.dashboard');
    }
}
