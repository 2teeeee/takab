<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
            'is_assembly_enabled' => 'nullable|boolean',
        ]);

        $data['is_assembly_enabled'] = $request->boolean('is_assembly_enabled');

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت ایجاد شد.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
            'is_assembly_enabled' => 'nullable|boolean',
        ]);

        $data['is_assembly_enabled'] = $request->boolean('is_assembly_enabled');
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت ویرایش شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت حذف شد.');
    }
}
