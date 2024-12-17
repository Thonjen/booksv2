<template>
  <Layout>
      <div class="ml-64 p-8 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
          <div class="bg-white rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-semibold mb-6">Reserve Books</h2>

              <div class="overflow-x-auto">
                  <table class="min-w-full">
                      <thead class="bg-gray-50">
                          <tr>
                              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                  Book Title
                              </th>
                              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                  Author
                              </th>
                              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                  Availability
                              </th>
                              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                  Actions
                              </th>
                          </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                          <tr v-for="book in books" :key="book.id" class="hover:bg-gray-50">
                              <td class="px-6 py-4 whitespace-nowrap">{{ book.title }}</td>
                              <td class="px-6 py-4 whitespace-nowrap">{{ book.author }}</td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <span :class="{
                                      'px-2 py-1 text-xs font-semibold rounded-full': true,
                                      'bg-green-100 text-green-800': book.availability === 'Available',
                                      'bg-yellow-100 text-yellow-800': book.availability === 'Reserved',
                                      'bg-red-100 text-red-800': book.availability === 'Borrowed'
                                  }">
                                      {{ book.availability }}
                                  </span>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <button 
                                      v-if="book.availability === 'Available'"
                                      @click="reserveBook(book.id)"
                                      class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-sm text-xs"
                                  >
                                      Reserve
                                  </button>
                                  <span v-else class="text-gray-400 text-xs">
                                      Not Available
                                  </span>
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
import Layout from '@/Layouts/FrontEndLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  books: {
      type: Array,
      required: true
  }
});

const form = useForm({
  book_id: null
});

const reserveBook = (bookId) => {
  form.book_id = bookId;
  form.post(route('reserve.store'), {
      onSuccess: () => {
          form.reset();
      }
  });
};
</script>