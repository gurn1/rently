{{-- Existing images --}}
@if(isset($property) && $property->images->count() > 0)
    <div class="panel">
        <h2 class="panel-title">Current Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($property->images->sortBy('sort_order') as $image)
                <div class="relative group">
                    <div class="h-32 rounded overflow-hidden bg-gray-100">
                        <img src="{{ Storage::url($image->path) }}"
                             alt="Property image"
                             class="w-full h-full object-cover">
                    </div>

                    {{-- Featured badge --}}
                    @if($image->is_featured)
                        <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded">
                            Featured
                        </span>
                    @endif

                    {{-- Actions --}}
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                        @if(!$image->is_featured)
                            <form method="POST"
                                  action="{{ route(auth()->user()->routePrefix() . '.properties.images.featured', [$property, $image]) }}">
                                @csrf
                                <x-primary-button type="button">
                                    Set Featured
                                </x-primary-button>
                            </form>
                        @endif

                        <form method="POST"
                              action="{{ route(auth()->user()->routePrefix() . '.properties.images.destroy', [$property, $image]) }}"
                              onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="button">
                                Delete
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Upload new images --}}
<div class="panel">
    <h2 class="panel-title">
        {{ isset($property) && $property->images->count() > 0 ? 'Add More Images' : 'Upload Images' }}
    </h2>

    @if(isset($property))
        {{-- On edit page — separate upload form --}}
        <form method="POST"
              action="{{ route(auth()->user()->routePrefix() . '.properties.images.store', $property) }}"
              enctype="multipart/form-data">
            @csrf
            <div class="space-y-3">
                <x-text-input type="file"
                       name="images[]"
                       multiple
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                />
                <p class="text-xs text-gray-400">JPG, PNG or WEBP. Max 5MB per image. Up to 10 images.</p>
                @error('images.*') <p class="error-field-message">{{ $message }}</p> @enderror
                <x-primary-button type="submit">
                    Upload Images
                </x-primary-button>
            </div>
        </form>
    @else
        {{-- On create page — part of main form --}}
        <div class="space-y-3">
            <x-text-input type="file"
                   name="images[]"
                   multiple
                   accept="image/jpg,image/jpeg,image/png,image/webp"
             />
            <p class="text-xs text-gray-400">JPG, PNG or WEBP. Max 5MB per image. Up to 10 images. The first image will be set as the featured image.</p>
            @error('images.*') <p class="error-field-message">{{ $message }}</p> @enderror
        </div>
    @endif
</div>