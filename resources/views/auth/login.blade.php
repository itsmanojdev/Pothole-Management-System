<x-layouts.app>
    <div class="h-full flex flex-col items-center gap-10">
        <x-logo class="size-30 text-gray-700" />
        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-sm p-6">
            <h1 class="heading text-center">Login</h1>
            <form x-data="{
                form: $form('post', '{{ route('login.store') }}', {
                    primary: '{{ old('primary') }}',
                    password: ''
                }).setErrors({{ Js::from($errors->messages()) }}),
            }">
                @csrf
                <div class="flex flex-col gap-4 mt-6 text-sm">
                    <x-form-field type="text" name="primary" label="Email/Mobile No/Aadhaar No" required />
                    <x-form-field type="password" name="password" required />
                    <x-form-field type="submit" value="LOG IN" class="py-2 mt-4 text-base tracking-wide" />
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
