<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { router, usePage, Link } from '@inertiajs/vue3'
import { reactive, watch, ref } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import debounce from 'lodash/debounce';
import { useTrans } from '@/helpers/translate';
import Notification from '@/Components/Notification.vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { FunnelIcon } from '@heroicons/vue/20/solid'
import { SBadge } from '@placetopay/spartan-vue';

defineProps({ sites: Object })

const page = usePage();
const urlParams = new URLSearchParams(window.location.search);
const type = urlParams.get('type') || 'All Sites';
const search = ref('')

let microsites = [...page.props.sites.data]

watch(search, debounce((value) => {
    let route = `/microsites?search=${value}`;

    if (type) {
        route = `/microsites?type=${type}&search=${value}`;
    }

    router.visit(route, { only: ['sites'] })
}, 500));

const navigation = [
    { name: 'All Sites', current: true, count: page.props.sites_data.data.total },
    ...page.props.sites_data.data.site_types.map(type => {
        return {
            name: type.name,
            current: false,
            count: type.total
        }
    })]

const siteCategories = [... new Set(microsites.flatMap(site => site.categories))]
const categories = reactive(siteCategories.map(cat => {
    return {
        name: cat,
        active: false
    }
}))

const view = (site_url) => {
    router.get(site_url);
}

function filterSites(category) {
    category.active = !category.active

    let activeCategories = categories.filter(cat => cat.active).map(cat => cat.name)

    if (activeCategories.length == 0) {
        microsites = [...page.props.sites.data]
        return
    }

    microsites = [...page.props.sites.data].filter(site => {
        return site.categories.some(cat => activeCategories.includes(cat))
    })
}

const mobileFiltersOpen = ref(false)

</script>

<template>
    <Layout>
        <div class="bg-white">
            <div>
                <!-- Mobile filter dialog -->
                <TransitionRoot as="template" :show="mobileFiltersOpen">
                    <Dialog class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                            enter-from="opacity-0" enter-to="opacity-100"
                            leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                            leave-to="opacity-0">
                            <div class="fixed inset-0 bg-black bg-opacity-25" />
                        </TransitionChild>

                        <div class="fixed inset-0 z-40 flex">
                            <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                enter-from="translate-x-full" enter-to="translate-x-0"
                                leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                leave-to="translate-x-full">
                                <DialogPanel
                                    class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                    <div class="flex items-center justify-between px-4">
                                        <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                        <button type="button"
                                            class="relative -mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                            @click="mobileFiltersOpen = false">
                                            <span class="absolute -inset-0.5" />
                                            <span class="sr-only">Close menu</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>

                                    <!-- Filters -->
                                    <div class="mt-4 border-t border-gray-200">
                                        <div class="mx-auto w-full max-w-md mb-5 mt-5">
                                            <label for="mobile-search" class="sr-only">Search</label>
                                            <div class="relative text-gray-900 focus-within:text-gray-900">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                                </div>
                                                <input id="mobile-search"
                                                    class="block w-full rounded-md border-0 bg-gray/20 py-1.5 pl-10 pr-3 text-gray placeholder:text-gray-900 focus:bg-gray focus:text-gray-900 focus:ring-0 focus:placeholder:text-gray-500 sm:text-sm sm:leading-6"
                                                    :placeholder="useTrans('Search')" type="search" name="search"
                                                    v-model="search" />
                                            </div>
                                        </div>

                                        <ul role="list"
                                            class="space-y-3 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900 px-4">
                                            <li v-for="item in navigation" :key="item.name">
                                                <Link :href="`/microsites?type=${item.name}`" :only="['sites']"
                                                    class="capitalize rounded-md bg-white py-2 text-sm font-medium cursor-pointer"
                                                    :aria-current="item.current ? 'page' : undefined">
                                                {{ useTrans(item.name) }}
                                                <SBadge color="primary" size="sm" pill> {{ item.count }} </SBadge>
                                                </Link>
                                            </li>
                                        </ul>

                                        <div class="space-y-10 divide-y divide-gray-200 py-6">
                                            <fieldset>
                                                <legend class="block text-sm font-medium text-gray-900 px-4">{{
                                                    useTrans('Categories') }}
                                                </legend>
                                                <div class="space-y-3 pt-6 px-6">
                                                    <div v-for="(category, idx) in categories" :key="category"
                                                        class="flex items-center">
                                                        <input :id="`filter-${category}-${idx}`" :name="`${category}[]`"
                                                            :value="category.name" type="checkbox"
                                                            :checked="category.active"
                                                            class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-white"
                                                            @click="filterSites(category)" />
                                                        <label :for="`filter-${category}-${idx}`"
                                                            class="ml-3 text-sm text-gray-600 capitalize">{{
                                                                category.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </Dialog>
                </TransitionRoot>

                <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">{{ useTrans('Microsites') }}</h1>
                        <div class="flex items-center">
                            <button type="button"
                                class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                                @click="mobileFiltersOpen = true">
                                <span class="sr-only">Filters</span>
                                <FunnelIcon class="h-5 w-5" aria-hidden="true" />
                            </button>
                        </div>
                    </div>

                    <section aria-labelledby="products-heading" class="pb-24 pt-6">
                        <h2 id="products-heading" class="sr-only">Products</h2>

                        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                            <!-- Filters -->
                            <div class="hidden lg:block">
                                <div class="mx-auto w-full max-w-md mb-5">
                                    <label for="mobile-search" class="sr-only">Search</label>
                                    <div class="relative text-gray-900 focus-within:text-gray-900">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                        </div>
                                        <input id="mobile-search"
                                            class="block w-full rounded-md border-0 bg-gray/20 py-1.5 pl-10 pr-3 text-gray placeholder:text-gray-900 focus:bg-gray focus:text-gray-900 focus:ring-0 focus:placeholder:text-gray-500 sm:text-sm sm:leading-6"
                                            :placeholder="useTrans('Search')" type="search" name="search"
                                            v-model="search" />
                                    </div>
                                </div>

                                <ul role="list"
                                    class="space-y-2 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900 px-4">
                                    <li v-for="item in navigation" :key="item.name">
                                        <Link :href="`/microsites?type=${item.name}`" :only="['sites']"
                                            class="capitalize rounded-md bg-white py-2 text-sm font-medium cursor-pointer"
                                            :aria-current="item.current ? 'page' : undefined">

                                        <SBadge :color="type === item.name ? 'gray' : 'white'">
                                            {{ useTrans(item.name) }} <SBadge color="gray" class="ml-1" border
                                                size="sm" pill> {{ item.count }} </SBadge>
                                        </SBadge>

                                        </Link>
                                    </li>
                                </ul>

                                <div class="space-y-10 divide-y divide-gray-200 py-6">
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-900 px-2">{{
                                            useTrans('Categories') }}
                                        </legend>
                                        <div class="space-y-3 pt-6 px-4">
                                            <div v-for="(category, idx) in categories" :key="category"
                                                class="flex items-center">
                                                <input :id="`filter-${category}-${idx}`" :name="`${category}[]`"
                                                    :value="category.name" type="checkbox" :checked="category.active"
                                                    class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-white"
                                                    @click="filterSites(category)" />
                                                <label :for="`filter-${category}-${idx}`"
                                                    class="ml-3 text-sm text-gray-600 capitalize">{{ category.name }}
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <!-- Product grid -->
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10  lg:col-span-3 lg:gap-x-8">
                                <a v-for="site in microsites" :key="site.id" class="text-sm">
                                    <div v-show="site.show" @click="view(site.details_url)"
                                        class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-100 hover:opacity-75 cursor-pointer">
                                        <img :src="site.logo" :alt="site.name"
                                            class="h-full w-full object-cover object-center" />
                                    </div>
                                    <h3 class="mt-4 font-medium text-gray-900">{{ site.name }}</h3>
                                    <p class="text-gray-500 capitalize">{{ useTrans(site.type) }}</p>
                                    <p class="mt-2 font-medium text-gray-900">{{ site.currency }}</p>
                                </a>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </Layout>
</template>