<template>
    <div>
        <label :for="input.id" class="block text-sm font-medium leading-6 text-gray-900">
            {{ useTrans(input.name) }}
        </label>
        <div class="mt-2">
            <input type="text" :name="payment_data + input.id" :id="input.id" v-model="input.value" :autocomplete="input.id" :placeholder="useTrans(input.name)"
                :class="[errors[`payment_data.${index}.value`] ? 'ring-red-300 focus:ring-red-600 placeholder:text-red-400' : 'ring-gray-300 focus:ring-orange-600 placeholder:text-gray-400', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6']" />
        </div>
        <p class="text-sm text-red-600" v-if="errors[`payment_data.${index}.value`]">
            {{ errors[`payment_data.${index}.value`] }}
        </p>
    </div>
</template>

<script setup>
import { useTrans } from '@/helpers/translate';

defineProps({
    input: Object,
    errors: Object,
    index: Number,
});

</script>