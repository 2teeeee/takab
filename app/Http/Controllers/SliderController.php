<?php
namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::latest()->paginate(10);
        return view('sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'lang' => 'required',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'required|image|max:2048',
        ]);

        $manager = new ImageManager(new Driver());
        $file = $request->file('image_path');
        $filename = uniqid() . '.webp';
        $imagePath = public_path('storage/slider/' . $filename);
        $image = $manager->read($file);
        $image->resize(1800,600);
        $image->toWebp()->save($imagePath);

        Slider::create([
            'lang' => $request->lang,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $filename,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'اسلایدر با موفقیت افزوده شد.');
    }

    public function edit(Slider $slider): Factory|View
    {
        return view('sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $request->validate([
            'lang' => 'required',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'subtitle', 'button_text', 'button_link']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image_path')) {
            $manager = new ImageManager(new Driver());
            $file = $request->file('image_path');
            $filename = uniqid() . '.webp';
            $imagePath = public_path('storage/slider/' . $filename);
            $image = $manager->read($file);
            $image->resize(1800,600);
            $image->toWebp()->save($imagePath);
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'اسلایدر با موفقیت ویرایش شد.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'اسلایدر حذف شد.');
    }
}
