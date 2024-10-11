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
                                        <SInputBlock id="email" v-model="payment.email" type="email"
                                            :label="useTrans('Email address')" :placeholder="useTrans('Email address')"
                                            :error="errors.email" :errorText="errors.email" />
                                    </div>
                                    <div class="sm:col-span-3">
                                        <SSelectBlock id="gateway" :placeholder="useTrans('Select an option')"
                                            name="gateway" :label="useTrans('Gateway')" v-model="payment.gateway"
                                            :error="errors.gateway" :errorText="errors.gateway">
                                            <option v-for="gateway in site.data.gateways" class="capitalize"
                                                :value="gateway">
                                                {{ gateway }}
                                            </option>
                                        </SSelectBlock>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <div v-if="site.data.type === 'billing'">
                                            <SInputBlock id="reference" v-model="payment.reference"
                                                :label="useTrans('Reference')" :placeholder="useTrans('Reference')"
                                                prefix="#" :error="errors.reference" :errorText="errors.reference" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <SInputBlock id="amount" name="amount" placeholder="0.00"
                                            :label="useTrans('Amount')" disabled prefix="$" :suffix="payment.currency"
                                            :error="errors.amount" :errorText="errors.amount" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-x-3 px-4 py-4 sm:px-8">
                                <SButton @click="() => router.get('/microsites')" variant="secondary">
                                    {{ useTrans('Go Back') }}
                                </SButton>
                                <SButton type="submit" form="payment-form" variant="primary">{{ useTrans('Pay') }}
                                </SButton>
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
import { SButton, SInputBlock, SSelectBlock } from '@placetopay/spartan-vue';

defineProps({
    payment: Object,
    errors: Object,
    site: Object,
    submit: Function,
})

</script>