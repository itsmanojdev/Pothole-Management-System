<x-layouts.app>
    <div class="h-full flex flex-col items-center">
        <x-logo class="size-34 text-gray-700" />
        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-sm p-6">
            <h1 class="heading text-center">Register</h1>
            <form x-data="{
                form: $form('post', '/register', {
                    name: '{{ old('name') }}',
                    email: '{{ old('email') }}',
                    mobile_number: '{{ old('mobile_number') }}',
                    aadhaar_number: '{{ old('aadhaar_number') }}',
                    password: '',
                    password_confirmation: ''
                }).setErrors({{ Js::from($errors->messages()) }}),
            }">
                @csrf
                <div class="flex flex-col gap-4 mt-6 text-sm">
                    <x-form-field type="text" name="name" text="Name" required />
                    <x-form-field type="text" name="email" text="Email" required />
                    <x-form-field type="text" name="mobile_number" text="Mobile Number" required />
                    <x-form-field type="text" name="aadhaar_number" text="Aadhaar Number" required />
                    <x-form-field type="password" name="password" text="Password" required />
                    <x-form-field type="password" name="password_confirmation" text="Confirm Password" required />
                    <x-form-field type="submit" value="Register" class="mt-4 tracking-wide" />
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
