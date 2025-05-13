@props(['mode' => 'create', 'admin' => []])

@php
    $isCreate = $mode == 'create';
    $isShow = $mode == 'show';
    $isEdit = $mode == 'edit';
@endphp

<form class="mt-6 flex gap-16">
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
