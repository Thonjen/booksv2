<template>
  <Layout>
    <div class="ml-64 p-8 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>

        <!-- Dashboard Cards -->
        <!-- Update the stats cards section in Dashboard.vue -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:-translate-y-2">
                <i class="fas fa-book text-4xl text-[#4e73df] mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Books</h3>
                <p class="text-2xl font-bold text-[#3498db]">{{ stats.totalBooks }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:-translate-y-2">
                <i class="fas fa-book-reader text-4xl text-[#4e73df] mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Borrows</h3>
                <p class="text-2xl font-bold text-[#3498db]">{{ stats.totalBorrowings }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:-translate-y-2">
                <i class="fas fa-undo-alt text-4xl text-[#4e73df] mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Returns</h3>
                <p class="text-2xl font-bold text-[#3498db]">{{ stats.totalReturns }}</p>
            </div>
       
           
        </div>

      
        <!-- Top 5 Most Received Books -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-bold text-gray-800 mb-4">Top 5 Most Borrowed Books</h3>
          <table class="w-full border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-4 text-left bg-gray-100">Book Title</th>
                <th class="py-2 px-4 text-left bg-gray-100">Receipt Count</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="book in topBooks" :key="book.title" class="odd:bg-gray-50 even:bg-white">
                <td class="py-2 px-4">{{ book.title }}</td>
                <td class="py-2 px-4">{{ book.count }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Recent Book Actions History -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Book Actions History</h3>
          <table class="w-full border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-4 text-left bg-gray-100">Book Title</th>
                <th class="py-2 px-4 text-left bg-gray-100">Action Type</th>
                <th class="py-2 px-4 text-left bg-gray-100">User</th>
                <th class="py-2 px-4 text-left bg-gray-100">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="action in bookActions" :key="action.id" class="odd:bg-gray-50 even:bg-white">
                <td class="py-2 px-4">{{ action.book.title }}</td>
                <td class="py-2 px-4">
                  <span 
                    :class="{
                      'text-green-600': action.type === 'return',
                      'text-red-600': action.type === 'cancellation'
                    }"
                  >
                    {{ action.type === 'return' ? 'Returned' : 'Reservation Cancelled' }}
                  </span>
                </td>
                <td class="py-2 px-4">{{ action.user.fullname }}</td>
                <td class="py-2 px-4">{{ formatDate(action.date) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { onMounted } from "vue";
import Layout from '../Layouts/FrontEndLayout.vue';

import { Chart } from "chart.js/auto";
import { Link } from "@inertiajs/vue3";

export default {
  components: {
    Link,
    Layout
  },
  props: {
    stats: Object,
    loginsData: Object,
    topBooks: Array,
    bookActions: Array,
  },
  mounted() {
    this.initChart();
  },
  methods: {
    initChart() {
      if (this.$refs.chartCanvas) {
        new Chart(this.$refs.chartCanvas, {
          type: "bar",
          data: {
            labels: Object.keys(this.loginsData),
            datasets: [
              {
                label: "Logins by Month",
                data: Object.values(this.loginsData),
                backgroundColor: "rgba(52, 152, 219, 0.7)",
                borderColor: "rgba(52, 152, 219, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            scales: {
              y: { beginAtZero: true },
            },
          },
        });
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }
  },
};
</script>
