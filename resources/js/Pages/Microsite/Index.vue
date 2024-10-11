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
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverGroup,
    PopoverPanel,
    Tab,
    TabGroup,
    TabList,
    TabPanel,
    TabPanels,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Bars3Icon, ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import { SBadge, SPlacetopayLogo } from '@placetopay/spartan-vue';

const navigations = {
    categories: [
        {
            id: 'women',
            name: 'Women',
            featured: [
                {
                    name: 'New Arrivals',
                    href: '#',
                    imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/mega-menu-category-01.jpg',
                    imageAlt: 'Models sitting back to back, wearing Basic Tee in black and bone.',
                },
                {
                    name: 'Basic Tees',
                    href: '#',
                    imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/mega-menu-category-02.jpg',
                    imageAlt: 'Close up of Basic Tee fall bundle with off-white, ochre, olive, and black tees.',
                },
            ],
            sections: [
                {
                    id: 'clothing',
                    name: 'Clothing',
                    items: [
                        { name: 'Tops', href: '#' },
                        { name: 'Dresses', href: '#' },
                        { name: 'Pants', href: '#' },
                        { name: 'Denim', href: '#' },
                        { name: 'Sweaters', href: '#' },
                        { name: 'T-Shirts', href: '#' },
                        { name: 'Jackets', href: '#' },
                        { name: 'Activewear', href: '#' },
                        { name: 'Browse All', href: '#' },
                    ],
                },
                {
                    id: 'accessories',
                    name: 'Accessories',
                    items: [
                        { name: 'Watches', href: '#' },
                        { name: 'Wallets', href: '#' },
                        { name: 'Bags', href: '#' },
                        { name: 'Sunglasses', href: '#' },
                        { name: 'Hats', href: '#' },
                        { name: 'Belts', href: '#' },
                    ],
                },
                {
                    id: 'brands',
                    name: 'Brands',
                    items: [
                        { name: 'Full Nelson', href: '#' },
                        { name: 'My Way', href: '#' },
                        { name: 'Re-Arranged', href: '#' },
                        { name: 'Counterfeit', href: '#' },
                        { name: 'Significant Other', href: '#' },
                    ],
                },
            ],
        },
        {
            id: 'men',
            name: 'Men',
            featured: [
                {
                    name: 'New Arrivals',
                    href: '#',
                    imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/product-page-04-detail-product-shot-01.jpg',
                    imageAlt: 'Drawstring top with elastic loop closure and textured interior padding.',
                },
                {
                    name: 'Artwork Tees',
                    href: '#',
                    imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/category-page-02-image-card-06.jpg',
                    imageAlt:
                        'Three shirts in gray, white, and blue arranged on table with same line drawing of hands and shapes overlapping on front of shirt.',
                },
            ],
            sections: [
                {
                    id: 'clothing',
                    name: 'Clothing',
                    items: [
                        { name: 'Tops', href: '#' },
                        { name: 'Pants', href: '#' },
                        { name: 'Sweaters', href: '#' },
                        { name: 'T-Shirts', href: '#' },
                        { name: 'Jackets', href: '#' },
                        { name: 'Activewear', href: '#' },
                        { name: 'Browse All', href: '#' },
                    ],
                },
                {
                    id: 'accessories',
                    name: 'Accessories',
                    items: [
                        { name: 'Watches', href: '#' },
                        { name: 'Wallets', href: '#' },
                        { name: 'Bags', href: '#' },
                        { name: 'Sunglasses', href: '#' },
                        { name: 'Hats', href: '#' },
                        { name: 'Belts', href: '#' },
                    ],
                },
                {
                    id: 'brands',
                    name: 'Brands',
                    items: [
                        { name: 'Re-Arranged', href: '#' },
                        { name: 'Counterfeit', href: '#' },
                        { name: 'Full Nelson', href: '#' },
                        { name: 'My Way', href: '#' },
                    ],
                },
            ],
        },
    ],
    pages: [
        { name: 'Company', href: '#' },
        { name: 'Stores', href: '#' },
    ],
}
const sortOptions = [
    { name: 'Most Popular', href: '#', current: true },
    { name: 'Best Rating', href: '#', current: false },
    { name: 'Newest', href: '#', current: false },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
]
const subCategories = [
    { name: 'Totes', href: '#' },
    { name: 'Backpacks', href: '#' },
    { name: 'Travel Bags', href: '#' },
    { name: 'Hip Bags', href: '#' },
    { name: 'Laptop Sleeves', href: '#' },
]
const filters = [
    {
        id: 'color',
        name: 'Color',
        options: [
            { value: 'white', label: 'White', checked: false },
            { value: 'beige', label: 'Beige', checked: false },
            { value: 'blue', label: 'Blue', checked: true },
            { value: 'brown', label: 'Brown', checked: false },
            { value: 'green', label: 'Green', checked: false },
            { value: 'purple', label: 'Purple', checked: false },
        ],
    },
    {
        id: 'category',
        name: 'Category',
        options: [
            { value: 'new-arrivals', label: 'New Arrivals', checked: false },
            { value: 'sale', label: 'Sale', checked: false },
            { value: 'travel', label: 'Travel', checked: true },
            { value: 'organization', label: 'Organization', checked: false },
            { value: 'accessories', label: 'Accessories', checked: false },
        ],
    },
    {
        id: 'size',
        name: 'Size',
        options: [
            { value: '2l', label: '2L', checked: false },
            { value: '6l', label: '6L', checked: false },
            { value: '12l', label: '12L', checked: false },
            { value: '18l', label: '18L', checked: false },
            { value: '20l', label: '20L', checked: false },
            { value: '40l', label: '40L', checked: true },
        ],
    },
]
const products = [
    {
        id: 1,
        name: 'Nomad Pouch',
        href: '#',
        price: '$50',
        availability: 'White and Black',
        imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/category-page-07-product-01.jpg',
        imageAlt: 'White fabric pouch with white zipper, black zipper pull, and black elastic loop.',
    },
    {
        id: 2,
        name: 'Zip Tote Basket',
        href: '#',
        price: '$140',
        availability: 'Washed Black',
        imageSrc: 'https://tailwindui.starxg.com/plus/img/ecommerce-images/category-page-07-product-02.jpg',
        imageAlt: 'Front of tote bag with washed black canvas body, black straps, and tan leather handles and accents.',
    },
    // More products...
]
const footerNavigation = {
    account: [
        { name: 'Manage Account', href: '#' },
        { name: 'Saved Items', href: '#' },
        { name: 'Orders', href: '#' },
        { name: 'Redeem Gift card', href: '#' },
    ],
    service: [
        { name: 'Shipping & Returns', href: '#' },
        { name: 'Warranty', href: '#' },
        { name: 'FAQ', href: '#' },
        { name: 'Find a store', href: '#' },
        { name: 'Get in touch', href: '#' },
    ],
    company: [
        { name: 'Who we are', href: '#' },
        { name: 'Press', href: '#' },
        { name: 'Careers', href: '#' },
        { name: 'Terms & Conditions', href: '#' },
        { name: 'Privacy', href: '#' },
    ],
    connect: [
        { name: 'Facebook', href: '#' },
        { name: 'Instagram', href: '#' },
        { name: 'Pinterest', href: '#' },
    ],
}

const mobileMenuOpen = ref(false)
const mobileFiltersOpen = ref(false)

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
        <div class="bg-white">
            <div>
                <!-- Mobile menu -->
                <TransitionRoot as="template" :show="mobileMenuOpen">
                    <Dialog class="relative z-40 lg:hidden" @close="mobileMenuOpen = false">
                        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                            enter-from="opacity-0" enter-to="opacity-100"
                            leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                            leave-to="opacity-0">
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

                                    <!-- Links -->
                                    <TabGroup as="div" class="mt-2">
                                        <div class="border-b border-gray-200">
                                            <TabList class="-mb-px flex space-x-8 px-4">
                                                <Tab as="template" v-for="category in navigation.categories"
                                                    :key="category.name" v-slot="{ selected }">
                                                    <button
                                                        :class="[selected ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-900', 'flex-1 whitespace-nowrap border-b-2 px-1 py-4 text-base font-medium']">{{
                                                            category.name }}</button>
                                                </Tab>
                                            </TabList>
                                        </div>
                                        <TabPanels as="template">
                                            <TabPanel v-for="category in navigation.categories" :key="category.name"
                                                class="space-y-10 px-4 pb-8 pt-10">
                                                <div class="grid grid-cols-2 gap-x-4">
                                                    <div v-for="item in category.featured" :key="item.name"
                                                        class="group relative text-sm">
                                                        <div
                                                            class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg bg-gray-100 group-hover:opacity-75">
                                                            <img :src="item.imageSrc" :alt="item.imageAlt"
                                                                class="object-cover object-center" />
                                                        </div>
                                                        <a :href="item.href"
                                                            class="mt-6 block font-medium text-gray-900">
                                                            <span class="absolute inset-0 z-10" aria-hidden="true" />
                                                            {{ item.name }}
                                                        </a>
                                                        <p aria-hidden="true" class="mt-1">Shop now</p>
                                                    </div>
                                                </div>
                                                <div v-for="section in category.sections" :key="section.name">
                                                    <p :id="`${category.id}-${section.id}-heading-mobile`"
                                                        class="font-medium text-gray-900">{{ section.name }}</p>
                                                    <ul role="list"
                                                        :aria-labelledby="`${category.id}-${section.id}-heading-mobile`"
                                                        class="mt-6 flex flex-col space-y-6">
                                                        <li v-for="item in section.items" :key="item.name"
                                                            class="flow-root">
                                                            <a :href="item.href" class="-m-2 block p-2 text-gray-500">{{
                                                                item.name }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </TabPanel>
                                        </TabPanels>
                                    </TabGroup>

                                    <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                                        <div v-for="page in navigation.pages" :key="page.name" class="flow-root">
                                            <a :href="page.href" class="-m-2 block p-2 font-medium text-gray-900">{{
                                                page.name }}</a>
                                        </div>
                                    </div>

                                    <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                                        <div class="flow-root">
                                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Sign in</a>
                                        </div>
                                        <div class="flow-root">
                                            <a href="#" class="-m-2 block p-2 font-medium text-gray-900">Create
                                                account</a>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 px-4 py-6">
                                        <a href="#" class="-m-2 flex items-center p-2">
                                            <img src="https://tailwindui.starxg.com/plus/img/flags/flag-canada.svg"
                                                alt="" class="block h-auto w-5 flex-shrink-0" />
                                            <span class="ml-3 block text-base font-medium text-gray-900">CAD</span>
                                            <span class="sr-only">, change currency</span>
                                        </a>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </Dialog>
                </TransitionRoot>

                <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">{{ useTrans('Microsites') }}</h1>

                        <Menu as="div" class="relative inline-block text-left">
                            <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                        <a :href="option.href"
                                            :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{
                                                option.name }}</a>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                        <div class="flex items-center">
                            <button type="button" class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7">
                                <span class="sr-only">View grid</span>
                                <Squares2X2Icon class="h-5 w-5" aria-hidden="true" />
                            </button>
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
                                    class="space-y-4 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900">
                                    <li v-for="item in navigation" :key="item.name">
                                        <Link :href="`/microsites?type=${item.name}`" :only="['sites']"
                                            :class="[item.name == type ? 'bg-gray-200' : 'text-gray bg-opacity-0 hover:bg-opacity-10', 'capitalize rounded-md bg-white px-3 py-2 text-sm font-medium cursor-pointer']"
                                            :aria-current="item.current ? 'page' : undefined">
                                        {{ useTrans(item.name) }}
                                        <SBadge color="primary" size="sm" pill> {{ item.count }} </SBadge>
                                        </Link>
                                    </li>
                                </ul>

                                <div class="space-y-10 divide-y divide-gray-200 py-6">
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-gray-900">{{
                                            useTrans('Categories') }}
                                        </legend>
                                        <div class="space-y-3 pt-6">
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
                            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-3 lg:col-span-3 lg:gap-x-8">
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