<script setup>
import Layout from '@/Pages/Layout/Main.vue';

defineProps({ sites: Object })

</script>

<template>
    <Layout>
        <div>
            <h1>Microsites</h1>

            <ul>
                <li v-for="(site, index) in sites" :key="index"> {{ site.name }}</li>
                {{ console.log(sites) }}
            </ul>
        </div>
    </Layout>
</template>