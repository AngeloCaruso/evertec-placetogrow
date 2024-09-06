<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50">
                <img v-if="usePage().props.locale == 'es'" width="25" height="25"
                    src="https://img.icons8.com/color/25/spain-circular.png" alt="spain-circular" />
                <img v-if="usePage().props.locale == 'en'" width="25" height="25"
                    src="https://img.icons8.com/color/25/usa-circular.png" alt="usa-circular" />
                <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
            </MenuButton>
        </div>

        <transition enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="py-1">
                    <MenuItem v-slot="{ active }">
                    <Link href="/locale/es"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm flex']">
                    <img width="30" height="30" src="https://img.icons8.com/color/30/spain-circular.png"
                        alt="spain-circular" />
                    <span class="mt-2 pl-2">Español</span>
                    </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <Link href="/locale/en"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm flex']">
                    <img width="30" height="30" src="https://img.icons8.com/color/30/usa-circular.png"
                        alt="usa-circular" />
                    <span class="mt-2 pl-2">English</span>
                    </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { Link, usePage } from '@inertiajs/vue3';
</script>