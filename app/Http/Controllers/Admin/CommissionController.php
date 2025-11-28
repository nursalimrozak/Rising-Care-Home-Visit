<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with(['payment.booking', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.commissions.index', compact('commissions'));
    }
}
