<template>
    <div>
        <SInputBlock :id="input.id" :name="payment_data + input.id" type="text" v-model="input.value"
            :label="useTrans(input.name)" :placeholder="useTrans(input.name)"
            :error="errors[`payment_data.${index}.value`]" :errorText="errors[`payment_data.${index}.value`]" />
    </div>
</template>

<script setup>
import { useTrans } from '@/helpers/translate';
import { SInputBlock } from '@placetopay/spartan-vue';

defineProps({
    input: Object,
    errors: Object,
    index: Number,
});

</script>