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

</script>

<template>
    <div class="min-h-full">
        <Popover as="header" class="pb-24" style="background-color: #4B5A68;" v-slot="{ open }">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="relative flex items-center justify-center py-5 lg:justify-between">
                    <div class="absolute left-0 flex-shrink-0 lg:static">
                        <a href="/microsites">
                            <span class="sr-only">Bootcamp Evertec 2024</span>
                            <img class="h-8 w-auto"
                                src="https://www.evertecinc.com/wp-content/uploads/2020/07/logo-evertec-footer.png"
                                alt="Bootcamp Evertec 2024" />
                        </a>
                    </div>

                    <div class="hidden lg:ml-4 lg:flex lg:items-center lg:pr-0.5">
                        <a href="/login">
                            <span class="text-white ml-2">Login</span>
                        </a>
                    </div>

                    <div class="min-w-0 flex-1 px-12 lg:hidden">
                        <div class="mx-auto w-full max-w-xs">
                            <label for="desktop-search" class="sr-only">Search</label>
                            <div class="relative text-white focus-within:text-gray-600">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                </div>
                                <input id="desktop-search"
                                    class="block w-full rounded-md border-0 bg-white/20 py-1.5 pl-10 pr-3 text-white placeholder:text-white focus:bg-white focus:text-gray-900 focus:ring-0 focus:placeholder:text-gray-500 sm:text-sm sm:leading-6"
                                    placeholder="Search" type="search" name="search" />
                            </div>
                        </div>
                    </div>

                    <div class="absolute right-0 flex-shrink-0 lg:hidden">
                        <PopoverButton
                            class="relative inline-flex items-center justify-center rounded-md bg-transparent p-2 text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </PopoverButton>
                    </div>
                </div>
            </div>

            <TransitionRoot as="template" :show="open">
                <div class="lg:hidden">
                    <TransitionChild as="template" enter="duration-150 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-150 ease-in" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <PopoverOverlay class="fixed inset-0 z-20 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <TransitionChild as="template" enter="duration-150 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-150 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <PopoverPanel focus
                            class="absolute inset-x-0 top-0 z-30 mx-auto w-full max-w-3xl origin-top transform p-2 transition">
                            <div
                                class="divide-y divide-gray-200 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                <div class="pb-2 pt-3">
                                    <div class="flex items-center justify-between px-4">
                                        <div>
                                            <img class="h-8 w-auto"
                                                src="https://www.evertecinc.com/wp-content/uploads/2020/07/logo-evertec-footer.png"
                                                alt="Bootcamp Evertec 2024" />
                                        </div>
                                        <div class="-mr-2">
                                            <PopoverButton
                                                class="relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                                <span class="absolute -inset-0.5" />
                                                <span class="sr-only">Close menu</span>
                                                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                            </PopoverButton>
                                        </div>
                                    </div>
                                    <div class="mt-3 space-y-1 px-2">
                                        <a href="/login" class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800">Login</a>
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </TransitionChild>
                </div>
            </TransitionRoot>
        </Popover>
        <main class="-mt-24 pb-8">
            <slot></slot>
        </main>
        <footer>
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="border-t border-gray-200 py-8 text-center text-sm text-gray-500 sm:text-left"><span
                        class="block sm:inline">&copy; Bootcamp Evertec 2024.</span> <span class="block sm:inline">All
                        rights reserved.</span></div>
            </div>
        </footer>
    </div>
</template>