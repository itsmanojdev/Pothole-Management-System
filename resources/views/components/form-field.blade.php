@props([
    'text',
    'required' => false,
    'enableCrtValidation' => true,
    'disabled' => false,
    'imgSrc' => '', // For image file
    'options' => [], //For dropdown & radio btn. Form: 'option' => isDisabled (option specific disabling)
    'optionFlex' => 'row', // For dropdown & radio options
    'disablePrevOption' => false, // For dropdown, if enabled options before the selected option will be disabled
    'currOpt', // For dropdown, Currently selected option
])

@php
    $inputCommonClass =
        'w-full bg-gray-50 border border-gray-400 px-2 py-2 mt-1 rounded-md outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500 animate ' .
        ($disabled ? 'bg-gray-100 text-gray-600' : '');
    $buttonCommonClass =
        'bg-primary-normal text-gray-100 font-medium px-4 py-2 text-base text-center rounded-sm cursor-pointer border-1 hover:border-primary-xdark hover:bg-primary-dark active:bg-primary-xdark animate';

    $inputType = $attributes->get('type');
    $name = $attributes->get('name');
@endphp

@switch($inputType)
    @case('text')
    @case('password')
        <div>
            @if (isset($text))
                <label for={{ $name }} @class(['block', 'ast' => $required])>{{ $text }}</label>
            @endif

            <input {{ $attributes->merge(['class' => $inputCommonClass, 'id' => "$name"]) }}
                :class="form.invalid('{{ $name }}') ?
                    'border-red-400 focus:ring-red-400 focus:border-red-400' :
                    (form.valid('{{ $name }}') ?
                        ({{ $enableCrtValidation }} && 'border-green-400') :
                        @if (!$errors->first($name) && old($name) != '' && $enableCrtValidation) 'border-green-400' @else '' @endif)"
                x-model="form.{{ $name }}"
                @if ($inputType == 'password') @input="form.validate('{{ $name }}')"
                @else
                    @change="form.validate('{{ $name }}')" @endif
                @disabled($disabled) />
            <template x-if="form.invalid('{{ $name }}')">
                <p class="text-red-700 italic text-xs font-medium mt-1" x-text="form.errors.{{ $name }}"></p>
            </template>
        </div>
    @break

    @case('hidden')
        <div>
            <input {{ $attributes->merge(['id' => "$name"]) }} x-model="form.{{ $name }}" />
        </div>
    @break

    @case('textarea')
        <div>
            <label for={{ $name }} @class(['block', 'ast' => $required])>{{ $text }}</label>
            <textarea {{ $attributes->merge(['class' => $inputCommonClass, 'id' => "$name"]) }}
                :class="form.invalid('{{ $name }}') ?
                    'border-red-400 focus:ring-red-400 focus:border-red-400' :
                    (form.valid('{{ $name }}') ?
                        ({{ $enableCrtValidation }} && 'border-green-400') :
                        @if (!$errors->first($name) && old($name) != '' && $enableCrtValidation) 'border-green-400' @else '' @endif)"
                x-model="form.{{ $name }}" @change="form.validate('{{ $name }}')" @disabled($disabled)></textarea>
            <template x-if="form.invalid('{{ $name }}')">
                <p class="text-red-700 italic text-xs font-medium mt-1" x-text="form.errors.{{ $name }}"></p>
            </template>
        </div>
    @break

    @case('dropdown')
        <div id="{{ $name }}-dropdown-wrapper">
            <label for={{ $name }} @class([
                'block' => $optionFlex == 'col' && true,
                'mr-3',
                'ast' => $required,
            ])>{{ $text }}</label>
            <select
                {{ $attributes->merge(['class' => 'px-4 py-2 bg-gray-50 border-2 border-gray-400 outline-none rounded-sm shadow-md cursor-pointer hover:border-gray-500 active:border-gray-800', 'id' => "$name"]) }}
                @disabled($disabled)>
                @foreach ($options as $key => $value)
                    @php
                        $isOptDisabled = $value === true;
                        $option = is_bool($value) ? $key : $value;

                        $isDisabled = $isOptDisabled || $disablePrevOption;
                        if (!$isOptDisabled && $disablePrevOption && $option == $currOpt) {
                            $isDisabled = $disablePrevOption = false;
                        }
                    @endphp
                    <option value="{{ $option }}" @selected(request($name) == $option || $option == (isset($currOpt) ? $currOpt : $options[0])) @disabled($isDisabled)>
                        {{ titleCase($option) }}
                    </option>
                @endforeach
            </select>
        </div>
    @break

    @case('dropdown-input')
        <div {{ $attributes->merge(['class' => 'relative']) }} id="{{ $name }}-dropdown-wrapper">
            <x-form-field type="text" name="{{ $name }}" text="Address" id="{{ $name }}-input"
                autocomplete="off" enableCrtValidation=false :disabled="$disabled" />

            <ul id="{{ $name }}-dropdown-list"
                class="absolute w-full bg-white rounded mt-1 max-h-40 overflow-y-auto z-10">
                <!-- Dynamically inserted options here -->
            </ul>
        </div>
    @break

    @case('checkbox')
        <div>
            <input {{ $attributes->merge(['class' => '', 'id' => $name]) }} @disabled($disabled) />
            <label for={{ $name }} @class(['ml-3'])>{{ $text }}</label>
        </div>
    @break

    @case('radio')
        <fieldset id="{{ $name }}-wrapper"
            class="border-2 border-gray-400 px-6 py-2 pr-10 rounded-lg shadow-sm hover:border-gray-500">
            <legend class="font-semibold px-2">{{ $text }}</legend>
            <div class="flex {{ $optionFlex == 'row' ? 'space-x-10' : 'flex-col spcace-y-1' }}">
                @foreach ($options as $key => $value)
                    @php
                        $isOptDisabled = $value === true;
                        $option = is_bool($value) ? $key : $value;
                    @endphp
                    <div>
                        <input {{ $attributes->merge(['id' => "$option", 'value' => "$option"]) }}
                            @checked(request($name) == $option || $option == $options[0]) @disabled($isOptDisabled || $disabled) />
                        <label for={{ $option }}>{{ titleCase($option) }}</abel>
                    </div>
                @endforeach
            </div>
        </fieldset>
    @break

    @case('img-file')
        @php
            $title = titleCase($name);
        @endphp

        <div class="flex flex-col gap-1">
            <div id="{{ $name }}-preview" class="w-75 h-50">
                @if ($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $title }}" class="image-cover rounded-lg">
                @else
                    <img src="https://placehold.co/300x200" alt="Default {{ $title }}" class="image-cover rounded-lg">
                @endif
            </div>
            @if (!$disabled)
                <label for={{ $name }}
                    {{ $attributes->merge(['class' => $buttonCommonClass]) }}>{{ $text }}</label>
                <input type="file" {{ $attributes->merge(['id' => $name]) }} class="w-0 h-0 opacity-0"
                    accept=".jpeg,.jpg,.png" />
            @endif
        </div>
        @pushOnce('scripts')
            <script>
                window.addEventListener("load", () => {
                    @if ($errors->has($name))
                        toast('error', "{{ $errors->first($name) }}")
                    @endif
                    @if (!$disabled)
                        const input = document.querySelector("#{{ $name }}");
                        input.imgSrc =
                            @if ($imgSrc)
                                "{{ $imgSrc }}"
                            @else
                                "https://placehold.co/300x200"
                            @endif ;
                        input.addEventListener("change", updateImagePreview);
                    @endif
                })
            </script>
        @endPushOnce
    @break

    @case('submit')
        <input type="submit" {{ $attributes->merge(['class' => $buttonCommonClass]) }} :disabled="form.processing" />
    @break

    @default
@endswitch
