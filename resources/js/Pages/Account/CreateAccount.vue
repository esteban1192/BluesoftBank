<template>
    <Header :userEmail="userEmail"></Header>
    <form class="max-w-md mx-auto mt-8" @submit.prevent="createAccount">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="accountType">Account Type:</label>
            <select id="accountType" v-model="form.accountType" class="w-full px-3 py-2 border rounded-md">
                <option value="checking">Checking</option>
                <option value="savings">Savings</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="balance">Initial Balance:</label>
            <input type="number" id="balance" v-model.number="form.balance" required
                class="w-full px-3 py-2 border rounded-md">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="city">City:</label>
            <input type="text" id="city" v-model.trim="form.city"
                class="w-full px-3 py-2 border rounded-md">
        </div>
        <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Create
            Account</button>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import Header from '../../Components/Header.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['userEmail']);
const form = useForm({
    accountType: 'checking',
    balance: 0,
    city: '' // Initialize the city field
});

const createAccount = () => {
    form.post('/user/account', {
        onFinish: () => {
            form.reset('accountType', 'balance', 'city'); // Reset the city field after successful submission
        }
    });
};
</script>
