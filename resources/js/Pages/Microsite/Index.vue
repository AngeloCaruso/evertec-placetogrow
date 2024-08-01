<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { Link } from '@inertiajs/vue3'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisHorizontalIcon } from '@heroicons/vue/20/solid'
import { router } from '@inertiajs/vue3'
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import {
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
defineProps({ sites: Object })

const navigation = [
    { name: 'All Sites', href: '#', current: true },
    { name: 'Billing', href: '#', current: false },
    { name: 'Donation', href: '#', current: false },
    { name: 'Subscriptions', href: '#', current: false },
]

const view = (site_url) => {
    router.get(site_url);
}

</script>

<template>
    <Layout>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <div class="hidden border-t border-white border-opacity-20 py-5 lg:block">
                <div class="grid grid-cols-3 items-center gap-8">
                    <div class="col-span-2">
                        <nav class="flex space-x-4">
                            <a v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'text-white' : 'text-indigo-100', 'rounded-md bg-white bg-opacity-0 px-3 py-2 text-sm font-medium hover:bg-opacity-10']"
                                :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                        </nav>
                    </div>
                    <div>
                        <div class="mx-auto w-full max-w-md">
                            <label for="mobile-search" class="sr-only">Search</label>
                            <div class="relative text-white focus-within:text-gray-600">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                </div>
                                <input id="mobile-search"
                                    class="block w-full rounded-md border-0 bg-white/20 py-1.5 pl-10 pr-3 text-white placeholder:text-white focus:bg-white focus:text-gray-900 focus:ring-0 focus:placeholder:text-gray-500 sm:text-sm sm:leading-6"
                                    placeholder="Search" type="search" name="search" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="sr-only">Page title</h1>
            <!-- Main 3 column grid -->
            <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8">
                <!-- Left column -->
                <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                    <section aria-labelledby="section-1-title">
                        <h2 class="sr-only" id="section-1-title">Section title</h2>
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="p-6">
                                <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
                                    <li v-for="site in sites.data" :key="site.id"
                                        class="p-1 flex flex-wrap items-center justify-center cursor-pointer"
                                        @click="view(site.details_url)">

                                        <div
                                            class="flex-shrink-0 relative overflow-hidden bg-orange-500 rounded-lg max-w-xs shadow-lg">
                                            <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                                                style="transform: scale(1.5); opacity: 0.1;">
                                                <rect x="159.52" y="175" width="152" height="152" rx="8"
                                                    transform="rotate(-45 159.52 175)" fill="white" />
                                                <rect y="107.48" width="152" height="152" rx="8"
                                                    transform="rotate(-45 0 107.48)" fill="white" />
                                            </svg>

                                            <div class="relative pt-10 px-10 flex items-center justify-center">
                                                <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                                                    style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;">
                                                </div>
                                                <img :src="site.logo" :alt="site.name" class="relative w-40"
                                                    style="aspect-ratio: 1;" />
                                            </div>
                                            <div class="relative text-white px-6 pb-6 mt-6">
                                                <span class="block opacity-75 -mb-1">{{ site.type }}</span>
                                                <div class="flex justify-between">
                                                    <span class="block font-semibold text-xl">{{ site.name }}</span>
                                                    <span
                                                        class="block bg-white rounded-full text-orange-500 text-xs font-bold px-3 py-2 leading-none flex items-center">{{
                                                            site.currency }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right column -->
                <div class="grid grid-cols-1 gap-4">
                    <section aria-labelledby="section-2-title">
                        <h2 class="sr-only" id="section-2-title">Section title</h2>
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="p-6">
                                Query all microsites and merge all the categories into one array
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </Layout>
</template>