<template>
    <!-- Main 3 column grid -->
    <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8">
        <!-- Left column -->
        <div class="grid grid-cols-1 gap-4 lg:col-span-2">
            <section aria-labelledby="section-1-title">
                <h2 class="sr-only" id="section-1-title">Section title</h2>
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-6">
                        <form @submit.prevent="submit" id="payment-form" method="post"
                            class="bg-white sm:rounded-xl md:col-span-2">
                            <div class="px-4 py-6 sm:p-8">
                                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div v-for="(field, index) in payment.payment_data" class="sm:col-span-3">
                                        <SelectInput v-if="field.is_select" :input="field" :index="index"
                                            :errors="errors" />
                                        <TextInput v-if="field.type === 'text'" :input="field" :index="index"
                                            :errors="errors" />
                                    </div>
                                </div>
                                <div
                                    class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 mt-6 pt-4 border-t border-gray-900/10">
                                    <div class="sm:col-span-3">
                                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ useTrans("Email address") }}
                                        </label>
                                        <div class="mt-2">
                                            <input type="email" name="email" id="email" v-model="payment.email"
                                                autocomplete="email" :placeholder="useTrans('Email address')"
                                                :class="[errors.email ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
                                        </div>
                                        <p class="text-sm text-red-600" v-if="errors.email">
                                            {{ errors.email }}
                                        </p>
                                    </div>
                                    <div class="sm:col-span-3"></div>
                                    <div class="sm:col-span-3">
                                        <div v-if="site.data.type === 'billing'">
                                            <label for="reference"
                                                class="block text-sm font-medium leading-6 text-gray-900">
                                                {{ useTrans("Reference") }}
                                            </label>
                                            <div class="mt-2">
                                                <input type="text" name="reference" id="reference"
                                                    v-model="payment.reference" autocomplete="reference"
                                                    :placeholder="useTrans('Reference')"
                                                    :class="[errors.reference ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
                                            </div>
                                            <p class="text-sm text-red-600" v-if="errors.reference">
                                                {{ errors.reference }}
                                            </p>
                                        </div>
                                        <div v-if="site.data.type === 'donation'">
                                            <label for="price"
                                                class="block text-sm font-medium leading-6 text-gray-900">
                                                {{ useTrans('Amount') }}
                                            </label>
                                            <div class="relative mt-2 rounded-md shadow-sm">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="text" name="price" id="price" v-model="payment.amount"
                                                    :class="[errors.amount ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-orange-600', 'text-gray-900 placeholder:text-gray-400 block w-full rounded-md border-0 py-1.5 pl-7 pr-12 ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']"
                                                    placeholder="0.00" aria-describedby="price-currency" />
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <span class="text-gray-500 sm:text-sm" id="price-currency">
                                                        {{ site.data.currency }}
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-sm text-red-600" v-if="errors.amount">
                                                {{ errors.amount }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="gateway" class="block text-sm font-medium leading-6 text-gray-900">
                                            {{ useTrans('Gateway') }}
                                        </label>
                                        <div class="mt-2">
                                            <select id="gateway" name="gateway" autocomplete="gateway-name"
                                                v-model="payment.gateway"
                                                :class="[errors.gateway ? 'ring-red-300 placeholder:text-red-400 focus:ring-red-600' : 'ring-gray-300 placeholder:text-gray-400 focus:ring-orange-600', 'text-gray-900 block w-full rounded-md border-0 py-1.5 pl-7 pr-12 ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']">
                                                <option selected disabled>Open this select menu</option>
                                                <option v-for="gateway in site.data.gateways" class="capitalize"
                                                    :value="gateway">
                                                    {{ gateway }}
                                                </option>
                                            </select>
                                        </div>
                                        <p class="text-sm text-red-600" v-if="errors.gateway">
                                            {{ errors.gateway }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-x-3 px-4 py-4 sm:px-8">
                                <button type="button" @click="() => router.get('/microsites')"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                                    {{ useTrans('Go back') }}
                                </button>
                                <button type="submit" form="payment-form"
                                    class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    {{ useTrans('Pay') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <!-- Right column -->
        <div class="grid grid-cols-1">
            <section aria-labelledby="section-2-title">
                <h2 class="sr-only" id="section-2-title">Section title</h2>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-lg bg-white pt-7 border-t-[25px]"
                    :style="{ 'border-color': site.data.primary_color }">
                    <span
                        class="mx-auto flex justify-center items-center size-[150px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                        <img :src="site.data.logo" width="100" alt="microsite-logo">
                    </span>
                    <div class="px-6 py-4 text-center">
                        <p class="font-bold text-xl mb-2">{{ site.data.name }}</p>
                        <p class="text-gray-700 text-base capitalize">{{ useTrans(site.data.type) }}</p>
                    </div>
                    <div class="px-6 pt-4 pb-4 text-center">
                        <span v-for="category in site.data.categories"
                            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mx-1">
                            {{ category }}
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { useTrans } from '@/helpers/translate';
import { router } from '@inertiajs/vue3'
import SelectInput from './SelectInput.vue';
import TextInput from './TextInput.vue';

defineProps({
    payment: Object,
    errors: Object,
    site: Object,
    submit: Function,
})

</script>