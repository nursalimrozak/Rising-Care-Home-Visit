<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.payouts.index', compact('payouts'));
    }

    public function show(Payout $payout)
    {
        $payout->load(['user', 'commissions.payment.booking']);
        return view('admin.payouts.show', compact('payout'));
    }

    public function process(Request $request, Payout $payout)
    {
        $request->validate([
            'proof_file' => 'required|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Payout sudah diproses.');
        }

        $path = $request->file('proof_file')->store('payout-proofs', 'public');

        $payout->update([
            'status' => 'processed',
            'proof_file' => $path,
            'notes' => $request->notes,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Payout berhasil diproses.');
    }
}
