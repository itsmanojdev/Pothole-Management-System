@props(['mode' => 'create', 'admin' => []])

@php
    $isCreate = $mode == 'create';
    $isShow = $mode == 'show';
    $isEdit = $mode == 'edit';
    $isProfile = request()->routeIs('profile');

    $formURL = '/admin/management';
    $method = 'post';
    if ($isShow) {
        $method = 'get';
        $formURL = "/admin/management/$admin->id/edit";
    } elseif ($isEdit) {
        $formURL = "/admin/management/$admin->id";
    }
@endphp

<form class="mt-6 flex gap-16" id="admin-{{ $mode }}" enctype="multipart/form-data" x-data="{
    form: $form('{{ $method }}', '{{ $formURL }}', {
        name: `{{ old('name', $admin->name ?? '') }}`,
        email: `{{ old('email', $admin->email ?? '') }}`,
        mobile_number: `{{ old('mobile_number', $admin->mobile_number ?? '') }}`,
        aadhaar_number: `{{ old('aadhaar_number', $admin->aadhaar_number ?? '') }}`,
        role: `{{ old('role', $admin->role ?? '') }}`,
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
        <x-form-field type="radio" name="role" :options="['admin', 'super-admin']" :disabled="$isShow" required />
        @if ($isCreate)
            <x-form-field type="password" name="password" :disabled="$isShow" required />
            <x-form-field type="password" name="password_confirmation" label="Confirm Password" :disabled="$isShow"
                required />
        @endif
        <x-form-field type="submit" value="Create Admin" class="px-16 mt-6" />

    </div>
    <div class="mt-2">
        <x-form-field type="img-file" name="profile-pic" label="Choose Profile Picture"
            imgSrc="{{ isset($admin->profile_pic) ? getImageSource($admin->profile_pic) : Vite::asset('resources/images/profile_default.png') }}"
            imgClasses="size-65" :disabled="$isShow" />
    </div>
</form>

@if ($isShow || $isEdit || $isProfile)
    {{-- Change Password Form --}}
    <hr class="my-8 border-gray-400" />
    <x-layouts.inner-form>
        <x-slot name="title">Change Password</x-slot>
        <form method="post" action="{{ route('admin.management.verify', $admin->id) }}" class="mt-4 space-y-4 w-1/2">
            @csrf
            @method('PATCH')
            <x-form-field type="password" name="{{ $isProfile ? 'old' : 'your' }}_password" />
            <x-form-field type="password" name="new_password" />
            <x-form-field type="password" name="new_password_conformation" label="Confirm Password" />
            <x-form-field type="submit" value="Change Password" class="inline-block px-8 mt-4" />
        </form>
    </x-layouts.inner-form>
    {{-- Delete Form --}}
    <x-layouts.inner-form theme="red" class="mt-8">
        <x-slot name="title">Delete Admin</x-slot>
        <form method="post" action="{{ route('admin.management.destroy', $admin->id) }}" class="flex gap-6 mt-4">
            @csrf
            @method('DELETE')
            <x-form-field type="text" name="delete" label="Confirm Delete" class="inline-block"
                placeholder="Type 'DELETE' here..." />
            <x-form-field type="submit" value="Delete"
                class="inline-block px-8 py-1 mt-auto bg-red-600 hover:bg-red-700 hover:border-red-900" />
        </form>
    </x-layouts.inner-form>
@endif
