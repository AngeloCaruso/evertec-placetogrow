<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { router, usePage, Link } from '@inertiajs/vue3'
import { reactive, watch, ref } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'
import debounce from 'lodash/debounce';
import { useTrans } from '@/helpers/translate';

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

</script>

<template>
    <Layout>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <div class="hidden border-t border-white border-opacity-20 py-5 lg:block">
                <div class="grid grid-cols-3 items-center gap-8">
                    <div class="col-span-2">
                        <nav class="flex space-x-4">
                            <div v-for="item in navigation" :key="item.name">
                                <Link :href="`/microsites?type=${item.name}`" :only="['sites']"
                                    :class="[item.name == type ? 'bg-gray-200 text-gray-800' : 'text-white bg-opacity-0 hover:bg-opacity-10', 'capitalize rounded-md bg-white px-3 py-2 text-sm font-medium cursor-pointer']"
                                    :aria-current="item.current ? 'page' : undefined">
                                {{ useTrans(item.name) }}
                                <span
                                    :class="[item.name == type ? 'bg-gray-200 text-gray-600' : 'bg-gray-100 text-gray-500', 'ml-1 hidden rounded-full px-2.5 py-0.5 text-xs font-medium md:inline-block']">
                                    {{ item.count }}
                                </span>
                                </Link>
                            </div>
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
                                    :placeholder="useTrans('Search')" type="search" name="search" v-model="search" />
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
                            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
                                <div class="grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2 sm:gap-y-10 lg:grid-cols-3">
                                    <div v-for="site in microsites" :key="site.id">
                                        <div v-show="site.show"
                                            class="group relative before:absolute before:-inset-2.5 before:rounded-[15px] before:bg-gray-50 before:opacity-0 hover:before:opacity-100 cursor-pointer"
                                            @click="view(site.details_url)">
                                            <div
                                                class="aspect-h-9 aspect-w-16 overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-900/10">
                                                <img :src="site.logo" :alt="site.name"
                                                    class="object-contain px-1 py-1" />
                                            </div>
                                            <div
                                                class="mt-4 text-sm font-medium text-slate-900 group-hover:text-orange-600">
                                                <h3 class="flex justify-between">
                                                    <span class="absolute -inset-2.5 z-10"></span>
                                                    <span aria-hidden="true" class="relative">
                                                        {{ site.name }}
                                                    </span>
                                                    <span class="relative leading-none">{{ site.currency }}</span>
                                                </h3>
                                            </div>
                                            <p class="relative mt-1 text-sm text-gray-500 capitalize">{{
                                                useTrans(site.type) }}</p>
                                        </div>
                                    </div>
                                </div>
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
                                <span v-for="category in categories" :key="category" @click="filterSites(category)"
                                    :class="[category.active ? 'bg-orange-50 text-orange-600 ring-orange-600/10' : 'bg-gray-50 text-gray-600 ring-gray-500/10', 'capitalize inline-flex items-center cursor-pointer rounded-md px-2 py-1 mx-1 my-1 text-xs font-medium ring-1 ring-inset']">
                                    {{ category.name }}
                                </span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </Layout>
</template>