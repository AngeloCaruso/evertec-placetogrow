<script setup>
import Layout from '@/Pages/Layout/Main.vue';

defineProps({
    payment: Object,
    site: Object,
});
</script>

<template>
    <Layout>
        <h1>Payment info</h1>

        <div>
            <div>
                <h2>Site data</h2>
                <div>
                    <img :src="site.data.logo" width="100" alt="microsite-logo">
                    <p>Site: {{ site.data.name }}</p>
                    <p>Type: {{ site.data.type }}</p>
                </div>
            </div>
            <div>
                <h2>Payment data</h2>
                <div>
                    <p>Reference: {{ payment.data.reference }}</p>
                    <p>Amount: {{ payment.data.amount }}</p>
                    <p>Currency: {{ payment.data.currency }}</p>
                    <p>Email: {{ payment.data.email }}</p>
                    <p>Status: {{ payment.data.gateway_status }}</p>
                </div>
            </div>
        </div>
    </Layout>
</template>