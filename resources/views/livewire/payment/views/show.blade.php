<x-app-layout>
    @push('scripts')
    @vite(['resources/js/Pages/Payment/Info.vue'])
    <script>
        console.log('Hello from the show payment blade');
    </script>
    @endpush
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:payment.show-payment :payment="$payment" />
        </div>
    </div>
</x-app-layout>