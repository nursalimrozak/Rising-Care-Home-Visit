<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingCtaAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CtaAppointmentController extends Controller
{
    public function edit()
    {
        $cta = LandingCtaAppointment::firstOrCreate(
            ['id' => 1],
            [
                'section_title' => 'Ready to Get Started?',
                'section_subtitle' => 'Buat janji dengan kami sekarang',
                'button_text' => 'Buat Janji Sekarang',
                'background_color' => '#0d9488',
                'is_active' => true,
            ]
        );
        
        return view('admin.landing.cta-appointment.edit', compact('cta'));
    }

    public function update(Request $request)
    {
        $cta = LandingCtaAppointment::firstOrFail();
        
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'section_subtitle' => 'nullable|string',
            'background_image' => 'nullable|image|max:2048',
            'background_color' => 'nullable|string|max:20',
            'button_text' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('background_image')) {
            // Delete old image
            if ($cta->background_image) {
                Storage::disk('public')->delete($cta->background_image);
            }
            
            $path = $request->file('background_image')->store('landingpage', 'public');
            $validated['background_image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $cta->update($validated);

        return redirect()->route('admin.landing.cta-appointment.edit')
            ->with('success', 'CTA section berhasil diperbarui.');
    }
}
