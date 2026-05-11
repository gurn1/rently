<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with(['images', 'amenities'])
            ->latest()
            ->paginate(12);

        return view('dashboard.admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = Amenity::orderby('name', 'asc')->get();
        return view('dashboard.admin.properties.create', compact('amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'key_features'        => 'nullable|string',
            'address'             => 'required|string|max:255',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'price'               => 'required|numeric|min:0',
            'property_type'       => 'required|in:house,apartment,studio,commercial',
            'bedrooms'            => 'required|integer|min:0',
            'bathrooms'           => 'required|integer|min:0',
            'size'                => 'nullable|integer|min:0',
            'availability_status' => 'required|in:available,occupied,under_maintenance',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'exists:amenities,id',
        ]);

        //$validated['property_manager_id'] = auth()->id();

        // Base slug 
        $base_slug = Str::slug($validated['title']);
        $slug = $base_slug;
        // check for duplicates
        $count = 2;

        while (Property::where('slug', $slug)->exists()) {
            $slug = $base_slug . '-' . $count;
            $count++;
        }


        $validated['slug'] = $slug;

        $property = Property::create($validated);

        if (!empty($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $this->authorize('view', $property);

        $property->load([
            'images',
            'amenities'
        ]);

        return view('dashboard.admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        $amenities = Amenity::orderBy('name', 'asc')->get();

        return view('dashboard.admin.properties.edit', compact('property', 'amenities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'key_features'        => 'nullable|string',
            'address'             => 'required|string|max:255',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'price'               => 'required|numeric|min:0',
            'property_type'       => 'required|in:house,apartment,studio,commercial',
            'bedrooms'            => 'required|integer|min:0',
            'bathrooms'           => 'required|integer|min:0',
            'size'                => 'nullable|integer|min:0',
            'availability_status' => 'required|in:available,occupied,under_maintenance',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'exists:amenities,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $property->update($validated);
        $property->amenities()->sync($validated['amenities'] ?? []);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        Property::destroy($property->id);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
