<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { usePage, useForm } from '@inertiajs/vue3'
import PaymentForm from '@/Components/PaymentForm.vue';
import SubscriptionForm from '@/Components/SubscriptionForm.vue';
import debounce from 'lodash/debounce';
import { watch } from 'vue';

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
    email: '',
    reference: '',
    amount: 0,
    fee: 0,
    is_paid: false,
    is_paid_monthly: false,
});

watch(() => payment.reference, debounce((value) => {
    console.log(value)

    payment.processing = true;

    fetch(`/payments/${payment.reference}/single`, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        method: 'GET'
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        }).then(data => {
            payment.amount = data.amount;
            payment.fee = data.fee;
            payment.is_paid = data.is_paid;
            payment.processing = false;
        }).catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
            payment.amount = 0;
            payment.fee = 0;
            payment.is_paid = false;
            payment.processing = false;
        });

}, 500));

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