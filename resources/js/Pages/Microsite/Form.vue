<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3'

defineProps({
    site: Object,
    errors: Object,
});

const page = usePage();
const payment = reactive({
    microsite_id: page.props.site.data.id,
    id_type: '',
    id_number: '',
    name: '',
    last_name: '',
    email: '',
    phone: '',
    gateway: '',
    description: 'Pago de ' + page.props.site.data.name,
    amount: '',
    currency: page.props.site.data.currency,
    return_url: page.props.site.data.return_url,
});

function submit() {
    router.post('/payments', payment);
}
</script>

<template>
    <Layout>
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
                                <form @submit.prevent="submit" id="payment-form" method="post"
                                    class="bg-white sm:rounded-xl md:col-span-2">
                                    <div class="px-4 py-6 sm:p-8">
                                        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                            <div class="sm:col-span-3">
                                                <label for="id-type"
                                                    class="block text-sm font-medium leading-6 text-gray-900">ID
                                                    Type</label>
                                                <div class="mt-2">
                                                    <select id="id-type" name="id-type" autocomplete="id-type"
                                                        v-model="payment.id_type"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                        <option selected disabled>Open this select menu</option>
                                                        <option value="cc">CC</option>
                                                        <option value="pp">PP</option>
                                                        <option value="ce">CE</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="id-number"
                                                    class="block text-sm font-medium leading-6 text-gray-900">ID
                                                    Number</label>
                                                <div class="mt-2">
                                                    <input type="text" name="id-number" id="id-number"
                                                        v-model="payment.id_number" autocomplete="id-number"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="first-name"
                                                    class="block text-sm font-medium leading-6 text-gray-900">First
                                                    name</label>
                                                <div class="mt-2">
                                                    <input type="text" name="first-name" id="first-name"
                                                        v-model="payment.name" autocomplete="given-name"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="last-name"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Last
                                                    name</label>
                                                <div class="mt-2">
                                                    <input type="text" name="last-name" id="last-name"
                                                        v-model="payment.last_name" autocomplete="family-name"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="email"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Email
                                                    address</label>
                                                <div class="mt-2">
                                                    <input id="email" name="email" type="email" autocomplete="email"
                                                        v-model="payment.email"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="phone"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Phone</label>
                                                <div class="mt-2">
                                                    <input type="text" name="phone" id="phone" v-model="payment.phone"
                                                        autocomplete="phone"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="price"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Amount</label>
                                                <div class="relative mt-2 rounded-md shadow-sm">
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-gray-500 sm:text-sm">$</span>
                                                    </div>
                                                    <input type="text" name="price" id="price" v-model="payment.amount"
                                                        class="block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                        placeholder="0.00" aria-describedby="price-currency" />
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                        <span class="text-gray-500 sm:text-sm" id="price-currency">{{
                                                            site.data.currency }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="gateway"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Gateway</label>
                                                <div class="mt-2">
                                                    <select id="gateway" name="gateway" autocomplete="gateway-name"
                                                        v-model="payment.gateway"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                                        <option selected disabled>Open this select menu</option>
                                                        <option v-for="gateway in site.data.gateways" class="capitalize"
                                                            :value="gateway">
                                                            {{ gateway }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                                        <button type="submit" form="payment-form"
                                            class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Pagar</button>
                                    </div>
                                </form>
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
                                <img :src="site.data.logo" width="100" alt="microsite-logo">
                                <p>{{ site.data.name }}</p>
                                <p>{{ site.data.type }}</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </Layout>
</template>