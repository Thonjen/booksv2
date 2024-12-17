<template>
  <Layout>
    <div class="ml-64 p-6 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
      <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-center text-xl font-semibold mb-4">Add New Book</h2>
        <form @submit.prevent="handleAddBook" class="space-y-4">
          <div>
            <label for="title" class="block text-sm font-medium mb-1">Book Title</label>
            <input v-model="form.title" id="title" type="text" required 
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200" />
            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
          </div>

          <div>
            <label for="author" class="block text-sm font-medium mb-1">Author</label>
            <input v-model="form.author" id="author" type="text" required 
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200" />
            <div v-if="form.errors.author" class="text-red-500 text-xs mt-1">{{ form.errors.author }}</div>
          </div>

          <div>
            <label for="bookId" class="block text-sm font-medium mb-1">Book ID</label>
            <input v-model="form.bookId" id="bookId" type="text" required 
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200" />
            <div v-if="form.errors.bookId" class="text-red-500 text-xs mt-1">{{ form.errors.bookId }}</div>
          </div>

          <div>
            <label for="publicationDate" class="block text-sm font-medium mb-1">Publication Date</label>
            <input v-model="form.publicationDate" id="publicationDate" type="date" required 
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200" />
            <div v-if="form.errors.publicationDate" class="text-red-500 text-xs mt-1">{{ form.errors.publicationDate }}</div>
          </div>

          <div>
            <label for="description" class="block text-sm font-medium mb-1">Book Description</label>
            <textarea v-model="form.description" id="description" 
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200 resize-y h-24"></textarea>
            <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
          </div>

          <div>
            <label for="image" class="block text-sm font-medium mb-1">Book Cover Image</label>
            <input 
              type="file" 
              id="image" 
              @input="form.image = $event.target.files[0]"
              accept="image/*"
              class="w-full border rounded-md p-2 text-sm focus:ring focus:ring-blue-200" 
            />
            <div v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</div>
            
            <!-- Preview image -->
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" class="h-32 w-auto object-cover rounded" />
            </div>
          </div>

          <button type="submit" :disabled="form.processing" 
            class="w-full bg-blue-600 text-white py-2 rounded-md text-sm hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-200">
            Add Book
          </button>
        </form>
      </div>
    </div>

  </Layout>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/FrontEndLayout.vue';

export default {
  components: {
    Link,
    Layout
  },
  data() {
    return {
      imagePreview: null
    }
  },
  setup() {
    const form = useForm({
      title: '',
      author: '',
      bookId: '',
      publicationDate: '',
      description: '',
      availability: 'Available',
      image: null
    });

    return { form };
  },
  methods: {
    handleAddBook() {
      this.form.post(route('books.store'), {
        onSuccess: () => {
          this.form.reset();
          this.imagePreview = null;
        },
      });
    },
    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.imagePreview = URL.createObjectURL(file);
      }
    }
  }
};
</script>
