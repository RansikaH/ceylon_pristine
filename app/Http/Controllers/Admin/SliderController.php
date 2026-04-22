<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::ordered()->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'main_topic' => 'required|string|max:255',
            'description' => 'required|string',
            'subtopic' => 'nullable|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_url' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider-images'), $imageName);
            $data['image'] = $imageName;
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        Slider::create($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'main_topic' => 'required|string|max:255',
            'description' => 'required|string',
            'subtopic' => 'nullable|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_url' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && file_exists(public_path('slider-images/' . $slider->image))) {
                unlink(public_path('slider-images/' . $slider->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider-images'), $imageName);
            $data['image'] = $imageName;
        }

        $data['sort_order'] = $data['sort_order'] ?? $slider->sort_order;
        $data['is_active'] = $data['is_active'] ?? $slider->is_active;

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete image
        if ($slider->image && file_exists(public_path('slider-images/' . $slider->image))) {
            unlink(public_path('slider-images/' . $slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully.');
    }

    /**
     * Toggle slider status.
     */
    public function toggleStatus(Slider $slider)
    {
        $slider->is_active = !$slider->is_active;
        $slider->save();

        return response()->json([
            'success' => true,
            'message' => 'Slider status updated successfully.',
            'is_active' => $slider->is_active
        ]);
    }

    /**
     * Reorder sliders.
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $sliderId) {
            $slider = Slider::find($sliderId);
            if ($slider) {
                $slider->sort_order = $index;
                $slider->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sliders reordered successfully.'
        ]);
    }
}
