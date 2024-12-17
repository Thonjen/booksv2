<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/FrontEndLayout.vue';

const form = useForm({
    user_id: '',
    book_id: '',
    borrow_date: '',
    due_date: ''
});

const props = defineProps({
    books: Array,
    users: Array
});

const createReceipt = () => {
    form.post(route('receipts.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Layout>
      <div class="ml-64 p-8 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
        <h2 class="text-xl font-semibold mb-4">Create Receipt</h2>
      <form @submit.prevent="createReceipt" class="space-y-4">
          <div>
              <label for="user_id" class="block text-sm font-medium">Borrower:</label>
              <select v-model="form.user_id" required class="mt-1 p-2 border border-gray-300 rounded w-full">
                  <option value="">Select Borrower</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">{{ user.fullname }}</option>
              </select>
          </div>

          <div>
              <label for="book_id" class="block text-sm font-medium">Book:</label>
              <select v-model="form.book_id" required class="mt-1 p-2 border border-gray-300 rounded w-full">
                  <option value="">Select Book</option>
                  <option v-for="book in books" :key="book.id" :value="book.id">{{ book.title }}</option>
              </select>
          </div>

          <div>
              <label for="borrow_date" class="block text-sm font-medium">Borrow Date:</label>
              <input type="date" v-model="form.borrow_date" required class="mt-1 p-2 border border-gray-300 rounded w-full">
          </div>

          <div>
              <label for="due_date" class="block text-sm font-medium">Due Date:</label>
              <input type="date" v-model="form.due_date" required class="mt-1 p-2 border border-gray-300 rounded w-full">
          </div>

          <button type="submit" :disabled="form.processing" class="w-full p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring">
              Create Receipt
          </button>
      </form>
      </div>
    </Layout>
</template>