<x-app-layout>
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="">
                <div class="p-6 text-gray-900 space-y-10">
                    <h1 class="font-bold text-3xl">Dashboard</h1>
                    <section class="space-y-4">
                        <div class="sm:flex-auto">
                            <h2 class="font-semibold text-2xl mt-6 leading-6 text-gray-900">{{__('Bills')}}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{__('Statistics about microsites type Billing')}}</p>
                        </div>
                        @if ($billingStats['emptyData'])
                        <div class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                            </svg>
                            <span class="mt-2 block text-sm font-semibold text-gray-900">{{__('There is no enough data to calculate metrics')}}</span>
                        </div>
                        @else
                        <div>
                            @livewire(\App\Livewire\Dashboard\BillStatsOverview::class, $billingStats)
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            @livewire(\App\Livewire\Dashboard\PaidvsUnpaidBillsChart::class, $billingStats)
                            @livewire(\App\Livewire\Dashboard\UnpaidvsExpiredBillsChart::class, $billingStats)
                        </div>
                        @endif

                    </section>
                    <section class="space-y-4">
                        <div class="sm:flex-auto">
                            <h2 class="font-semibold text-2xl mt-6 leading-6 text-gray-900">{{ __('Subscriptions')}}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{__('Statistics about microsites type Subscription')}}</p>
                        </div>
                        @if ($subscriptionStats['emptyData'])
                        <div class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                            </svg>
                            <span class="mt-2 block text-sm font-semibold text-gray-900">{{__('There is no enough data to calculate metrics')}}</span>
                        </div>
                        @else
                        <div class="grid gap-6 md:grid-cols-2">
                            @livewire(\App\Livewire\Dashboard\ActiveVsInactiveSubsChart::class, $subscriptionStats)
                            @livewire(\App\Livewire\Dashboard\SubscriptionsOverTimeChart::class, $subscriptionStats)
                        </div>
                        @endif
                    </section>
                    <section class="space-y-4">
                        <div class="sm:flex-auto">
                            <h2 class="font-semibold text-2xl mt-6 leading-6 text-gray-900">{{__('Donations')}}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{__('Statistics about microsites type Donation')}}</p>
                        </div>
                        @if ($donationStats['emptyData'])
                        <div class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                            </svg>
                            <span class="mt-2 block text-sm font-semibold text-gray-900">{{__('There is no enough data to calculate metrics')}}</span>
                        </div>
                        @else
                        <div class="grid gap-6">
                            @livewire(\App\Livewire\Dashboard\DonationsBySitesChart::class, $donationStats)
                        </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>