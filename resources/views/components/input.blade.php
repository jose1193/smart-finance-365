@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'px-4 py-2 border border-gray-300  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:border-gray-700  dark:focus:border-indigo-400 focus:outline-none focus:ring focus:ring-opacity-40',
]) !!}>
