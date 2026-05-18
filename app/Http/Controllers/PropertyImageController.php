<?php
namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyImageController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'images'   => 'required|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $sortOrder = $property->images()->max('sort_order') ?? 0;

        foreach ($request->file('images') as $file) {
            $path = $file->store('properties/' . $property->id, 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'path'        => $path,
                'is_featured' => $property->images()->count() === 0, // first image is featured
                'sort_order'  => ++$sortOrder,
            ]);
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy(Property $property, PropertyImage $image)
    {
        $this->authorize('update', $property);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        // If deleted image was featured, set the next one as featured
        if ($image->is_featured) {
            $next = $property->images()->first();
            if ($next) {
                $next->update(['is_featured' => true]);
            }
        }

        return redirect()->back()->with('success', 'Image deleted.');
    }

    public function setFeatured(Property $property, PropertyImage $image)
    {
        $this->authorize('update', $property);

        // Remove featured from all
        $property->images()->update(['is_featured' => false]);

        // Set this one as featured
        $image->update(['is_featured' => true]);

        return redirect()->back()->with('success', 'Featured image updated.');
    }
}