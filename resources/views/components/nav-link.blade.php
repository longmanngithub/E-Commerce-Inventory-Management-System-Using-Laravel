@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'inline-flex items-center px-3 py-3 bg-blue-500/10 dark:bg-blue-400/20 rounded-xl text-lg font-bold leading-5 text-blue-500 dark:text-blue-400 focus:outline-none focus:border-blue-400/20 transition duration-150 ease-in-out'
                : 'inline-flex items-center px-3 py-3 border-2 rounded-xl border-transparent text-lg font-medium leading-5 text-gray-900 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
