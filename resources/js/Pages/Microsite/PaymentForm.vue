<script setup>
import Layout from '@/Pages/Layout/Main.vue';
import { reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3'

defineProps({
    site: Object,
    errors: Object,
});

const page = usePage();
const payment = reactive({
    microsite_id: page.props.site.data.id,
    id_type: '',
    id_number: '',
    name: '',
    last_name: '',
    email: '',
    phone: '',
    gateway: '',
    reference: page.props.site.data.id + '-' + page.props.site.data.name + '-' + Date.now(),
    description: 'Pago de ' + page.props.site.data.name,
    amount: '',
    currency: page.props.site.data.currency,
    return_url: page.props.site.data.return_url,
});


function submit() {
    router.post('/payments', payment);
    console.log(page.props.errors);
}
</script>

<template>
    <Layout>
        <div>
            <h1>Payment Form</h1>
        </div>

        <div>
            <div>
                <h2>Datos del sitio</h2>
                <div>
                    <img :src="site.data.logo" width="100" alt="microsite-logo">
                    <p>{{ site.data.name }}</p>
                    <p>{{ site.data.type }}</p>
                    <p>{{ site.data.currency }}</p>
                </div>
            </div>
            <div>
                <h2>Tus datos</h2>
                <div>
                    <form @submit.prevent="submit" id="payment-form" method="post">
                        <div class="max-w-sm">
                            <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nombre</label>
                            <input type="text" id="name" name="name" class="" v-model="payment.name">
                        </div>
                        <div class="max-w-sm">
                            <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Apellido</label>
                            <input type="text" id="lastname" name="lastname" class="" v-model="payment.last_name">
                        </div>
                        <div class="max-w-sm">
                            <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                            <input type="email" id="email" name="email" class="" v-model="payment.email">
                        </div>
                        <div class="max-w-sm">
                            <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Teléfono</label>
                            <input type="tel" id="phone" name="phone" class="" v-model="payment.phone">
                        </div>
                        <div class="max-w-sm">
                            <label for="amount" class="block text-sm font-medium mb-2 dark:text-white">Monto</label>
                            <input type="number" id="amount" name="amount" class="" v-model="payment.amount">
                        </div>
                        <div class="max-w-sm">
                            <label for="idType" class="block text-sm font-medium mb-2 dark:text-white">
                                Tipo de identificacion
                            </label>
                            <select id="idType" class="" v-model="payment.id_type">
                                <option selected disabled>Open this select menu</option>
                                <option value="cc" >CC</option>
                                <option value="pp">PP</option>
                                <option value="ce">CE</option>
                            </select>
                        </div>
                        <div class="max-w-sm">
                            <label for="idNumber" class="block text-sm font-medium mb-2 dark:text-white">
                                Numero de identificacion
                            </label>
                            <input type="number" id="idNumber" name="idNumber" class="" v-model="payment.id_number">
                        </div>
                        <div class="max-w-sm">
                            <label for="gateway" class="block text-sm font-medium mb-2 dark:text-white">Pasarela</label>
                            <select id="gateway" class="" v-model="payment.gateway">
                                <option selected disabled>Open this select menu</option>
                                <option value="placetopay">Placetopay</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <button type="submit" form="payment-form">Pagar</button>
        </div>
    </Layout>
</template>