<x-layouts.app>
    <x-slot name="title">
        Profile Page
    </x-slot>

    <x-forms.user mode="{{ isset($isEdit) && $isEdit ? 'edit' : 'show' }}" :user="$user" />
</x-layouts.app>
