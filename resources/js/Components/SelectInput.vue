<template>
    <div>
        <SSelectBlock :id="input.id" :placeholder="useTrans('Select an option')" :name="input.id"
            :label="useTrans(input.name)" v-model="input.value" :error="errors[`payment_data.${index}.value`]" :errorText="errors[`payment_data.${index}.value`]">
            <option v-for="option in input.options" :value="option" class="capitalize">
                {{ option }}
            </option>
        </SSelectBlock>
    </div>
</template>

<script setup>
import { useTrans } from '@/helpers/translate';
import { SSelectBlock } from '@placetopay/spartan-vue';

defineProps({
    input: Object,
    errors: Object,
    index: Number,
});

</script>