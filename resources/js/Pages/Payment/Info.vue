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
            class="relative flex flex-col bg-white shadow-lg pointer-events-auto dark:bg-neutral-800 mx-auto sm:max-w-lg sm:w-full m-3 rounded-xl ">
            <div class="relative overflow-hidden min-h-32 text-center dark:bg-neutral-950 rounded-t-xl"
                :style="{ 'background-color': site.data.primary_color }">

                <!-- SVG Background Element -->
                <figure class="absolute inset-x-0 bottom-0 -mb-px">
                    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                        viewBox="0 0 1920 100.1">
                        <path fill="currentColor" class="fill-white dark:fill-neutral-800"
                            d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                    </svg>
                </figure>
                <!-- End SVG Background Element -->
            </div>

            <div class="relative z-10 -mt-12">
                <!-- Icon -->
                <span
                    class="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <img :src="site.data.logo" width="100" alt="microsite-logo">
                </span>
                <!-- End Icon -->
            </div>

            <!-- Body -->
            <div class="p-4 sm:p-7 overflow-y-auto">
                <div class="text-center">
                    <h3 id="hs-ai-modal-label" class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Invoice from {{ site.data.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                        Reference {{ payment.data.reference }}
                    </p>
                </div>

                <!-- Grid -->
                <div class="mt-5 sm:mt-10 grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <div>
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Amount paid:</span>
                        <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">$ {{
                            payment.data.amount }} {{ payment.data.currency }}</span>
                    </div>
                    <!-- End Col -->

                    <div>
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Date paid:</span>
                        <span
                            class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{ payment.data.date }}</span>
                    </div>
                    <!-- End Col -->

                    <div>
                        <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Payment method:</span>
                        <div class="flex items-center gap-x-2">
                            <svg class="size-5" width="400" height="248" viewBox="0 0 400 248" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0)">
                                    <path d="M254 220.8H146V26.4H254V220.8Z" fill="#FF5F00" />
                                    <path
                                        d="M152.8 123.6C152.8 84.2 171.2 49 200 26.4C178.2 9.2 151.4 0 123.6 0C55.4 0 0 55.4 0 123.6C0 191.8 55.4 247.2 123.6 247.2C151.4 247.2 178.2 238 200 220.8C171.2 198.2 152.8 163 152.8 123.6Z"
                                        fill="#EB001B" />
                                    <path
                                        d="M400 123.6C400 191.8 344.6 247.2 276.4 247.2C248.6 247.2 221.8 238 200 220.8C228.8 198.2 247.2 163 247.2 123.6C247.2 84.2 228.8 49 200 26.4C221.8 9.2 248.6 0 276.4 0C344.6 0 400 55.4 400 123.6Z"
                                        fill="#F79E1B" />
                                </g>
                                <defs>
                                    <clipPath id="clip0">
                                        <rect width="400" height="247.2" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">•••• 4242</span>
                        </div>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Grid -->

                <div class="mt-5 sm:mt-10">
                    <h4 class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Summary</h4>

                    <ul class="mt-3 flex flex-col">
                        <li
                            class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Payment to Front</span>
                                <span>$264.00</span>
                            </div>
                        </li>
                        <li
                            class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Tax fee</span>
                                <span>$52.8</span>
                            </div>
                        </li>
                        <li
                            class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between w-full">
                                <span>Amount paid</span>
                                <span>$316.8</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Button -->
                <div class="mt-5 flex justify-end gap-x-2">
                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" x2="12" y1="15" y2="3" />
                        </svg>
                        Invoice PDF
                    </a>
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
                <!-- End Buttons -->

                <div class="mt-5 sm:mt-10">
                    <p class="text-sm text-gray-500 dark:text-neutral-500">If you have any questions, please do not
                        contact us, this is just a dev environment :) <a
                            class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                            href="https://github.com/AngeloCaruso">AngeloCaruso</a></p>
                </div>
            </div>
            <!-- End Body -->
        </div>
    </Layout>
</template>