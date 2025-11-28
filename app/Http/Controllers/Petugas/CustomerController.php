<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function show(User $user)
    {
        // Ensure it's a customer
        if ($user->role !== 'customer') {
            abort(404);
        }

        $user->load(['occupation', 'membership', 'healthRecords.category']);
        
        return view('petugas.customers.show', compact('user'));
    }
}
