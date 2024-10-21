<div class="relative mx-auto sm:max-w-lg sm:w-full divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-lg">
    <div class="min-h-28" style="background-color: {{ $site->data->primary_color }}"></div>

    <div class="px-4 py-5 sm:p-6">
        <div class="relative z-10 -mt-14">
            <span class="mx-auto flex justify-center items-center size-[150px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                <img src="{{$site->data->logo}}" width="100" alt="microsite-logo">
            </span>
        </div>
        <div class="text-center mt-3">
            <h3 id="hs-ai-modal-label" class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{__('Invoice from')}} {{ $site->data->name }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                {{__('Reference')}}: {{ $payment->data->reference }}
            </p>
        </div>

        <div class="mt-5 sm:mt-10 flex flex-wrap justify-center gap-10">
            <div class="text-center">
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Amount paid')}}</span>
                <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                    {{ $payment->data->amount }} {{ $payment->data->currency }}
                </span>
            </div>

            <div class="text-center">
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Payment Date')}}</span>
                <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                    {{ $payment->data->date }}
                </span>
            </div>

            <div class="text-center">
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Payment Status')}}</span>
                <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200 ">
                    {{ __($payment->data->status_label) }}
                </span>
            </div>
        </div>

        <div class="mt-5 sm:mt-10">
            <h4 class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">{{__('User data')}}</h4>

            <ul class="mt-3 flex flex-col">
                @foreach($payment->data->payment_data as $field)
                <li class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                        <span>{{__($field->name)}}</span>
                        <span class="font-semibold">{{ $field->value }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>