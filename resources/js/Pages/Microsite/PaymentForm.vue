<script setup>
import Layout from '@/Pages/Layout/Main.vue';

defineProps({ site: Object })
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
                    <form>
                        <div class="max-w-sm">
                            <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nombre</label>
                            <input type="text" id="name" name="name"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Apellido</label>
                            <input type="text" id="lastname" name="lastname"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                            <input type="email" id="email" name="email"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Teléfono</label>
                            <input type="tel" id="phone" name="phone"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="amount" class="block text-sm font-medium mb-2 dark:text-white">Monto</label>
                            <input type="number" id="amount" name="amount"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="idType" class="block text-sm font-medium mb-2 dark:text-white">Tipo de identificacion</label>
                            <select id="hs-select-label" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                <option selected="">Open this select menu</option>
                                <option>CC</option>
                                <option>PP</option>
                                <option>CE</option>
                            </select>
                        </div>
                        <div class="max-w-sm">
                            <label for="idNumber" class="block text-sm font-medium mb-2 dark:text-white">Numero de identificacion</label>
                            <input type="number" id="idNumber" name="idNumber"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        <div class="max-w-sm">
                            <label for="gateway" class="block text-sm font-medium mb-2 dark:text-white">Pasarela</label>
                            <select id="hs-select-label" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                <option selected="">Open this select menu</option>
                                <option>Placetopay</option>
                                <option>PayPal</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <button type="submit">Pagar</button>
        </div>
    </Layout>
</template>