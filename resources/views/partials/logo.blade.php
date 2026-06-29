@php 
  use Illuminate\Support\Facades\Storage; 
  $path = $path ?? '/';
@endphp

<a href="{{ $path }}" class="flex h-full items-center">
    @if(setting('site_logo'))
        <img src="{{ Storage::url(setting('site_logo')) }}"
            alt="{{ setting('site_name', 'Rently') }}"
            class="h-full w-auto object-contain">
    @else
        <span class="text-xl font-bold text-indigo-600">
            {{ setting('site_name', 'Rently') }}
        </span>
    @endif
</a>