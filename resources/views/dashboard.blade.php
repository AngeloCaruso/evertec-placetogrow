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
                        <div>
                            @livewire(\App\Livewire\Dashboard\BillStatsOverview::class, ['totalBills' => $totalBills, 'paidBills' => $paidBills, 'rejectedBills' => $rejectedBills])
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            @livewire(\App\Livewire\Dashboard\PaidvsUnpaidBillsChart::class, ['paidBills' => $paidBills, 'unpaidBills' => $unpaidBills])
                            @livewire(\App\Livewire\Dashboard\UnpaidvsExpiredBillsChart::class, ['unpaidBills' => $unpaidBills, 'expiredBills' => $expiredBills])
                        </div>
                    </section>
                    <section class="space-y-4">
                        <div class="sm:flex-auto">
                            <h2 class="font-semibold text-2xl mt-6 leading-6 text-gray-900">{{ __('Subscriptions')}}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{__('Statistics about microsites type Subscription')}}</p>
                        </div>
                        <div>

                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            @livewire(\App\Livewire\Dashboard\PaidvsUnpaidBillsChart::class, ['paidBills' => $paidBills, 'unpaidBills' => $unpaidBills])
                            @livewire(\App\Livewire\Dashboard\UnpaidvsExpiredBillsChart::class, ['unpaidBills' => $unpaidBills, 'expiredBills' => $expiredBills])
                        </div>
                    </section>
                    <section class="space-y-4">
                        <div class="sm:flex-auto">
                            <h2 class="font-semibold text-2xl mt-6 leading-6 text-gray-900">{{__('Donations')}}</h2>
                            <p class="mt-1 text-sm text-gray-700">{{__('Statistics about microsites type Donation')}}</p>
                        </div>
                        <div>

                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            @livewire(\App\Livewire\Dashboard\PaidvsUnpaidBillsChart::class, ['paidBills' => $paidBills, 'unpaidBills' => $unpaidBills])
                            @livewire(\App\Livewire\Dashboard\UnpaidvsExpiredBillsChart::class, ['unpaidBills' => $unpaidBills, 'expiredBills' => $expiredBills])
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>