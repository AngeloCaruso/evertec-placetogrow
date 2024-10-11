<script setup>
import {
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import LanguageSelector from '@/Components/LanguageSelector.vue';
import { useTrans } from '@/helpers/translate';
import { SPlacetopayLogo } from '@placetopay/spartan-vue';

</script>

<template>
    <div class="min-h-full">
        <header class="relative bg-white">
            <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="border-b border-gray-200">
                    <div class="flex h-20 items-center">
                        <button type="button" class="relative rounded-md bg-white p-2 text-gray-400 lg:hidden"
                            @click="mobileMenuOpen = true">
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open menu</span>
                            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Logo -->
                        <div class="ml-4 flex lg:ml-0">
                            <a href="/microsites">
                                <SPlacetopayLogo mode="base" size="none" width="200" />
                            </a>
                        </div>

                        <div class="ml-auto flex items-center">
                            <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                                <a href="/login" class="text-sm font-medium text-gray-700 hover:text-gray-800">
                                    {{ useTrans('Sign in') }}
                                </a>
                                <span class="h-6 w-px bg-gray-200" aria-hidden="true" />
                            </div>

                            <div class="hidden lg:ml-4 lg:flex">
                                <LanguageSelector />
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main >
            <slot></slot>
        </main>

        <footer aria-labelledby="footer-heading" class="bg-white">
            <h2 id="footer-heading" class="sr-only">Footer</h2>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="border-t border-gray-100 py-10 sm:flex sm:items-center sm:justify-between">
                    <div class="flex items-center justify-center text-sm text-gray-500">
                        <p class="ml-3 border-gray-200 pl-3"></p>
                    </div>
                    <p class="mt-6 text-center text-sm text-gray-500 sm:mt-0"> &copy; Bootcamp Evertec 2024. {{
                        useTrans('All rights reserved.') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>