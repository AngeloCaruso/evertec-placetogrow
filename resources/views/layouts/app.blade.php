<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.title') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.title', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="https://www.evertecinc.com/wp-content/uploads/2020/07/cropped-favicon-evertec-32x32.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            <livewire:layout.sidebar-navigation />
            <!-- Main content -->
            <div class="lg:pl-72">
                <main>
                    <div class="py-10">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center">
                                <x-breadcrumbs />
                                <x-lang-selector />
                            </div>
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>

            @filamentScripts
            <!-- @vite('resources/js/app.js') -->
            @stack('scripts')
        </main>
    </div>
</body>

</html>