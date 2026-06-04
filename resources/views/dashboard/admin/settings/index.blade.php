@extends('layouts.portal')

@section('title', 'Settings')

@php use Illuminate\Support\Facades\Request as Req; @endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-500 mt-1">Manage your portal configuration.</p>
    </div>

    @php $activeTab = request('tab', 'general'); @endphp

    {{-- Tabs --}}
    <div class="border-b border-gray-200 mb-8">
        <nav class="flex gap-6 text-sm">
            @foreach($groups as $key => $label)
                <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
                   class="pb-3 border-b-2 transition {{ $activeTab === $key
                       ? 'border-indigo-600 text-indigo-600 font-medium'
                       : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Settings form for active tab --}}
    @foreach($groups as $groupKey => $groupLabel)
        @if($activeTab === $groupKey)
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="group" value="{{ $groupKey }}">

                <div class="bg-white rounded-lg shadow panel">
                    @forelse($settings->get($groupKey, collect()) as $setting)
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-start gap-4">
                                <div class="md:w-1/3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ $setting->label }}
                                    </label>
                                    @if($setting->hint)
                                        <p class="text-xs text-gray-400 mt-1">{{ $setting->hint }}</p>
                                    @endif
                                </div>
                                <div class="md:w-2/3">
                                    @if($setting->type === 'boolean')
                                        <div class="flex items-center gap-3">
                                            <x-checkbox-input
                                                name="{{ $setting->key }}"
                                                id="{{ $setting->key }}"
                                                value="1"
                                                :checked="(bool) $setting->value" />
                                            <label for="{{ $setting->key }}" class="text-sm text-gray-600">
                                                Enabled
                                            </label>
                                        </div>

                                    @elseif($setting->type === 'encrypted')
                                        <x-text-input type="password"
                                               name="{{ $setting->key }}"
                                               placeholder="{{ $setting->value ? '••••••••••••••••' : 'Not set' }}"
                                               autocomplete="new-password"
                                               />
                                        @if($setting->value)
                                            <p class="text-xs text-green-600 mt-1">✓ Value is set. Leave blank to keep existing.</p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-1">Not configured yet.</p>
                                        @endif

                                    @elseif($setting->key === 'default_payment_method')
                                        <x-select 
                                            name="{{ $setting->key }}"
                                            :selected="$setting->value"
                                            :options="['stripe' => 'Stripe (Online)', 'manual' => 'Manual (Bank Transfer / Cash)']"
                                        />

                                    @elseif($setting->type === 'integer')
                                        <x-text-input type="number"
                                               name="{{ $setting->key }}"
                                               value="{{ $setting->value }}"
                                               class=""/>

                                    @elseif($setting->key === 'site_logo')
                                        <div class="space-y-3">
                                            @if($setting->value)
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ Storage::url($setting->value) }}"
                                                        alt="Site Logo"
                                                        class="h-12 object-contain">
                                                    <span class="text-xs text-green-600">Logo is set</span>
                                                </div>
                                            @endif
                                            <x-text-input type="file"
                                                name="site_logo_file"
                                                accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp"/>
                                            <p class="text-xs text-gray-400">PNG, JPG, SVG or WEBP.</p>
                                        </div>

                                    @else
                                        <x-text-input type="text"
                                               name="{{ $setting->key }}"
                                               value="{{ $setting->value }}"
                                               class=""/>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-gray-400 text-sm">No settings in this group.</div>
                    @endforelse
                </div>

                <div class="flex justify-end">
                    <x-primary-button type="submit">
                    {{ __('Save ' . $groupLabel . ' Settings') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
    @endforeach
@endsection