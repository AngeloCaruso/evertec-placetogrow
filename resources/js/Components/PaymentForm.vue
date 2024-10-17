<template>
    <main class="lg:flex lg:min-h-full lg:flex-row-reverse lg:overflow-hidden rounded-lg shadow-sm">
        <h1 class="sr-only">Checkout</h1>

        <!-- Mobile order summary -->
        <section aria-labelledby="order-heading" class="bg-gray-50 px-4 py-6 sm:px-6 lg:hidden">
            <Disclosure as="div" class="mx-auto max-w-lg" v-slot="{ open }">
                <div class="flex items-center justify-between">
                    <h2 id="order-heading" class="text-lg font-medium text-gray-900">{{ useTrans('Your Order') }}</h2>
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
                        <div class="flex justify-between">
                            <dt>{{ useTrans('Fee') }}</dt>
                            <dd class="text-gray-900">{{
                                (new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: site.data.currency
                                }))
                                    .format(payment.fee)
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
                            .format(+payment.amount + +payment.fee)
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
                                <SBadge v-for="category in site.data.categories" color="gray" border="1" size="sm">
                                    {{ category }}
                                </SBadge>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

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
                    <div class="flex justify-between">
                        <dt>{{ useTrans('Fee') }}</dt>
                        <dd class="text-gray-900">{{
                            (new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: site.data.currency
                            }))
                                .format(payment.fee)
                        }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-6 text-gray-900">
                        <dt class="text-base">Total</dt>
                        <dd class="text-base">{{
                            (new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: site.data.currency
                            }))
                                .format(+payment.amount + +payment.fee)
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
                        <div class="col-span-full" v-for="(field, index) in payment.payment_data">
                            <SelectInput v-if="field.is_select" :input="field" :index="index" :errors="errors" />
                            <TextInput v-if="field.type === 'text'" :input="field" :index="index" :errors="errors" />
                        </div>
                        <div class="col-span-full">
                            <div class="border-t border-gray-900/10">
                            </div>
                        </div>
                        <div class="col-span-full">
                            <SInputBlock id="email" v-model="payment.email" type="email"
                                :label="useTrans('Email address')" :placeholder="useTrans('Email address')"
                                :error="errors.email" :errorText="errors.email" />
                        </div>
                        <div class="col-span-full">
                            <SSelectBlock id="gateway" :placeholder="useTrans('Select an option')" name="gateway"
                                :label="useTrans('Gateway')" v-model="payment.gateway" :error="errors.gateway"
                                :errorText="errors.gateway">
                                <option v-for="gateway in site.data.gateways" class="capitalize" :value="gateway">
                                    {{ gateway }}
                                </option>
                            </SSelectBlock>
                        </div>
                        <div class="col-span-full" v-if="site.data.type === 'billing'">
                            <SInputBlock id="reference" v-model="payment.reference" :label="useTrans('Reference')"
                                :placeholder="useTrans('Reference')" prefix="#" :error="errors.reference"
                                :errorText="errors.reference" />
                        </div>
                        <div class="col-span-full" v-if="site.data.type !== 'billing'">
                            <SInputBlock id="amount" v-model="payment.amount" name="amount" placeholder="0.00"
                                :label="useTrans('Amount')" prefix="$" :suffix="payment.currency" :error="errors.amount"
                                :errorText="errors.amount" />
                        </div>
                    </div>

                    <SButton type="submit" form="payment-form" variant="primary" class="mt-6 w-full"
                        :loading="payment.processing">
                        {{
                            useTrans('Pay') + ' ' +
                            (new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: site.data.currency
                            }))
                                .format(+payment.amount + +payment.fee)
                        }}
                    </SButton>

                    <p class="mt-3 flex justify-center text-sm font-medium text-gray-500">
                        <LockClosedIcon class="mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                        Powered by really secure payment gateway
                    </p>

                    <div class="flex justify-center">
                        <SButton type="button" variant="link" class="mt-2 text-black"
                            @click="() => router.get('/microsites')">
                            {{ useTrans('Go Back') }}
                        </SButton>
                    </div>
                </form>
            </div>
        </section>
    </main>
</template>

<script setup>
import { useTrans } from '@/helpers/translate';
import { router } from '@inertiajs/vue3'
import SelectInput from './SelectInput.vue';
import TextInput from './TextInput.vue';
import { SBadge, SButton, SInputBlock, SSelectBlock } from '@placetopay/spartan-vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { LockClosedIcon } from '@heroicons/vue/20/solid'

defineProps({
    payment: Object,
    errors: Object,
    site: Object,
    submit: Function
})

</script>