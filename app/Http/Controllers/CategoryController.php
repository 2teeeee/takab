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
        $category = new Category;
        return view('categories.create', compact('category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $locales = ['fa', 'en', 'ar'];

        $request->validate([
            'is_assembly_enabled' => 'nullable|boolean',
        ]);

        $category = Category::create([
            'is_assembly_enabled' => $request->boolean('is_assembly_enabled'),
        ]);

        foreach ($locales as $locale) {
            if (!$request->input("title.$locale")) continue;

            $category->translations()->create([
                'locale' => $locale,
                'title' => $request->input("title.$locale"),
                'keywords' => $request->input("keywords.$locale"),
                'description' => $request->input("description.$locale"),
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت ایجاد شد.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $locales = ['fa', 'en', 'ar'];

        $category->update([
            'is_assembly_enabled' => $request->boolean('is_assembly_enabled'),
        ]);

        foreach ($locales as $locale) {
            if (!$request->input("title.$locale")) continue;

            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $request->input("title.$locale"),
                    'keywords' => $request->input("keywords.$locale"),
                    'description' => $request->input("description.$locale"),
                ]
            );
        }

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت ویرایش شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'دسته با موفقیت حذف شد.');
    }
}
