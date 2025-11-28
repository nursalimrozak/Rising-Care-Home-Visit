<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthQuestionCategory;
use Illuminate\Http\Request;

class HealthQuestionCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        HealthQuestionCategory::create($validated);

        return redirect()->route('admin.health-screening.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, HealthQuestionCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.health-screening.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(HealthQuestionCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.health-screening.index')->with('success', 'Kategori berhasil dihapus');
    }
}
