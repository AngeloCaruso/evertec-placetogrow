<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { usePage, router, useForm } from '@inertiajs/vue3'
import PaymentForm from '@/Components/PaymentForm.vue';
import SubscriptionForm from '@/Components/SubscriptionForm.vue';

defineProps({
    site: Object,
    errors: Object,
});

const page = usePage();
const payment = useForm({
    microsite_id: page.props.site.data.id,
    payment_data: page.props.site.data.form_fields,
    subscription_name: 'test - erase later',
    gateway: 'placetopay',
    description: 'Pago de ' + page.props.site.data.name,
    currency: page.props.site.data.currency,
    additional_attributes: {
        document_type: '',
    },
    amount: 0
});

function submitPayment() {
    payment.post('/payments', payment);
}

function submitSubscription() {
    payment.post('/subscription', payment);
}

</script>

<template>
    <Layout>
        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="sr-only">Page title</h1>
            <PaymentForm v-if="site.data.type !== 'subscription'" :payment="payment" :site="site" :errors="errors"
                :submit="submitPayment" />
            <SubscriptionForm v-if="site.data.type === 'subscription'" :payment="payment" :site="site" :errors="errors"
                :submit="submitSubscription" />
        </div>
    </Layout>
</template>