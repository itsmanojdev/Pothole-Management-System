@props(['mode' => 'create', 'pothole' => []])

@php
    $isCreate = $mode == 'create';
    $isShow = $mode == 'show';
    $isEdit = $mode == 'edit';

    $formURL = '/citizen/potholes';
    $method = 'post';
    if ($isShow) {
        $method = 'get';
        $formURL = "/citizen/potholes/$pothole->id/edit";
    } elseif ($isEdit) {
        $formURL = "/citizen/potholes/$pothole->id";
    }
@endphp

<form id="pothole-{{ $mode }}" enctype="multipart/form-data" class="mt-6 space-y-4" x-data="{
    form: $form('{{ $method }}', '{{ $formURL }}', {
        title: `{{ old('title', $pothole->title ?? '') }}`,
        description: `{{ old('description', $pothole->description ?? '') }}`,
        address: `{{ old('address', $pothole->address ?? '') }}`,
        latitude: `{{ old('latitude', $pothole->latitude ?? '') }}`,
        longitude: `{{ old('longitude', $pothole->longitude ?? '') }}`
    }).setErrors({{ Js::from($errors->messages()) }}),
}">
    @csrf
    @if ($isEdit)
        @method('PATCH')
    @endif
    <div class="flex items-center gap-16">
        <div class="w-3/5 space-y-4">
            <x-form-field type="text" name="title" :disabled="$isShow" required />
            <x-form-field type="textarea" name="description" class="h-35" :disabled="$isShow" />
        </div>
        <div class="mt-2">
            <x-form-field type="img-file" name="pothole-image" label="Choose Pothole Image"
                imgSrc="{{ isset($pothole->image_path) ? getImageSource($pothole->image_path) : Vite::asset('resources/images/pothole_default.png') }}"
                :disabled="$isShow" />
        </div>
    </div>
    <div class="px-6 py-4 space-y-4 bg-white border border-gray-400 rounded-sm shadow-md">
        <div class="flex gap-4">
            <x-form-field type="dropdown-input" name="address" autocomplete="off" class="flex-1" :disabled="$isShow"
                required />
            @if ($isCreate || $isEdit)
                <x-button id="current-location-btn" class="py-2.5 relative top-[27px] self-start">Current
                    location</x-button>
            @elseif ($isShow)
                <x-button href="https://maps.google.com/?q={{ $pothole->latitude }},{{ $pothole->longitude }}"
                    target="_blank" class="py-2.5 relative top-[27px] self-start" onclick="event.stopPropagation();">
                    <div class="flex items-center gap-2">
                        <x-icons.location class="inline size-5 text-white" />
                        <p>Show in Google Map</p>
                    </div>
                </x-button>
            @endif
        </div>
        <div class="flex justify-center">
            <div id="pothole-create-map" class="w-full h-72 sm:h-125 rounded-lg z-1"></div>
        </div>
        <x-form-field type="hidden" name="latitude" />
        <x-form-field type="hidden" name="longitude" />
    </div>
    @if ($isCreate)
        <x-form-field type="submit" value="Create Pothole" class="px-16" />
    @elseif ($isEdit)
        <x-form-field type="submit" value="Update Pothole" class="px-16 py-3 mt-3 mb-0" />
        <x-button type="outline" :href="route('citizen.pothole.show', $pothole->id)" class="!text-base ml-4 px-8 py-3">Cancel</x-button>
    @elseif ($isShow)
        @if (Auth::user()->isCitizen())
            <x-button :href="route('citizen.pothole.edit', $pothole->id)" class="inline-block px-16 py-3 mt-4 text-base">Edit Pothole</x-button>
        @endif
    @endif
</form>

{{-- SUB FORMS --}}
@if ($isShow || $isEdit)
    @if ($pothole->status == \App\PotholeStatus::RESOLVED)
        {{-- Verification Form (Citizen & Super Admin) --}}
        <hr class="my-8 border-gray-400" />
        <x-layouts.inner-form>
            <x-slot name="title">Verify Pothole</x-slot>
            <p class="text-muted text-sm">Current Status: {{ $pothole->status }}</p>
            @if (Auth::user()->isCitizen() || Auth::user()->isSuperAdmin())
                <form method="post" action='{{ userRoute('pothole.verify', $pothole->id) }}' class="flex gap-6 mt-4">
                    @csrf
                    @method('PATCH')
                    <x-form-field type="text" name="verify" :labelDisplay="false" class="inline-block"
                        placeholder="Type 'VERIFY' here..." />
                    <x-form-field type="submit" value="Verify" class="inline-block px-8 py-1" />
                </form>
            @else
                <p class="mt-4">This pothole needs to be verified by either reported Citizen or Super Admin</p>
            @endif
        </x-layouts.inner-form>
    @elseif (Auth::user()->isCitizen() &&
            ($pothole->status == \App\PotholeStatus::OPEN || $pothole->status == \App\PotholeStatus::ASSIGNED))
        {{-- Delete Form (Citizen) --}}
        <hr class="my-8 border-gray-400" />
        <x-layouts.inner-form theme="red">
            <x-slot name="title">Delete Pothole</x-slot>
            <p class="text-muted text-sm">Current Status: {{ $pothole->status }}</p>
            <form method="post" action="/citizen/potholes/{{ $pothole->id }}" class="flex gap-6 mt-4">
                @csrf
                @method('DELETE')
                <x-form-field type="text" name="delete" :labelDisplay="false"
                    class="inline-block"placeholder="Type 'DELETE' here..." />
                <x-form-field type="submit" value="Delete"
                    class="inline-block px-8 py-1 bg-red-600 hover:bg-red-700 hover:border-red-900" />
            </form>
            <p class="text-muted text-xs mt-2">Pothole cannot be deleted after repair starts (Pothole status: In
                progress)
            </p>
        </x-layouts.inner-form>
    @elseif ($pothole->status == \App\PotholeStatus::OPEN)
        {{-- Assign To Me Form (Admin & Super Admin) --}}
        <hr class="my-8 border-gray-400" />
        <x-layouts.inner-form>
            <x-slot name="title">Status Update</x-slot>
            <p class="text-muted text-sm">Current Status: {{ $pothole->status }}</p>
            <form method="post" action="/admin/potholes/{{ $pothole->id }}/status" class="mt-4">
                @csrf
                @method('PATCH')
                <x-form-field type="hidden" name="status" value="assigned" />
                <x-form-field type="submit" value="Assign To Me" class="inline-block px-8" />
            </form>
        </x-layouts.inner-form>
    @elseif ($pothole->status != \App\PotholeStatus::VERIFIED)
        {{-- Status Update Form (Admin & Super Admin) --}}
        @if ($pothole->assigned_to == Auth::id())
            <hr class="my-8 border-gray-400" />
            <x-layouts.inner-form>
                <x-slot name="title">Status Update</x-slot>
                <p class="text-muted text-sm">Current Status: {{ $pothole->status }}</p>

                <form method="post" action="/admin/potholes/{{ $pothole->id }}/status"
                    class="flex items-center gap-8 mt-4">
                    @csrf
                    @method('PATCH')
                    <x-form-field type="dropdown" name="status" class="w-42" :options="['open', 'assigned', 'in-progress', 'resolved', 'verified' => true]" :disablePrevOption="true"
                        :currOpt="Str::kebab($pothole->status->value)" />
                    <x-form-field type="submit" value="Update Status" class="inline-block px-6" />
                </form>
            </x-layouts.inner-form>
        @elseif(Auth::user()->isCitizen())
            {{-- Note to Citizen, if repair started --}}
            @if (Auth::user()->isCitizen())
                <hr class="my-8 border-gray-400" />
                <x-layouts.inner-form theme="red">
                    <p>This Pothole cannot be deleted due to repair in progress</p>
                </x-layouts.inner-form>
            @endif
        @endif
    @endif
@endif
