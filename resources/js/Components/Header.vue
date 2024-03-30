<template>
    <header class="bg-gray-800 text-white py-4 px-8 flex justify-between items-center">
        <h1 class="text-lg font-bold">Bluesoft Bank</h1>
        <p>{{ userEmail }}</p>
    </header>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    userEmail: {
        type: String,
        required: true
    }
});
</script>

<style scoped>
</style>