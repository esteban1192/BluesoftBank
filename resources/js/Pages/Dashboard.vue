<template>
    <Header :user="user" />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Account Dashboard</h1>
        <div v-if="accounts.length === 0" class="text-gray-600">No accounts found.</div>
        <div v-else>
            <div v-for="account in accounts" :key="account.id" class="border rounded p-4 my-2">
                <p class="text-lg font-bold">ID: {{ account.id }}</p>
                <p class="">Type: {{ account.account_type }}</p>
                <p>Balance: ${{ account.balance }}</p>
                <p v-if="account.city">City: {{ account.city }}</p> <!-- Display city if available -->
                <p>Created At: {{ account.created_at }}</p>
                
                <button @click="generateExtracts(account.id)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded mt-2">Generate Statements</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue';
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    accounts: {
        type: Array,
        required: true
    },
    user: {
        type: Object,
        required: true
    }
})

const generateExtracts = (accountId) => {
    const queryParams = {
        accountId: accountId
    }
    router.get('/generate-statements/', queryParams)
}
</script>
