<template>
    <Header :userEmail="userEmail" />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Recent Transactions</h1>

        <!-- Toggle button for filters -->
        <button @click="toggleFilters" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">{{ showFilters ? 'Hide' : 'Show' }} Filters</button>

        <!-- Filter inputs -->
        <div v-if="showFilters" class="mb-4">
            <label class="block mb-1">Account ID:</label>
            <input v-model="accountIdFilter" type="text" placeholder="Enter Account ID" class="border p-2 rounded w-full mb-2">

            <label class="block mb-1">Minimum Amount:</label>
            <input v-model="minAmountFilter" type="number" placeholder="Minimum Amount" class="border p-2 rounded w-full mb-2">

            <label class="block mb-1">Maximum Amount:</label>
            <input v-model="maxAmountFilter" type="number" placeholder="Maximum Amount" class="border p-2 rounded w-full mb-2">

            <button @click="applyFilters" class="w-full bg-blue-500 text-white px-4 py-2 rounded">Apply Filters</button>
        </div>

        <!-- Display filtered transactions -->
        <div v-if="filteredTransactions.length === 0" class="text-gray-600">No transactions found.</div>
        <div v-else>
            <div v-for="transaction in filteredTransactions" :key="transaction.id" class="border rounded p-4 my-2">
                <p class="text-lg font-bold">Transaction ID: {{ transaction.id }}</p>
                <p>From Account: {{ transaction.from_account_id }}</p>
                <p>To Account: {{ transaction.to_account_id }}</p>
                <p>Amount: ${{ transaction.amount }}</p>
                <p>Transaction Date: {{ transaction.transaction_date }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3'

const props = defineProps({
    transactions: {
        type: Array,
        required: true
    },
    userEmail: {
        type: String,
        required: true
    }
});

const accountIdFilter = ref('');
const minAmountFilter = ref('');
const maxAmountFilter = ref('');
const showFilters = ref(false); // Initialize with true to show filters by default

const filteredTransactions = ref(props.transactions);

const applyFilters = () => {
    const queryParams = {};

    if (accountIdFilter.value) {
        queryParams.accountId = accountIdFilter.value;
    }

    if (minAmountFilter.value) {
        queryParams.minAmount = minAmountFilter.value;
    }

    if (maxAmountFilter.value) {
        queryParams.maxAmount = maxAmountFilter.value;
    }

    router.get('/recent-transactions', queryParams)
};

const toggleFilters = () => {
    showFilters.value = !showFilters.value; // Toggle the value
};
</script>
