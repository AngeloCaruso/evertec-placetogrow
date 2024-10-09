<template>
    <div class="bg-white py-10 rounded-lg border-t-[25px]" :style="{ 'border-color': site.data.primary_color }">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <span
                class="mx-auto flex justify-center items-center size-[150px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                <img :src="site.data.logo" width="100" alt="microsite-logo">
            </span>
            <div class="px-6 py-4 text-center">
                <p class="font-bold text-xl mb-2">{{ site.data.name }}</p>
                <p class="text-gray-700 text-base capitalize">{{ useTrans(site.data.type) }}</p>
            </div>
            <div class="mx-auto max-w-4xl text-center">
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ useTrans('Pricing plans for your needs') }}
                </p>
            </div>
            <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-gray-600">
                {{ useTrans('Choose an affordable plan that\'s packed with the best features for engaging your needs.')
                }}
            </p>

            <div class="mt-10 flex justify-center"
                v-if="site.data.is_paid_monthly && site.data.is_paid_yearly && !payment.amount">
                <fieldset aria-label="Payment frequency">
                    <RadioGroup v-model="frequency"
                        class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200">
                        <RadioGroupOption as="template" v-for="option in frequencies" :key="option.value"
                            :value="option" v-slot="{ checked }">
                            <div
                                :class="[checked ? 'bg-indigo-600 text-white' : 'text-gray-500', 'cursor-pointer rounded-full px-2.5 py-1']">
                                {{ useTrans(option.label) }}</div>
                        </RadioGroupOption>
                    </RadioGroup>
                </fieldset>
            </div>

            <div class="flex justify-center items-center">
                <div v-if="payment.amount"
                    class="isolate mx-auto mt-10 grid grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-1 w-max max-w-md">

                    <div class="bg-orange-100 border-t-4 border-orange-500 rounded-b text-gray-700 px-4 py-3 shadow-md"
                        role="alert">
                        <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-orange-500 mr-4"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                </svg></div>
                            <div>
                                <p class="font-bold">{{ useTrans('You picked the plan: ') + payment.subscription_name }}</p>
                                <p class="text-sm">{{ useTrans('This plan includes: ') + payment.features }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="ring-2 ring-orange-500 rounded-3xl p-7">
                        <form @submit.prevent="submit" id="payment-form" method="post">
                            <div
                                class="items-center justify-between gap-x-4 gap-y-4 grid sm:grid-cols-1 md:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ useTrans("Name") }}
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="name" id="name"
                                            v-model="payment.additional_attributes.name" autocomplete="name"
                                            :placeholder="useTrans('Name')"
                                            :class="[errors['additional_attributes.name'] ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
                                    </div>
                                    <p class="text-sm text-red-600" v-if="errors['additional_attributes.name']">
                                        {{ errors['additional_attributes.name'] }}
                                    </p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ useTrans("Surname") }}
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="surname" id="surname"
                                            v-model="payment.additional_attributes.surname" autocomplete="surname"
                                            :placeholder="useTrans('Surname')"
                                            :class="[errors['additional_attributes.surname'] ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
                                    </div>
                                    <p class="text-sm text-red-600" v-if="errors['additional_attributes.surname']">
                                        {{ errors['additional_attributes.surname'] }}
                                    </p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="document_type"
                                        class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ useTrans('Document Type') }}
                                    </label>
                                    <div class="mt-2">
                                        <select id="document_type" name="document_type" autocomplete="document_type"
                                            v-model="payment.additional_attributes.document_type"
                                            :class="[errors['additional_attributes.document_type'] ? 'ring-red-300 placeholder:text-red-400 focus:ring-red-600' : 'ring-gray-300 placeholder:text-gray-400 focus:ring-orange-600', 'text-gray-900 block w-full rounded-md border-0 py-1.5 pl-7 pr-12 ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']">
                                            <option selected disabled value="">{{ useTrans('Select a document type') }}
                                            </option>
                                            <option v-for="type in site.data.document_types" :value="type"
                                                class="uppercase">{{ type }}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="text-sm text-red-600" v-if="errors['additional_attributes.document_type']">
                                        {{ errors['additional_attributes.document_type'] }}
                                    </p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ useTrans("Document") }}
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="document" id="document"
                                            v-model="payment.additional_attributes.document" autocomplete="document"
                                            :placeholder="useTrans('Document')"
                                            :class="[errors['additional_attributes.document'] ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
                                    </div>
                                    <p class="text-sm text-red-600" v-if="errors['additional_attributes.document']">
                                        {{ errors['additional_attributes.document'] }}
                                    </p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">
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

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">
                                        {{ useTrans('Gateway') }}
                                    </label>
                                    <div class="mt-2">
                                        <select id="gateway" name="gateway" autocomplete="gateway-name"
                                            v-model="payment.gateway"
                                            :class="[errors.gateway ? 'ring-red-300 placeholder:text-red-400 focus:ring-red-600' : 'ring-gray-300 placeholder:text-gray-400 focus:ring-orange-600', 'text-gray-900 block w-full rounded-md border-0 py-1.5 pl-7 pr-12 ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']">
                                            <option selected disabled value="">{{ useTrans('Select a gateway') }}
                                            </option>
                                            <option v-for="gateway in site.data.gateways" class="capitalize"
                                                :value="gateway">
                                                {{ gateway }}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="text-sm text-red-600" v-if="errors.gateway">
                                        {{ errors.gateway }}
                                    </p>
                                </div>
                            </div>

                            <button
                                class="bg-orange-600 text-white shadow-sm hover:bg-orange-500 w-full mt-6 block rounded-md px-3 py-2 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                {{ useTrans('Subscribe') }}
                            </button>
                            <a :href='`/microsites/${site.data.slug}`' class="text-gray-900 mt-2 block text-center text-sm font-semibold leading-6">
                                {{ useTrans('Cancel') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
            <div v-if="!payment.amount"
                class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div v-for="(plan, i) in site.data.plans" :key="i"
                    :class="[plan.featured ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-200', 'rounded-3xl p-8 xl:p-10']">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 :id="i"
                            :class="[plan.featured ? 'text-indigo-600' : 'text-gray-900', 'text-lg font-semibold leading-8']">
                            {{ plan.name }}</h3>
                        <p v-if="plan.featured"
                            class="rounded-full bg-indigo-600/10 px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600">
                            {{ useTrans('Featured Plan!') }}
                        </p>
                    </div>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span class="text-3xl font-bold tracking-tight text-gray-900">
                            {{
                                (new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: site.data.currency
                                }))
                                    .format(frequency['value'] == 'monthly' ? plan.price_monthly : plan.price_yearly)
                            }}
                        </span>
                        <span class="text-sm font-semibold leading-6 text-gray-600">{{ useTrans(frequency.priceSuffix)
                            }}</span>
                    </p>
                    <button
                        @click="() => { payment.amount = frequency['value'] == 'monthly' ? plan.price_monthly : plan.price_yearly; payment.subscription_name = plan.name; payment.features = plan.features.join(', '); }"
                        :aria-describedby="i"
                        :class="[plan.featured ? 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' : 'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300', 'w-full mt-6 block rounded-md px-3 py-2 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">
                        {{ useTrans('Choose Plan') }}
                    </button>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600 xl:mt-10">
                        <li v-for="feature in plan.features" :key="feature" class="flex gap-x-3">
                            <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true" />
                            {{ feature }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3';
import { RadioGroup, RadioGroupOption } from '@headlessui/vue'
import { CheckIcon } from '@heroicons/vue/20/solid'
import { useTrans } from '@/helpers/translate';

defineProps({
    payment: Object,
    site: Object,
    errors: Object,
    submit: Function
})

const frequencies = [
    { value: 'monthly', label: 'Monthly', priceSuffix: '/month' },
    { value: 'annually', label: 'Annually', priceSuffix: '/year' },
]

const frequency = ref(frequencies[0])
const show = ref(true)
</script>