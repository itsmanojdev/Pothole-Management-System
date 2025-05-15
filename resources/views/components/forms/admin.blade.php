@props(['mode' => 'create', 'admin' => []])

@php
    $isCreate = $mode == 'create';
    $isShow = $mode == 'show';
    $isEdit = $mode == 'edit';

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
        <x-form-field type="password" name="password" :disabled="$isShow" required />
        <x-form-field type="password" name="password_confirmation" label="Confirm Password" :disabled="$isShow"
            required />
        <x-form-field type="submit" value="Create Admin" class="px-16 mt-6" />

    </div>
    <div class="mt-2">
        <x-form-field type="img-file" name="profile-pic" label="Choose Profile Picture"
            imgSrc="{{ isset($admin->profile_pic) ? getImageSource($admin->profile_pic) : Vite::asset('resources/images/profile_default.png') }}"
            imgClasses="size-65" :disabled="$isShow" />
    </div>
</form>
