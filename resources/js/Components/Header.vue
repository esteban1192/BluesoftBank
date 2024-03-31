<template>
    <header class="bg-gray-800 text-white py-4 px-8 flex justify-between items-center">
        <div class="flex text-lg font-bold">
            <Link href="/dashboard" class="ml-4 hover:underline cursor-pointer">Bluesoft Bank</Link>
            <Link href="/create-account" class="ml-4 hover:underline cursor-pointer">Create Account</Link>
            <Link href="/transfer" class="ml-4 hover:underline cursor-pointer">Transfer</Link>
            <Link href="/recent-transactions" class="ml-4 hover:underline cursor-pointer">Recent Transactions</Link>
        </div>
        <div>
            <p>{{ userEmail }}</p>
        </div>
    </header>
</template>

<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    userEmail: {
        type: String,
        required: true
    }
});
</script>

<style scoped>
</style>
