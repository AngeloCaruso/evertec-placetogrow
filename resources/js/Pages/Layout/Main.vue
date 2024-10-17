<script setup>
import {
    TransitionChild,
    TransitionRoot,
    Dialog,
    DialogPanel,
} from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
import LanguageSelector from '@/Components/LanguageSelector.vue';
import { useTrans } from '@/helpers/translate';
import { SPlacetopayLogo } from '@placetopay/spartan-vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)
const showFooter = ref(true)

</script>

<template>
    <div class="flex flex-col min-h-screen">
        <!-- Mobile menu -->
        <TransitionRoot as="template" :show="mobileMenuOpen">
            <Dialog class="relative z-40 lg:hidden" @close="mobileMenuOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full" enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                        leave-to="-translate-x-full">
                        <DialogPanel
                            class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
                            <div class="flex px-4 pb-2 pt-5">
                                <button type="button"
                                    class="relative -m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400"
                                    @click="mobileMenuOpen = false">
                                    <span class="absolute -inset-0.5" />
                                    <span class="sr-only">Close menu</span>
                                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                </button>
                            </div>

                            <!-- Logo -->
                            <div class="py-5 px-4 flex lg:ml-0 mb-8">
                                <a href="/microsites">
                                    <SPlacetopayLogo mode="base" size="none" width="200" />
                                </a>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <a href="/login" class="text-lg font-medium text-gray-700 hover:text-gray-800 px-4">
                                        {{ useTrans('Sign in') }}
                                    </a>
                                    <span class="h-6 w-px bg-gray-200" aria-hidden="true" />
                                </div>

                                <LanguageSelector />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <header class="relative bg-white">
            <p
                class="flex h-2 items-center justify-center bg-orange-500 px-4 text-sm font-medium text-white sm:px-6 lg:px-8">
            </p>

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
                            <SPlacetopayLogo mode="base" size="none" width="200"
                                @click="() => router.get('/microsites')" class="cursor-pointer" />
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

        <main>
            <slot></slot>
        </main>

        <footer aria-labelledby="footer-heading" :class="[showFooter ? 'bg-white mt-auto w-full' : 'hidden']">
            <h2 id="footer-heading" class="sr-only">Footer</h2>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="border-t border-gray-100 py-8 sm:flex sm:items-center sm:justify-between">
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