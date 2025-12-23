<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function contact(): View
    {
        $page = Page::where('slug', 'contact')->where('is_active', true)->first();
        return view('pages.contact', compact('page'));
    }

    public function index(): View
    {
        $pages = Page::latest()->paginate(10);
        return view('pages.index', compact('pages'));
    }

    public function edit(Page $page): View
    {
        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $locales = ['fa', 'en', 'ar'];

        $request->validate([
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
        ]);

        $page->update([
            'slug' => str($request->slug)->slug(),
            'is_active' => $request->has('is_active'),
        ]);


        foreach ($locales as $locale) {
            if (!$request->input("title.$locale")) continue;

            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $request->input("title.$locale"),
                    'content' => $request->input("content.$locale"),
                ]
            );
        }

        return redirect()->route('admin.pages.index')->with('success', 'صفحه با موفقیت ویرایش شد.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'صفحه حذف شد.');
    }
}
