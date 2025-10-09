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
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'دسته با موفقیت ایجاد شد.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'دسته با موفقیت ویرایش شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'دسته با موفقیت حذف شد.');
    }
}
