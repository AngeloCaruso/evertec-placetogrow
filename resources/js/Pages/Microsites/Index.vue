<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <h1>Microsites</h1>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Category</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="microsite in microsites" :key="microsite.id">
                            <td>{{ microsite.name }}</td>
                            <td>{{ microsite.logo }}</td>
                            <td>{{ microsite.category }}</td>
                            <td>{{ microsite.active }}</td>
                            <td>
                                <Link :href="route('microsite.edit', microsite.id)">Edit</Link>
                                <Link :href="route('microsite.destroy', microsite.id)">Delete</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>