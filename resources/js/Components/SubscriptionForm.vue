<template>
    <Transition name="slide-right" mode="out-in">
        <div v-if="!payment.amount">
            <div class="bg-white py-7 rounded-lg border-t-[25px]" :style="{ 'border-color': site.data.primary_color }">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <span
                        class="mx-auto flex justify-center items-center size-[150px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                        <img :src="site.data.logo" width="100" alt="microsite-logo">
                    </span>
                    <div class="mt-8 mx-auto max-w-4xl text-center">
                        <p class="text-3xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                            {{ useTrans('Pricing plans for your needs') }}
                        </p>
                    </div>
                    <p class="mx-auto max-w-2xl text-center text-lg leading-8 text-gray-600">{{
                        useTrans('Choose an affordable plan that\'s packed with the best features for engaging your needs.')
                        }}
                    </p>

                    <div class="mt-12 flex justify-center"
                        v-if="site.data.is_paid_monthly && site.data.is_paid_yearly">
                        <fieldset aria-label="Payment frequency">
                            <RadioGroup v-model="frequency"
                                class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200">
                                <RadioGroupOption as="template" v-for="option in frequencies" :key="option.value"
                                    :value="option" v-slot="{ checked }">
                                    <div
                                        :class="[checked ? 'bg-orange-500 text-white' : 'text-gray-500', 'cursor-pointer rounded-full px-2.5 py-1']">
                                        {{ useTrans(option.label) }}</div>
                                </RadioGroupOption>
                            </RadioGroup>
                        </fieldset>
                    </div>
                    <div
                        class="isolate mx-auto mt-6 grid max-w-md grid-cols-1 gap-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                        <div v-for="(plan, i) in site.data.plans" :key="i"
                            :class="[plan.featured ? 'ring-2 ring-orange-500' : 'ring-1 ring-gray-300', 'rounded-2xl p-8 xl:p-10']">
                            <div class="flex items-center justify-between gap-x-4">
                                <h3 :id="i"
                                    :class="[plan.featured ? 'text-orange-600' : 'text-gray-900', 'text-lg font-semibold leading-8']">
                                    {{ plan.name }}</h3>
                                <p v-if="plan.featured"
                                    class="rounded-full bg-orange-600/10 px-2.5 py-1 text-xs font-semibold leading-5 text-orange-600">
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
                                <span class="text-sm font-semibold leading-6 text-gray-600">
                                    {{ useTrans(frequency.priceSuffix) }}
                                </span>
                            </p>
                            <SButton type="button" :variant="plan.featured ? 'primary' : 'outline'" class="w-full mt-6"
                                @click="() => { payment.amount = frequency['value'] == 'monthly' ? plan.price_monthly : plan.price_yearly; payment.is_paid_monthly = frequency['value'] == 'monthly'; payment.subscription_name = plan.name; payment.features = plan.features.join(', '); }">
                                {{ useTrans('Choose Plan') }}
                            </SButton>
                            <ul role="list" class="mt-8 space-y-1 text-sm leading-6 text-gray-600 xl:mt-10">
                                <li v-for="feature in plan.features" :key="feature" class="flex gap-x-3">
                                    <CheckCircleIcon class="h-6 w-5 flex-none text-orange-600" aria-hidden="true" />
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <SButton type="button" variant="link" class="mt-2 text-black" @click="() => router.get('/microsites')">
                            {{ useTrans('Go Back') }}
                        </SButton>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="payment.amount">
            <main class="lg:flex lg:min-h-full lg:flex-row-reverse lg:overflow-hidden rounded-lg shadow-sm">
                <h1 class="sr-only">Checkout</h1>

                <!-- Mobile order summary -->
                <section aria-labelledby="order-heading" class="bg-gray-50 px-4 py-6 sm:px-6 lg:hidden">
                    <Disclosure as="div" class="mx-auto max-w-lg" v-slot="{ open }">
                        <div class="flex items-center justify-between">
                            <h2 id="order-heading" class="text-lg font-medium text-gray-900">{{ useTrans('Your Order')
                                }}
                            </h2>
                            <DisclosureButton class="font-medium text-orange-600 hover:text-orange-500">
                                <span v-if="open">{{ useTrans('Hide full summary') }}</span>
                                <span v-if="!open">{{ useTrans('Show full summary') }}</span>
                            </DisclosureButton>
                        </div>

                        <DisclosurePanel>
                            <ul role="list" class="divide-y divide-gray-200 border-b border-gray-200">
                                <li class="flex space-x-6 py-6">
                                    <img :src="site.data.logo" alt="microsite-logo"
                                        class="h-40 w-40 flex-none rounded-md bg-white object-cover object-center border border-gray-200 bg-origin-padding p-3" />
                                    <div class="flex flex-col justify-between space-y-4">
                                        <div class="space-y-1 pt-2 text-sm font-medium">
                                            <h3 class="text-gray-900 text-xl pb-3">{{ site.data.name }}</h3>
                                            <p class="text-gray-500">{{ useTrans(site.data.type) }}</p>
                                            <p class="text-gray-500">{{ site.data.currency }}</p>
                                            <div class="pt-4 space-x-1">
                                                <SBadge v-for="category in site.data.categories" color="gray" border="1"
                                                    size="sm">
                                                    {{ category }}
                                                </SBadge>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <dl class="mt-6 space-y-6 text-sm font-medium text-gray-500">
                                <div class="flex justify-between">
                                    <dt>Subtotal</dt>
                                    <dd class="text-gray-900">{{
                                        (new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: site.data.currency
                                        }))
                                            .format(payment.amount)
                                        }}</dd>
                                </div>
                            </dl>
                        </DisclosurePanel>

                        <p
                            class="mt-6 flex items-center justify-between border-t border-gray-200 pt-6 text-sm font-medium text-gray-900">
                            <dt class="text-base">Total</dt>
                            <dd class="text-base">{{
                                (new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: site.data.currency
                                }))
                                    .format(payment.amount)
                                }}</dd>
                        </p>
                    </Disclosure>
                </section>

                <!-- Order summary -->
                <section aria-labelledby="summary-heading" class="hidden w-full max-w-md flex-col bg-gray-50 lg:flex">
                    <h2 id="summary-heading" class="sr-only">Order summary</h2>

                    <ul role="list" class="flex-auto divide-y divide-gray-200 overflow-y-auto px-6">
                        <li class="flex space-x-6 py-6">
                            <img :src="site.data.logo" alt="microsite-logo"
                                class="h-40 w-40 flex-none rounded-md bg-white object-cover object-center border border-gray-200 bg-origin-padding p-3" />
                            <div class="flex flex-col justify-between space-y-4">
                                <div class="space-y-1 pt-2 text-sm font-medium">
                                    <h3 class="text-gray-900 text-xl pb-3">{{ site.data.name }}</h3>
                                    <p class="text-gray-500">{{ useTrans(site.data.type) }}</p>
                                    <p class="text-gray-500">{{ site.data.currency }}</p>
                                    <div class="pt-4 space-x-1">
                                        <SBadge v-for="category in site.data.categories" color="gray" border="1"
                                            size="sm">
                                            {{ category }}
                                        </SBadge>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="bg-white border-t-4 border-orange-500 rounded-b text-gray-700 px-4 py-3" role="alert">
                        <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-orange-500 mr-4"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">{{ useTrans('You picked the plan: ') +
                                    payment.subscription_name }}
                                </p>
                                <p class="text-sm">{{ useTrans('This plan includes: ') + payment.features }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="sticky bottom-0 flex-none border-t border-gray-200 bg-gray-50 p-6">
                        <dl class="mt-6 space-y-6 text-sm font-medium text-gray-500">
                            <div class="flex justify-between">
                                <dt>Subtotal</dt>
                                <dd class="text-gray-900">{{
                                    (new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: site.data.currency
                                    }))
                                        .format(payment.amount)
                                    }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-6 text-gray-900">
                                <dt class="text-base">Total</dt>
                                <dd class="text-base">{{
                                    (new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: site.data.currency
                                    }))
                                        .format(payment.amount)
                                    }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <!-- Checkout form -->
                <section aria-labelledby="payment-heading"
                    class="flex-auto overflow-y-auto px-4 pb-16 pt-12 sm:px-6 sm:pt-16 lg:px-8 lg:pb-24 bg-white">
                    <div class="mx-auto max-w-lg">
                        <form @submit.prevent="submit" id="payment-form" method="post" class="mt-6">
                            <div class="grid grid-cols-12 gap-x-4 gap-y-6">
                                <div class="col-span-full">
                                    <SInputBlock id="name" v-model="payment.additional_attributes.name" type="text"
                                        :label="useTrans('Name')" :placeholder="useTrans('Name')"
                                        :error="errors['additional_attributes.name']"
                                        :errorText="errors['additional_attributes.name']" />
                                </div>
                                <div class="col-span-full">
                                    <SInputBlock id="surname" v-model="payment.additional_attributes.surname"
                                        type="text" :label="useTrans('Surname')" :placeholder="useTrans('Surname')"
                                        :error="errors['additional_attributes.surname']"
                                        :errorText="errors['additional_attributes.surname']" />
                                </div>
                                <div class="col-span-full">
                                    <SSelectBlock id="document_type" :placeholder="useTrans('Select a document type')"
                                        name="document_type" :label="useTrans('Document Type')"
                                        v-model="payment.additional_attributes.document_type"
                                        :error="errors['additional_attributes.document_type']"
                                        :errorText="errors['additional_attributes.document_type']">
                                        <option v-for="type in site.data.document_types" class="uppercase"
                                            :value="type">
                                            {{ type }}
                                        </option>
                                    </SSelectBlock>
                                </div>
                                <div class="col-span-full">
                                    <SInputBlock id="document" v-model="payment.additional_attributes.document"
                                        type="text" :label="useTrans('Document')" :placeholder="useTrans('Document')"
                                        :error="errors['additional_attributes.document']"
                                        :errorText="errors['additional_attributes.document']" />
                                </div>
                                <div class="col-span-full">
                                    <SInputBlock id="email" v-model="payment.email" type="email"
                                        :label="useTrans('Email address')" :placeholder="useTrans('Email address')"
                                        :error="errors.email" :errorText="errors.email" />
                                </div>
                                <div class="col-span-full">
                                    <SSelectBlock id="gateway" :placeholder="useTrans('Select an option')"
                                        name="gateway" :label="useTrans('Gateway')" v-model="payment.gateway"
                                        :error="errors.gateway" :errorText="errors.gateway">
                                        <option v-for="gateway in site.data.gateways" class="capitalize"
                                            :value="gateway">
                                            {{ gateway }}
                                        </option>
                                    </SSelectBlock>
                                </div>
                            </div>

                            <SButton type="submit" form="payment-form" variant="primary" class="mt-6 w-full" :loading="payment.processing">
                                {{
                                    useTrans('Pay') + ' ' +
                                    (new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: site.data.currency
                                    }))
                                        .format(payment.amount)
                                }}
                            </SButton>

                            <SButton type="button" variant="link" class="mt-1 w-full text-black" @click=" payment.amount = '' ">
                                {{ useTrans('Cancel') }}
                            </SButton>

                            <p class="mt-3 flex justify-center text-sm font-medium text-gray-500">
                                <LockClosedIcon class="mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                                Secured by a really secure payment gateway
                            </p>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </Transition>

</template>

<script setup>
import { ref } from 'vue'
import { RadioGroup, RadioGroupOption } from '@headlessui/vue'
import { CheckCircleIcon, LockClosedIcon } from '@heroicons/vue/20/solid'
import { useTrans } from '@/helpers/translate';
import { SBadge, SButton, SInputBlock, SSelectBlock } from '@placetopay/spartan-vue';
import { router } from '@inertiajs/vue3'

defineProps({
    payment: Object,
    errors: Object,
    site: Object,
    submit: Function
})

import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

const frequencies = [
    { value: 'monthly', label: 'Monthly', priceSuffix: '/month' },
    { value: 'annually', label: 'Annually', priceSuffix: '/year' },
]

const frequency = ref(frequencies[0])

</script>

<style scoped>
.slide-right-enter-active,
.slide-right-leave-active {
    transition: all 0.25s ease;
}

.slide-right-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.slide-right-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>