<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPopupController extends Controller
{
    public function index()
    {
        $popups = LandingPopup::ordered()->get();
        $activeCount = LandingPopup::active()->count();
        
        return view('admin.landing.popups.index', compact('popups', 'activeCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        // Check if trying to activate and already have 3 active
        if ($request->has('is_active') && $request->is_active) {
            $activeCount = LandingPopup::active()->count();
            if ($activeCount >= 3) {
                return back()->withErrors(['is_active' => 'Maksimal 3 pop-up yang bisa diaktifkan.'])->withInput();
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('landing/popups', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        
        // Auto-assign order number (next available number)
        $maxOrder = LandingPopup::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        LandingPopup::create($validated);

        return redirect()->route('admin.landing.popups.index')
            ->with('success', 'Pop-up berhasil ditambahkan.');
    }

    public function edit(LandingPopup $popup)
    {
        return response()->json($popup);
    }

    public function update(Request $request, LandingPopup $popup)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'link' => 'nullable|url',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Check if trying to activate and already have 3 active (excluding current)
        if ($request->has('is_active') && $request->is_active && !$popup->is_active) {
            $activeCount = LandingPopup::active()->where('id', '!=', $popup->id)->count();
            if ($activeCount >= 3) {
                return back()->withErrors(['is_active' => 'Maksimal 3 pop-up yang bisa diaktifkan.'])->withInput();
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $validated['image'] = $request->file('image')->store('landing/popups', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $popup->update($validated);

        return redirect()->route('admin.landing.popups.index')
            ->with('success', 'Pop-up berhasil diupdate.');
    }

    public function destroy(LandingPopup $popup)
    {
        $deletedOrder = $popup->order;
        
        // Delete image
        if ($popup->image) {
            Storage::disk('public')->delete($popup->image);
        }

        $popup->delete();

        // Re-order remaining popups
        LandingPopup::where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()->route('admin.landing.popups.index')
            ->with('success', 'Pop-up berhasil dihapus.');
    }

    public function toggleActive(LandingPopup $popup)
    {
        // If trying to activate, check limit
        if (!$popup->is_active) {
            $activeCount = LandingPopup::active()->count();
            if ($activeCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 3 pop-up yang bisa diaktifkan.'
                ], 422);
            }
        }

        $popup->is_active = !$popup->is_active;
        $popup->save();

        return response()->json([
            'success' => true,
            'is_active' => $popup->is_active,
            'message' => 'Status pop-up berhasil diubah.'
        ]);
    }
}
