<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\LandingServicesHighlight;
use App\Models\LandingCtaAppointment;
use App\Models\LandingHowWeWork;
use App\Models\LandingTestimonial;
use App\Models\LandingArticle;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::active()->get();
        $servicesHighlight = LandingServicesHighlight::active()->get();
        $ctaAppointment = LandingCtaAppointment::where('is_active', true)->first();
        $howWeWork = LandingHowWeWork::orderBy('order')->get();
        
        // Get customer reviews that are set to show on landing page
        $testimonials = Review::with(['customer:id,name,avatar', 'booking.service:id,name'])
            ->where('show_on_landing', true)
            ->latest()
            ->limit(6)
            ->get();
            
        $articles = LandingArticle::latest()->limit(3)->get();

        // Get active pop-ups (max 3)
        $popups = \App\Models\LandingPopup::active()->ordered()->limit(3)->get();

        $sectionSettings = \App\Models\SiteSetting::whereIn('key', [
            'landing_services_title', 'landing_services_subtitle',
            'landing_how_we_work_title', 'landing_how_we_work_subtitle',
            'landing_reviews_title', 'landing_reviews_subtitle',
            'landing_articles_title', 'landing_articles_subtitle',
            'landing_about_title', 'landing_about_description', 'landing_about_image', 'landing_about_points'
        ])->pluck('value', 'key');

        return view('landing.index', compact(
            'heroSlides',
            'servicesHighlight',
            'ctaAppointment',
            'howWeWork',
            'testimonials',
            'articles',
            'sectionSettings',
            'popups'
        ));
    }

    public function services()
    {
        $services = Service::with('category', 'prices.membership')
            ->where('is_active', true)
            ->get();
            
        $ctaAppointment = LandingCtaAppointment::where('is_active', true)->first();

        return view('landing.services', compact('services', 'ctaAppointment'));
    }

    public function articles()
    {
        $articles = LandingArticle::published()->paginate(9);
        $ctaAppointment = LandingCtaAppointment::where('is_active', true)->first();
        return view('landing.articles', compact('articles', 'ctaAppointment'));
    }

    public function articleDetail($slug)
    {
        $article = LandingArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedArticles = LandingArticle::published()
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('landing.article-detail', compact('article', 'relatedArticles'));
    }
}
