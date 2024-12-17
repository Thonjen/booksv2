<template>
  <Layout>
    <div class="ml-64 p-8 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
      <div class="p-6 space-y-6">
        <h2 class="text-2xl font-semibold mb-4">Receipt History</h2>

        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Receipt ID</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Borrower</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Book Title</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">ISBN</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Borrow Date</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Due Date</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Return Date</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Fine</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="receipts.length === 0">
                <td colspan="10" class="px-6 py-4 text-center text-gray-500">No receipts found</td>
              </tr>
              <tr v-for="receipt in receipts" :key="receipt.id" class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ receipt.receipt_number }}</td>
                <td class="px-4 py-3">{{ receipt.borrowerName }}</td>
                <td class="px-4 py-3">{{ receipt.bookTitle }}</td>
                <td class="px-4 py-3">{{ receipt.ISBN }}</td>
                <td class="px-4 py-3">{{ receipt.borrowDate }}</td>
                <td class="px-4 py-3">{{ receipt.dueDate }}</td>
                <td class="px-4 py-3">{{ receipt.returnDate || 'Not returned' }}</td>
                <td class="px-4 py-3">₱{{ receipt.fine_amount || '0.00' }}</td>
                <td class="px-4 py-3">
                  <span :class="{
                    'px-2 py-1 text-xs font-semibold rounded-full': true,
                    'bg-green-100 text-green-800': receipt.status === 'returned',
                    'bg-yellow-100 text-yellow-800': receipt.status === 'active',
                    'bg-red-100 text-red-800': receipt.status === 'overdue'
                  }">
                    {{ receipt.status }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex space-x-2">
                    <button v-if="receipt.status === 'active'" @click="returnBook(receipt.id)" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-sm text-xs">
                      Return
                    </button>
                    <button v-if="receipt.status === 'returned'" @click="deleteReceipt(receipt.id)" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-sm text-xs">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/FrontEndLayout.vue';

const props = defineProps({
  receipts: {
    type: Array,
    required: true
  }
});

const returnBook = (receiptId) => {
  if (confirm('Are you sure you want to mark this book as returned?')) {
    useForm().post(route('receipts.return', receiptId));
  }
};

const deleteReceipt = (receiptId) => {
  if (confirm('Are you sure you want to delete this receipt?')) {
    useForm().delete(route('receipts.destroy', receiptId));
  }
};
</script>

<style scoped>
/* Optional: Adjust table styling if needed */
</style>
