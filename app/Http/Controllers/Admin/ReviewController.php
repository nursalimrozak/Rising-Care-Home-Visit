<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer', 'booking.service'])
            ->latest()
            ->paginate(20);
            
        $sectionTitle = \App\Models\SiteSetting::where('key', 'landing_reviews_title')->value('value');
        $sectionSubtitle = \App\Models\SiteSetting::where('key', 'landing_reviews_subtitle')->value('value');
            
        return view('admin.reviews.index', compact('reviews', 'sectionTitle', 'sectionSubtitle'));
    }

    public function toggleLanding(Review $review)
    {
        $review->update([
            'show_on_landing' => !$review->show_on_landing
        ]);

        return response()->json([
            'success' => true,
            'show_on_landing' => $review->show_on_landing
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }
}
