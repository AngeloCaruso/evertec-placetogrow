<template>
    <div>
        <label :for="input.id" class="block text-sm font-medium leading-6 text-gray-900">
            {{ useTrans(input.name) }}
        </label>
        <div class="mt-2">
            <select :id="input.id" :name="input.id" :autocomplete="input.id" v-model="input.value"
                :class="[errors[`payment_data.${index}.value`] ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-orange-600', 'text-gray-900 block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:max-w-xs sm:text-sm sm:leading-6']">
                <option selected disabled value="">{{ useTrans(input.name) }}</option>
                <option v-for="option in input.options" :value="option">{{ option }}</option>
            </select>
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