<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::where('user_id', Auth::id())
            ->with(['payment.booking'])
            ->latest()
            ->paginate(15);

        $totalCommission = Commission::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->sum('amount');
            
        $pendingCommission = Commission::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->sum('amount');

        return view('petugas.commissions.index', compact('commissions', 'totalCommission', 'pendingCommission'));
    }
}
