<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingServicesHighlight;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesHighlightController extends Controller
{
    public function index()
    {
        $servicesHighlight = LandingServicesHighlight::with('service')->orderBy('order')->get();
        $services = Service::where('is_active', true)->get();
        
        $sectionTitle = \App\Models\SiteSetting::where('key', 'landing_services_title')->value('value');
        $sectionSubtitle = \App\Models\SiteSetting::where('key', 'landing_services_subtitle')->value('value');
        
        return view('admin.landing.services-highlight.index', compact('servicesHighlight', 'services', 'sectionTitle', 'sectionSubtitle'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('admin.landing.services-highlight.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LandingServicesHighlight::create($validated);

        return redirect()->route('admin.landing.services-highlight.index')
            ->with('success', 'Service highlight berhasil ditambahkan.');
    }

    public function edit(LandingServicesHighlight $servicesHighlight)
    {
        if (request()->wantsJson()) {
            return response()->json($servicesHighlight);
        }
        $services = Service::where('is_active', true)->get();
        return view('admin.landing.services-highlight.edit', compact('servicesHighlight', 'services'));
    }

    public function update(Request $request, LandingServicesHighlight $servicesHighlight)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $servicesHighlight->update($validated);

        return redirect()->route('admin.landing.services-highlight.index')
            ->with('success', 'Service highlight berhasil diperbarui.');
    }

    public function destroy(LandingServicesHighlight $servicesHighlight)
    {
        $servicesHighlight->delete();

        return back()->with('success', 'Service highlight berhasil dihapus.');
    }
}
