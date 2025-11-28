<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->get();
        return view('admin.service-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories',
            'description' => 'nullable|string',
        ]);

        ServiceCategory::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $serviceCategory->id,
            'description' => 'nullable|string',
        ]);

        $serviceCategory->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki layanan');
        }

        $serviceCategory->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
