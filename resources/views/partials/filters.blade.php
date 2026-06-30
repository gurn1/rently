<form method="GET" action="{{ route('properties.index') }}" class="space-y-6">

    {{-- Preserve search term --}}
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif

    <div class="bg-white rounded-lg shadow p-5 space-y-5">
        <h2 class="font-semibold text-gray-700">Filters</h2>

        {{-- Property type --}}
        <div>
            <x-input-label>Property Type</x-input-label>
            <x-select
              name="type"
              placeholder="Any Type"
              :selected="request('type')"
              :options="['house' => 'House', 'apartment' => 'Apartment', 'studio' => 'Studio', 'commercial' => 'Commercial']"
            />
        </div>

        {{-- Bedrooms --}}
        <div>
            <x-input-label>Min Bedrooms</x-input-label>
            <x-select
              name="bedrooms"
              placeholder="Any"
              :selected="request('bedrooms')"
              :options="[1 => '1+', 2 => '2+', 3 => '3+', 4 => '4+', 5 => '5+']"
            />
        </div>

        {{-- Bathrooms --}}
        <div>
            <x-input-label>Min Bathrooms</x-input-label>
            <x-select
              name="bathrooms"
              placeholder="Any"
              :selected="request('bathrooms')"
              :options="[1 => '1+', 2 => '2+', 3 => '3+']"
            />
        </div>

        {{-- Price range --}}
        <div>
            <x-input-label>Price Range (£/mo)</x-input-label>
            <div class="flex gap-2">
              <x-text-input
                type="number"
                name="price_min"
                value="{{ request('price_min') }}"
                placeholder="Min"
              />
              <x-text-input
                type="number"
                name="price_max"
                value="{{ request('price_max') }}"
                placeholder="Max"
              />
            </div>
        </div>

        {{-- Availability --}}
        <div>
            <x-input-label>Availability</x-input-label>
            <x-select
              name="availability"
              placeholder=""
              :selected="request('availability')"
              :options="['available' => 'Available', 'occupied' => 'Occupied']"
            />
        </div>

        {{-- Sort --}}
        <div>
            <x-input-label>Sort By</x-input-label>
            <x-select
              name="sort"
              :selected="request('sort')"
              :options="['latest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'bedrooms' => 'Most Bedrooms']"
            />
        </div>

        <x-primary-button>
            Apply Filters
        </x-primary-button>

        @if(request()->hasAny(['type', 'bedrooms', 'bathrooms', 'price_min', 'price_max', 'availability', 'sort', 'search']))
            <a href="{{ route('properties.index') }}"
                class="block w-full text-center text-sm text-gray-500 hover:text-indigo-600 transition">
                Clear all filters
            </a>
        @endif
    </div>
</form>