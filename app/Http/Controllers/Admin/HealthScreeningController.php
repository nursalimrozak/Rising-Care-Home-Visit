<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthScreeningQuestion;
use Illuminate\Http\Request;

class HealthScreeningController extends Controller
{
    public function index()
    {
        $categories = \App\Models\HealthQuestionCategory::with('questions')->orderBy('order')->get();
        $questions = HealthScreeningQuestion::orderBy('order')->get();
        
        // Calculate next order for each category
        $nextOrderByCategory = [];
        foreach ($categories as $category) {
            $maxOrder = $category->questions->max('order');
            $nextOrderByCategory[$category->id] = $maxOrder ? $maxOrder + 1 : 1;
        }
        
        // Next order for uncategorized questions
        $maxUncategorized = $questions->where('category_id', null)->max('order');
        $nextOrderUncategorized = $maxUncategorized ? $maxUncategorized + 1 : 1;
        
        // Next order for new category
        $maxCategoryOrder = $categories->max('order');
        $nextCategoryOrder = $maxCategoryOrder ? $maxCategoryOrder + 1 : 1;
        
        return view('admin.health-screening.index', compact('categories', 'questions', 'nextOrderByCategory', 'nextOrderUncategorized', 'nextCategoryOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:health_question_categories,id',
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,boolean,scale,checklist',
            'order' => 'required|integer|min:1',
            'options' => 'nullable|string|required_if:type,checklist',
        ]);

        if ($request->type === 'checklist' && $request->options) {
            // Convert comma separated string to array
            $validated['options'] = array_map('trim', explode(',', $request->options));
        } else {
            $validated['options'] = null;
        }

        HealthScreeningQuestion::create($validated);

        return redirect()->route('admin.health-screening.index')->with('success', 'Pertanyaan berhasil ditambahkan');
    }

    public function update(Request $request, HealthScreeningQuestion $question)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:health_question_categories,id',
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,boolean,scale,checklist',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'options' => 'nullable|string|required_if:type,checklist',
        ]);

        if ($request->type === 'checklist' && $request->options) {
            $validated['options'] = array_map('trim', explode(',', $request->options));
        } else {
            $validated['options'] = null;
        }

        $question->update($validated);

        return redirect()->route('admin.health-screening.index')->with('success', 'Pertanyaan berhasil diperbarui');
    }

    public function destroy(HealthScreeningQuestion $question)
    {
        $question->delete();
        return redirect()->route('admin.health-screening.index')->with('success', 'Pertanyaan berhasil dihapus');
    }
}
