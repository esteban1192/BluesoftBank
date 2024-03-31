<template>
    <Header :user="user"></Header>
    <form class="max-w-md mx-auto mt-8" @submit.prevent="makeTransaction">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="fromAccount">From Account:</label>
            <select id="fromAccount" v-model="form.fromAccount" class="w-full px-3 py-2 border rounded-md">
                <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.id }} (${{ account.balance }})</option>
            </select>
            <span v-if="form.errors.fromAccount" class="text-red-500 text-sm">{{ form.errors.fromAccount }}</span>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="toAccount">To Account:</label>
            <input type="number" id="toAccount" v-model.number="form.toAccount" required
                class="w-full px-3 py-2 border rounded-md">
            <span v-if="form.errors.toAccount" class="text-red-500 text-sm">{{ form.errors.toAccount }}</span>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Amount:</label>
            <input type="number" id="amount" v-model.number="form.amount" required
                class="w-full px-3 py-2 border rounded-md">
            <span v-if="form.errors.amount" class="text-red-500 text-sm">{{ form.errors.amount }}</span>
        </div>
        <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Make Transaction</button>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import Header from '../../Components/Header.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['user', 'accounts']);
const form = useForm({
    fromAccount: '',
    toAccount: '',
    amount: null
});

const makeTransaction = () => {
    form.post('/transfer', {
        onFinish: () => {
            form.reset('fromAccount', 'toAccount', 'amount');
        }
    });
};
</script>
