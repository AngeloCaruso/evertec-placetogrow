<script setup>
import Layout from '@/Pages/Layout/Main.vue';

defineProps({
    payment: Object,
    site: Object,
});

function print() {
    window.print();
}
</script>

<template>
    <Layout>
        <div
            class="relative mx-auto sm:max-w-lg sm:w-full divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-lg">
            <div class="min-h-28" :style="{ 'background-color': site.data.primary_color }">
                <figure class="relative -bottom-20 pt-8" :style="{ 'color': site.data.primary_color }">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 735 60" width="100%" height="100%"
                        preserveAspectRatio="xMinYMin meet" xml:space="preserve">
                        <rect width="100%" height="100%" fill="transparent" />
                        <path fill="currentColor"
                            d="M6644216.85 17559511.1055s16901324.416 1689408.926 34728748.8 0v1810597.789H6644216.85v-1810597.789z" />
                        <circle fill="currentColor" vector-effect="non-scaling-stroke" r="35"
                            transform="matrix(18.36 0 0 6.54 367.5 -186.77)" />
                    </svg>
                </figure>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="relative z-10 -mt-14">
                    <span
                        class="mx-auto flex justify-center items-center size-[150px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                        <img :src="site.data.logo" width="100" alt="microsite-logo">
                    </span>
                </div>
                <div class="text-center mt-3">
                    <h3 id="hs-ai-modal-label" class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Invoice from {{ site.data.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                        Reference {{ payment.data.reference }}
                    </p>
                </div>

                <div class="mt-5 sm:mt-10 flex flex-wrap justify-center gap-10">
                    <div class="text-center">
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Amount paid</span>
                        <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ payment.data.amount }} {{ payment.data.currency }}
                        </span>
                    </div>

                    <div class="text-center">
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Date paid</span>
                        <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ payment.data.date }}
                        </span>
                    </div>

                    <div class="text-center">
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Payment Status</span>
                        <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ payment.data.gateway_status }}
                        </span>
                    </div>
                </div>

                <div class="mt-5 sm:mt-10">
                    <h4 class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">User data</h4>

                    <ul class="mt-3 flex flex-col">
                        <li v-for="field in payment.data.payment_data"
                            class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>{{ field.name }}</span>
                                <span class="font-semibold">{{ field.value }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="mt-5 flex justify-end gap-x-2">
                    <button
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none"
                        :style="{ 'background-color': site.data.primary_color }" @click="print">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect width="12" height="8" x="6" y="14" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>

            <div class="px-4 py-4 sm:px-6">
                <p class="text-sm text-gray-500 dark:text-neutral-500">If you have any questions, please do not
                    contact us. This is just a dev environment :) <a
                        class="inline-flex items-center gap-x-1.5 text-gray-800 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                        href="https://github.com/AngeloCaruso" target="_blank">AngeloCaruso</a>
                </p>
            </div>
        </div>
    </Layout>
</template>