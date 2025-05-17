@props(['mode' => 'create', 'user' => (object) []])

@php
    $isCreate = $mode == 'create';
    $isShow = $mode == 'show';
    $isEdit = $mode == 'edit';
    $isProfile = request()->routeIs('user.profile.*');

    $formURL = $isProfile ? '' : route('admin.management.store');
    $method = 'post';
    if ($isShow) {
        $method = 'get';
        $formURL = $isProfile ? route('user.profile.edit', $user->id) : route('admin.management.edit', $user->id);
    } elseif ($isEdit) {
        $method = 'patch';
        $formURL = $isProfile ? route('user.profile.update', $user->id) : route('admin.management.update', $user->id);
    }
@endphp

<form class="mt-6 flex gap-16" id="admin-{{ $mode }}" enctype="multipart/form-data" x-data="{
    form: $form('{{ $method }}', '{{ $formURL }}', {
        name: `{{ old('name', $user->name ?? '') }}`,
        email: `{{ old('email', $user->email ?? '') }}`,
        mobile_number: `{{ old('mobile_number', $user->mobile_number ?? '') }}`,
        aadhaar_number: `{{ old('aadhaar_number', $user->aadhaar_number ?? '') }}`,
        role: `{{ old('role', isset($user->role->value) ? Str::kebab($user->role->value) : 'admin') }}`,
        password: '',
        password_confirmation: ''
    }).setErrors({{ Js::from($errors->messages()) }}),
}">
    @csrf
    @if ($isEdit)
        @method('PATCH')
    @endif
    <div class="w-1/2 space-y-4">
        <x-form-field type="text" name="name" :disabled="$isShow" required />
        <x-form-field type="text" name="email" :disabled="$isShow" required />
        <x-form-field type="text" name="mobile_number" :disabled="$isShow" required />
        <x-form-field type="text" name="aadhaar_number" :disabled="$isShow" required />
        @if (!$isProfile)
            <x-form-field type="radio" name="role" :options="['admin', 'super-admin']" :disabled="$isShow" required />
        @endif
        @if ($isCreate)
            <x-form-field type="password" name="password" :disabled="$isShow" required />
            <x-form-field type="password" name="password_confirmation" label="Confirm Password" :disabled="$isShow"
                required />
        @endif
        @if ($isCreate)
            <x-form-field type="submit" value="Create Admin" class="px-16 mt-6" />
        @elseif ($isShow)
            <x-button :href="$formURL" class="inline-block px-16 py-3 mt-4 text-base">Edit Amin</x-button>
        @elseif($isEdit)
            <x-form-field type="submit" value="Update Admin" class="px-16 py-3 mt-3 mb-0" />
            <x-button type="outline" :href="route($isProfile ? 'user.profile.show' : 'admin.management.show', $user->id)" class="!text-base ml-4 px-8 py-3">Cancel</x-button>
        @endif

    </div>
    <div class="mt-2">
        <x-form-field type="img-file" name="profile-pic" label="Choose Profile Picture"
            imgSrc="{{ isset($user->profile_pic) ? getImageSource($user->profile_pic) : Vite::asset('resources/images/profile_default.png') }}"
            imgClasses="size-65" :disabled="$isShow" />
    </div>
</form>

@if ($isShow || $isEdit || $isProfile)
    {{-- Change Password Form --}}
    <hr class="my-8 border-gray-400" />
    <x-layouts.inner-form>
        <x-slot name="title">Change Password</x-slot>
        {{-- method="post" action="{{ route('admin.management.password', $user->id) }}" --}}
        <form class="mt-4 space-y-4 w-1/2" x-data="{
            form: $form('patch', '{{ route($isProfile ? 'user.profile.password' : 'admin.management.password', $user->id) }}', {
                password: '',
                new_password: '',
                new_password_confirmation: ''
            }).setErrors({{ Js::from($errors->messages()) }}),
        }">
            @csrf
            @method('PATCH')
            <x-form-field type="password" name="password" label="{{ $isProfile ? 'Old' : 'Your' }} Password"
                required />
            <x-form-field type="password" name="new_password" required />
            <x-form-field type="password" name="new_password_confirmation" label="Confirm Password" required />
            <x-form-field type="submit" value="Change Password" class="inline-block px-8 mt-4" />
        </form>
    </x-layouts.inner-form>
    {{-- Delete Form --}}
    <x-layouts.inner-form theme="red" class="mt-8">
        <x-slot name="title">Delete Admin</x-slot>
        <form method="post"
            action="{{ route($isProfile ? 'user.profile.destroy' : 'admin.management.destroy', $user->id) }}"
            class="flex gap-6 mt-4">
            @csrf
            @method('DELETE')
            <x-form-field type="text" name="delete" label="Confirm Delete" class="inline-block"
                placeholder="Type 'DELETE' here..." autocomplete="off" />
            <x-form-field type="submit" value="Delete" class="inline-block px-8 py-1 mt-auto red-btn" />
        </form>
    </x-layouts.inner-form>
@endif
