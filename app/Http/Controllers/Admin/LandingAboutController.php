<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingAboutController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::whereIn('key', [
            'landing_about_title',
            'landing_about_description',
            'landing_about_image',
            'landing_about_points'
        ])->pluck('value', 'key');

        $points = json_decode($settings['landing_about_points'] ?? '[]', true);
        if (empty($points)) {
            $points = [
                'Tenaga medis profesional dan bersertifikat',
                'Layanan home visit tersedia',
                'Sistem membership dengan benefit menarik'
            ];
        }

        return view('admin.landing.about.index', compact('settings', 'points'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings.landing_about_title' => 'required|string',
            'settings.landing_about_description' => 'required|string',
            'settings.landing_about_image' => 'nullable|image|max:2048',
            'points' => 'array',
            'points.*' => 'nullable|string'
        ]);

        // Handle Image
        if ($request->hasFile('settings.landing_about_image')) {
            $path = $request->file('settings.landing_about_image')->store('landing', 'public');
            SiteSetting::updateOrCreate(
                ['key' => 'landing_about_image'],
                ['value' => $path]
            );
        }

        // Handle Title & Description
        SiteSetting::updateOrCreate(
            ['key' => 'landing_about_title'],
            ['value' => $request->input('settings.landing_about_title')]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'landing_about_description'],
            ['value' => $request->input('settings.landing_about_description')]
        );

        // Handle Points
        $points = array_values(array_filter($request->input('points', []), function($value) {
            return !is_null($value) && $value !== '';
        }));
        
        SiteSetting::updateOrCreate(
            ['key' => 'landing_about_points'],
            ['value' => json_encode($points)]
        );

        return back()->with('success', 'About section updated successfully');
    }
}
