<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CommissionSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'commission_petugas' => SiteSetting::get('commission_petugas', 60),
            'commission_admin' => SiteSetting::get('commission_admin', 20),
            'commission_superadmin' => SiteSetting::get('commission_superadmin', 15),
            'commission_service' => SiteSetting::get('commission_service', 5),
        ];

        return view('admin.commission-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'commission_petugas' => 'required|numeric|min:0|max:100',
            'commission_admin' => 'required|numeric|min:0|max:100',
            'commission_superadmin' => 'required|numeric|min:0|max:100',
            'commission_service' => 'required|numeric|min:0|max:100',
        ]);

        // Validate total must equal 100
        $total = $request->commission_petugas + $request->commission_admin + 
                 $request->commission_superadmin + $request->commission_service;

        if ($total != 100) {
            return back()->withErrors(['total' => 'Total persentase harus 100%. Saat ini: ' . $total . '%']);
        }

        // Update settings
        SiteSetting::set('commission_petugas', $request->commission_petugas);
        SiteSetting::set('commission_admin', $request->commission_admin);
        SiteSetting::set('commission_superadmin', $request->commission_superadmin);
        SiteSetting::set('commission_service', $request->commission_service);

        return back()->with('success', 'Pengaturan komisi berhasil diperbarui');
    }
}
