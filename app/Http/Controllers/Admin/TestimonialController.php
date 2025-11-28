<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = LandingTestimonial::latest()->get();
        return view('admin.landing.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.landing.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_avatar' => 'nullable|image|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'service_name' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('customer_avatar')) {
            $path = $request->file('customer_avatar')->store('testimonials', 'public');
            $validated['customer_avatar'] = $path;
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        LandingTestimonial::create($validated);

        return redirect()->route('admin.landing.testimonials.index')
            ->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function edit(LandingTestimonial $testimonial)
    {
        if (request()->wantsJson()) {
            return response()->json($testimonial);
        }
        return view('admin.landing.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, LandingTestimonial $testimonial)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_avatar' => 'nullable|image|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'service_name' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('customer_avatar')) {
            // Delete old avatar
            if ($testimonial->customer_avatar) {
                Storage::disk('public')->delete($testimonial->customer_avatar);
            }
            
            $path = $request->file('customer_avatar')->store('testimonials', 'public');
            $validated['customer_avatar'] = $path;
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $testimonial->update($validated);

        return redirect()->route('admin.landing.testimonials.index')
            ->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function destroy(LandingTestimonial $testimonial)
    {
        if ($testimonial->customer_avatar) {
            Storage::disk('public')->delete($testimonial->customer_avatar);
        }
        
        $testimonial->delete();

        return back()->with('success', 'Testimonial berhasil dihapus.');
    }
}
