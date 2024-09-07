<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { reactive } from 'vue';
import { usePage, router } from '@inertiajs/vue3'
import { useTrans } from '@/helpers/translate';
import PaymentForm from '@/Components/PaymentForm.vue';
import SubscriptionForm from '@/Components/SubscriptionForm.vue';

defineProps({
    site: Object,
    errors: Object,
});

const page = usePage();
const payment = reactive({
    microsite_id: page.props.site.data.id,
    payment_data: page.props.site.data.form_fields,
    amount: '',
    gateway: '',
    description: 'Pago de ' + page.props.site.data.name,
    currency: page.props.site.data.currency,
});

function submit() {
    router.post('/payments', payment);
}

</script>

<template>
    <Layout>
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="sr-only">Page title</h1>
            <PaymentForm v-if="site.data.type !== 'subscription'" :payment="payment" :site="site" :errors="errors"
                :submit="submit" />
            <SubscriptionForm v-if="site.data.type === 'subscription'" :site="site" />
        </div>
    </Layout>
</template>