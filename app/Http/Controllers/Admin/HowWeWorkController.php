<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingHowWeWork;
use Illuminate\Http\Request;

class HowWeWorkController extends Controller
{
    public function index()
    {
        $steps = LandingHowWeWork::orderBy('order')->get();
        
        $sectionTitle = \App\Models\SiteSetting::where('key', 'landing_how_we_work_title')->value('value');
        $sectionSubtitle = \App\Models\SiteSetting::where('key', 'landing_how_we_work_subtitle')->value('value');
        
        return view('admin.landing.how-we-work.index', compact('steps', 'sectionTitle', 'sectionSubtitle'));
    }

    public function create()
    {
        return view('admin.landing.how-we-work.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LandingHowWeWork::create($validated);

        return redirect()->route('admin.landing.how-we-work.index')
            ->with('success', 'Step berhasil ditambahkan.');
    }

    public function edit(LandingHowWeWork $howWeWork)
    {
        if (request()->wantsJson()) {
            return response()->json($howWeWork);
        }
        return view('admin.landing.how-we-work.edit', compact('howWeWork'));
    }

    public function update(Request $request, LandingHowWeWork $howWeWork)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $howWeWork->update($validated);

        return redirect()->route('admin.landing.how-we-work.index')
            ->with('success', 'Step berhasil diperbarui.');
    }

    public function destroy(LandingHowWeWork $howWeWork)
    {
        $howWeWork->delete();

        return back()->with('success', 'Step berhasil dihapus.');
    }
}
