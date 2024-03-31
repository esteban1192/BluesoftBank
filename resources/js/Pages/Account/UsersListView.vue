<template>
    <div>
      <Header :user="user" />
      <div class="max-w-md mx-auto mt-8">
        <h2 class="text-xl font-bold mb-4">User List</h2>
        <div class="flex flex-col mb-4">
          <label for="selectedMonth" class="mb-2 font-semibold">Select Month:</label>
          <input type="month" v-model="selectedMonth" id="selectedMonth" class="px-3 py-2 border rounded-md">
        </div>
        <div class="flex flex-col mb-4">
          <label for="moreThanOneMillionOnly" class="mb-2 font-semibold">Show Users with More Than 1,000,000 Transactions:</label>
          <select v-model="moreThanOneMillionOnly" id="moreThanOneMillionOnly" class="px-3 py-2 border rounded-md">
            <option value="">All Users</option>
            <option value="true">More Than 1,000,000 Transactions Only</option>
          </select>
        </div>
        <button @click="applyFilters" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md">Apply Filters</button>
        <table v-if="filteredUsers.length" class="w-full border-collapse border mt-4">
          <thead>
            <tr>
              <th class="border border-gray-400 px-4 py-2">Name</th>
              <th class="border border-gray-400 px-4 py-2">Email</th>
              <th class="border border-gray-400 px-4 py-2">Transaction Count</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id" class="border-b">
              <td class="border border-gray-400 px-4 py-2">{{ user.name }}</td>
              <td class="border border-gray-400 px-4 py-2">{{ user.email }}</td>
              <td class="border border-gray-400 px-4 py-2">{{ user.transactions_count }}</td>
            </tr>
          </tbody>
        </table>
        <div v-else class="mt-4 text-gray-500">No users match the selected filters.</div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, ref } from 'vue';
  import Header from '../../Components/Header.vue';
  import { router } from '@inertiajs/vue3';
  
  const props = defineProps(['user', 'userList']);
  const selectedMonth = ref('');
  const moreThanOneMillionOnly = ref('');
  
  const applyFilters = () => {
    const query = {};
    if (selectedMonth.value !== '') {
      query.month = selectedMonth.value;
    }
    if (moreThanOneMillionOnly.value) {
      query.moreThanOneMillionOnly = moreThanOneMillionOnly.value;
    }
    
    router.get('users-list', query);
  };
  
  // Filter users based on applied filters
  const filteredUsers = ref(props.userList);
  
  </script>
  