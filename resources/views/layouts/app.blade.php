<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
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
            <div id="mobile-menu" class="relative z-50 lg:hidden hidden" role="dialog" aria-modal="true">
                <div id="backdrop" class="fixed inset-0 bg-gray-900/80 transition-opacity ease-linear duration-300 opacity-0" aria-hidden="true"></div>
                <div id="off-canvas" class="fixed inset-0 flex -translate-x-full transition ease-in-out duration-300 transform">
                    <div class="relative mr-16 flex w-full max-w-xs flex-1">
                        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                            <button id="close-button" type="button" class="-m-2.5 p-2.5">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2" style="background-color: #4B5A68;">
                            <div class="flex h-16 shrink-0 items-center mt-2">
                                <x-application-logo class="h-9 w-auto" />
                            </div>
                            <livewire:layout.sidebar-navigation />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto border-gray-200 bg-white px-6" style="background-color: #4B5A68;">
                    <div class="flex h-16 shrink-0 items-center mt-2">
                        <x-application-logo class="h-9 w-auto" />
                    </div>
                    <livewire:layout.sidebar-navigation />
                </div>
            </div>

            <!-- Main content -->
            <div class="lg:pl-72">
                <div class="sticky top-0 z-40 lg:hidden">
                    <button type="button" id="open-button" class="-m-2.5 ml-1.5 p-2.5 text-gray-700">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 6h16.5m-16.5 6h16.5" />
                        </svg>
                    </button>
                </div>
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
            @vite('resources/js/app.js')
        </main>
    </div>
</body>

</html>