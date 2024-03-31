<template>
  <Header :user="user" />
  <div class="mt-4">
    <h2 class="text-lg font-semibold mb-2">Generate Statements</h2>
    <form @submit.prevent="generateStatements">
      <div class="items-center mb-2">
        <label for="month" class="mr-2">Select Month:</label>
        <select v-model="selectedMonth" id="month" class="border rounded py-1 mr-4">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
      </div>
      <div class="items-center mb-2">
        <label for="year" class="mr-2">Select Year:</label>
        <input type="number" v-model="selectedYear" id="year" class="border rounded px-2 py-1" :min="minYear" :max="maxYear">
      </div>
      <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded mt-2">Generate</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import download from 'downloadjs'
import Header from '../../Components/Header.vue';
import axios from 'axios';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    accountId: {
        type: Number,
        required: true
    }
})

const selectedMonth = ref(1); // Default to January
const currentYear = new Date().getFullYear(); // Get the current year
const selectedYear = ref(currentYear); // Default to current year
const minYear = 1980; // Minimum allowed year
const maxYear = currentYear; // Maximum allowed year (current year)

const generateStatements = () => {
  const queryParams = {
    responseType: 'blob', // had to add this one here
    params: {
      accountId: props.accountId,
      month: selectedMonth.value,
      year: selectedYear.value
    }
  };
  // Redirect to the generate-statements route with the provided query parameters
  axios.get('/pdf-statements', queryParams)
    .then(response => {
      console.log(response)
      download(response.data)
    })
    .catch(error => {
      // Handle error
      console.error(error);
    });
};

</script>
