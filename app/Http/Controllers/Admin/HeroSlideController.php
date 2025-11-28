<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('order')->get();
        return view('admin.landing.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.landing.hero-slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'background_image' => 'required|image|max:2048',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('background_image')) {
            // Store in 'landingpage' folder
            $path = $request->file('background_image')->store('landingpage', 'public');
            $validated['background_image'] = $path;
        }

        HeroSlide::create($validated);

        return redirect()->route('admin.landing.hero-slides.index')
            ->with('success', 'Slide berhasil ditambahkan.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        if (request()->wantsJson()) {
            return response()->json($heroSlide);
        }
        return view('admin.landing.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|max:2048',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('background_image')) {
            // Delete old image
            if ($heroSlide->background_image) {
                Storage::disk('public')->delete($heroSlide->background_image);
            }
            
            // Store in 'landingpage' folder
            $path = $request->file('background_image')->store('landingpage', 'public');
            $validated['background_image'] = $path;
        }

        $heroSlide->update($validated);

        return redirect()->route('admin.landing.hero-slides.index')
            ->with('success', 'Hero slide berhasil diperbarui');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->background_image) {
            Storage::disk('public')->delete($heroSlide->background_image);
        }
        
        $heroSlide->delete();

        return back()->with('success', 'Slide berhasil dihapus.');
    }
}
