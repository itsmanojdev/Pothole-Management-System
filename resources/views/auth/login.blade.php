<x-layouts.app>
    <div class="h-full flex flex-col items-center">
        <x-logo class="size-34 text-gray-700" />
        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-sm p-6">
            <h1 class="heading text-center">Login</h1>
            <form x-data="{
                form: $form('post', '/login', {
                    primary: '{{ old('primary') }}',
                    password: ''
                }).setErrors({{ Js::from($errors->messages()) }}),
            }">
                @csrf
                <div class="flex flex-col gap-4 mt-6 text-sm">
                    <x-form-field type="text" name="primary" text="Email/Mobile No/Aadhaar No" required />
                    <x-form-field type="password" name="password" text="Password" required />
                    <x-form-field type="submit" text="LOG IN" class="py-2 mt-4 text-base tracking-wide" />
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
