<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingArticle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = LandingArticle::with('author')->latest()->get();
        $categories = LandingArticle::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');
            
        $sectionTitle = \App\Models\SiteSetting::where('key', 'landing_articles_title')->value('value');
        $sectionSubtitle = \App\Models\SiteSetting::where('key', 'landing_articles_subtitle')->value('value');
            
        return view('admin.landing.articles.index', compact('articles', 'categories', 'sectionTitle', 'sectionSubtitle'));
    }

    public function create()
    {
        $categories = LandingArticle::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');
            
        return view('admin.landing.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:landing_articles,slug',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        $validated['author_id'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        
        // Set published_at to now if published and not set
        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        LandingArticle::create($validated);

        return redirect()->route('admin.landing.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(LandingArticle $article)
    {
        if (request()->wantsJson()) {
            return response()->json($article);
        }
        $categories = LandingArticle::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');
            
        return view('admin.landing.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, LandingArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:landing_articles,slug,' . $article->id,
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        $validated['is_published'] = $request->has('is_published');
        
        // Set published_at to now if published and not set
        if ($validated['is_published'] && empty($validated['published_at']) && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.landing.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(LandingArticle $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        
        $article->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }
    
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'upload' => 'required|image|max:2048'
            ]);

            $path = $request->file('upload')->store('articles/images', 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }
}
