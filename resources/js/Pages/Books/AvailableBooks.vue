<template>
  <Layout>
    <div class="ml-64 p-8 flex-1 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen">
      <main class="p-6 bg-white shadow rounded-lg">
        <!-- Search Bar -->
        <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-inner mb-6">
          <img src="/img/icon.png" class="w-6 h-6 mr-3 opacity-70" alt="Search Icon" />
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search by title, author, or course..."
            class="w-full p-3 bg-transparent text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
          />
        </div>

        <!-- Add tabs for different views -->
        <div class="mb-6 flex space-x-4">
          <button 
            v-for="tab in tabs" 
            :key="tab.value"
            @click="activeTab = tab.value"
            :class="[
              'px-4 py-2 rounded-lg',
              activeTab === tab.value ? 'bg-blue-500 text-white' : 'bg-gray-200'
            ]"
          >
            {{ tab.label }}
            <span class="ml-2 bg-white text-blue-500 rounded-full px-2 py-1 text-xs">
              {{ getTabCount(tab.value) }}
            </span>
          </button>
        </div>

        <!-- Books Table with enhanced status display -->
        <table v-if="activeTab === 'books'" class="min-w-full">
          <thead class="bg-blue-50 text-gray-600 text-sm uppercase tracking-wider">
            <tr>
              <th class="px-4 py-2 text-left">Cover</th>
              <th class="px-4 py-2 text-left">Title</th>
              <th class="px-4 py-2 text-left">Author</th>
              <th class="px-4 py-2 text-left">Course</th>
              <th class="px-4 py-2 text-left">Book ID</th>
              <th class="px-4 py-2 text-left">Availability</th>
              <th class="px-4 py-2 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="book in filteredBooks" :key="book.id">
              <!-- Book Cover -->
              <td class="px-4 py-2">
                <img 
                :src="book.image_url || '/img/default-book.jpg'" 
                alt="Book Cover" 
                  class="w-full h-20 object-cover"
                />
              </td>
              <!-- Book Title -->
              <td class="px-4 py-2">
                <span class="text-gray-700 font-medium">{{ book.title }}</span>
              </td>
              <!-- Book Author -->
              <td class="px-4 py-2">{{ book.author }}</td>
              <!-- Course -->
              <td class="px-4 py-2">
                <span class="text-gray-600">{{ book.course || 'N/A' }}</span>
              </td>
              <!-- Book ID -->
              <td class="px-4 py-2">{{ book.bookId }}</td>
              <!-- Availability -->
              <td class="px-4 py-2">
                <div class="flex flex-col">
                  <span :class="getStatusClass(book.availability)">
                    {{ book.availability }}
                  </span>
                  <span v-if="book.availability === 'Borrowed' && book.current_borrower" class="text-sm text-gray-600">
                    Borrowed by: {{ book.current_borrower.fullname }}
                    <br>
                    Due: {{ formatDate(book.current_borrower.due_date) }}
                  </span>
                  <span v-if="book.availability === 'Reserved' && book.current_reserver" class="text-sm text-gray-600">
                    Reserved by: {{ book.current_reserver.fullname }}
                    <br>
                    Until: {{ formatDate(book.current_reserver.until_date) }}
                  </span>
                </div>
              </td>
              <!-- Actions -->
              <td class="px-4 py-2 flex justify-center space-x-2">
                <button
                  @click="updateBook(book)"
                  class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                >
                  Edit
                </button>
                <button
                  @click="deleteBook(book.id)"
                  class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <table v-if="activeTab === 'borrowed'" class="min-w-full">
          <thead class="bg-blue-50">
            <tr>
              <th class="px-4 py-2">Book</th>
              <th class="px-4 py-2">Borrower</th>
              <th class="px-4 py-2">Phone Number</th>
              <th class="px-4 py-2">Due Date</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="book in borrowedBooks" :key="book.id">
              <td class="px-4 py-2">{{ book.title }}</td>
              <td class="px-4 py-2">{{ book.current_borrower?.fullname }}</td>
              <td class="px-4 py-2">{{ book.current_borrower?.phone_number || 'N/A' }}</td>
              <td class="px-4 py-2">{{ formatDate(book.current_borrower?.due_date) }}</td>
              <td class="px-4 py-2">
                <button @click="returnBook(book.id)" class="bg-green-500 text-white px-3 py-1 rounded">
                  Return
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Reserved Books Table -->
        <table v-if="activeTab === 'reserved'" class="min-w-full">
          <thead class="bg-blue-50">
            <tr>
              <th class="px-4 py-2">Book</th>
              <th class="px-4 py-2">Reserved By</th>
              <th class="px-4 py-2">Phone Number</th>
              <th class="px-4 py-2">Until Date</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="book in reservedBooks" :key="book.id">
              <td class="px-4 py-2">{{ book.title }}</td>
              <td class="px-4 py-2">{{ book.current_reserver?.fullname }}</td>
              <td class="px-4 py-2">{{ book.current_reserver?.phone_number || 'N/A' }}</td>
              <td class="px-4 py-2">{{ formatDate(book.current_reserver?.until_date) }}</td>
              <td class="px-4 py-2">
                <button @click="cancelReservation(book.id)" class="bg-red-500 text-white px-3 py-1 rounded">
                  Cancel
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Requests Table -->
        <template v-if="activeTab === 'requests'">
          <!-- Pending Requests Table -->
          <h3 class="text-lg font-semibold mb-4">Pending Requests</h3>
          <table class="min-w-full mb-8">
            <thead class="bg-blue-50">
              <tr>
                <th class="px-4 py-2">Book</th>
                <th class="px-4 py-2">Requester</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Start Date</th>
                <th class="px-4 py-2">End Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="request in pendingRequests" :key="request.id">
                <td class="px-4 py-2">{{ request.book.title }}</td>
                <td class="px-4 py-2">{{ request.user.fullname }}</td>
                <td class="px-4 py-2 capitalize">{{ request.request_type }}</td>
                <td class="px-4 py-2">{{ formatDate(request.request_date) }}</td>
                <td class="px-4 py-2">{{ formatDate(request.return_date) }}</td>
                <td class="px-4 py-2">
                  <span :class="{
                    'text-yellow-600': request.status === 'pending',
                    'text-green-600': request.status === 'approved',
                    'text-red-600': request.status === 'rejected'
                  }">
                    {{ request.status }}
                  </span>
                </td>
                <td class="px-4 py-2 space-x-2">
                  <button 
                    @click="approveRequest(request.id)" 
                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
                  >
                    Approve
                  </button>
                  <button 
                    @click="rejectRequest(request.id)" 
                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                  >
                    Reject
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Rejected Requests Table -->
          <h3 class="text-lg font-semibold mb-4 mt-[20vh]">History</h3>
          <table class="min-w-full">
            <thead class="bg-blue-50">
              <tr>
                <th class="px-4 py-2">Book</th>
                <th class="px-4 py-2">Requester</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="request in historyRequests" :key="request.id">
                <td class="px-4 py-2">{{ request.book.title }}</td>
                <td class="px-4 py-2">{{ request.user.fullname }}</td>
                <td class="px-4 py-2">{{ request.request_type }}</td>
                <td class="px-4 py-2">{{ formatDate(request.request_date) }}</td>
                <td class="px-4 py-2">{{ request.status }}</td>
              </tr>
            </tbody>
          </table>

          
        </template>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" @click.self="closeModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Edit Book Details</h3>
            
            <!-- Title -->
            <div class="mb-4">
              <label for="editTitle" class="block text-sm font-medium text-gray-600">Title:</label>
              <input
                type="text"
                v-model="editBookData.title"
                id="editTitle"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Author -->
            <div class="mb-4">
              <label for="editAuthor" class="block text-sm font-medium text-gray-600">Author:</label>
              <input
                type="text"
                v-model="editBookData.author"
                id="editAuthor"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Course -->
            <div class="mb-4">
              <label for="editCourse" class="block text-sm font-medium text-gray-600">Course:</label>
              <input
                type="text"
                v-model="editBookData.course"
                id="editCourse"
                placeholder="e.g., Computer Science, Mathematics, Literature"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Availability -->
            <div class="mb-4">
              <label for="availabilitySelect" class="block text-sm font-medium text-gray-600">Availability:</label>
              <select
                v-model="editBookData.availability"
                id="availabilitySelect"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="Available">Available</option>
                <option value="Borrowed">Borrowed</option>
                <option value="Reserved">Reserved</option>
              </select>
            </div>

            <!-- Image Upload -->
            <!-- In the Modal section, add this before the image upload input -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600">Current Cover:</label>
              <img 
                :src="editBookData.image_url || '/img/default-book.jpg'" 
                alt="Current Book Cover" 
                class="h-20 object-cover rounded mt-2"
              />
            </div>

            <!-- Update the image upload section -->
            <div class="mb-4">
              <label for="imageUpload" class="block text-sm font-medium text-gray-600">
                Update Book Cover:
              </label>
              <input
                type="file"
                id="imageUpload"
                @change="handleImageUpload"
                accept="image/jpeg,image/png,image/jpg"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-6 space-x-4">
              <button
                @click="saveBook"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition w-full"
              >
                Save
              </button>
              <button
                @click="closeModal"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition w-full"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>

      </main>
    </div>
  </Layout>
</template>


<script>
  import { Link } from '@inertiajs/vue3';
  import Layout from '../../Layouts/FrontEndLayout.vue';
  import Modal from '../../Components/Modal.vue';

export default {
  components: {
        Link,
        Layout,
        Modal
    },
  props: {
    books: Array,
    bookRequests: Array
  },
  data() {
    return {
      activeTab: 'books',
      searchQuery: '',
      showModal: false,
      editBookData: {
        id: null,
        title: '',
        author: '',
        course: '',
        availability: 'Available',
        availabilityDate: '',
        image_url: null
      },
      tabs: [
        { label: 'All Books', value: 'books' },
        { label: 'Requests', value: 'requests' },
        { label: 'Borrowed', value: 'borrowed' },
        { label: 'Reserved', value: 'reserved' }
      ],
      requestForm: {
        type: 'borrow',
        request_date: '',
        return_date: '',
        book_id: null
      }
    };
  },
  computed: {
    filteredBooks() {
      return this.books.filter(book => {
        return book.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
               book.author.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
               (book.course && book.course.toLowerCase().includes(this.searchQuery.toLowerCase()));
      });
    },
    borrowedBooks() {
      return this.books.filter(book => book.availability === 'Borrowed');
    },
    reservedBooks() {
      return this.books.filter(book => book.availability === 'Reserved');
    },
    pendingRequests() {
      return this.bookRequests.filter(request => request.status === 'pending');
    },

    historyRequests() {
      return this.bookRequests.filter(request => request.status === 'rejected' || request.status === 'approved');
    }
  },

  methods: {
    getStatusClass(status) {
      return {
        'Available': 'bg-green-100 text-green-800',
        'Borrowed': 'bg-red-100 text-red-800',
        'Reserved': 'bg-yellow-100 text-yellow-800'
      }[status] || 'bg-gray-100 text-gray-800'
    },
    getTabCount(tab) {
      switch(tab) {
        case 'books': return this.books.length
        case 'borrowed': return this.books.filter(b => b.availability === 'Borrowed').length
        case 'reserved': return this.books.filter(b => b.availability === 'Reserved').length
        case 'requests': return this.bookRequests.length
      }
    },
    async approveRequest(requestId) {
        try {
            await this.$inertia.patch(route('book-requests.approve', requestId));
        } catch (error) {
            alert('Error approving request');
        }
    },

    async rejectRequest(requestId) {
        try {
            await this.$inertia.patch(route('book-requests.reject', requestId));
        } catch (error) {
            alert('Error rejecting request');
        }
    },

    returnBook(bookId) {
      this.$inertia.post(route('receipts.return', bookId), {
        action_type: 'return',
        return_date: new Date().toISOString(),
      });
    },
    
    cancelReservation(bookId) {
      this.$inertia.delete(route('reserve.destroy', bookId), {
        data: {
          action_type: 'cancellation',
          cancellation_date: new Date().toISOString(),
        }
      });
    },
    
    formatDate(date) {
      if (!date) return '';
      return new Date(date).toLocaleDateString();
    },
    updateBook(book) {
      this.editBookData = {
        id: book.id,
        title: book.title,
        author: book.author,
        course: book.course || '',
        availability: book.availability || 'Available',
        availabilityDate: book.availabilityDate || '',
        image_url: book.image_url, // Include the current image URL
      };
      this.showModal = true;
    },
    saveBook() {
      const formData = new FormData();
      formData.append('_method', 'PUT'); // Method spoofing for PUT
      formData.append('title', this.editBookData.title);
      formData.append('author', this.editBookData.author);
      formData.append('course', this.editBookData.course);
      formData.append('availability', this.editBookData.availability);
      
      if (this.editBookData.image_url instanceof File) {
        formData.append('image', this.editBookData.image_url); // Note: changed to 'image' to match controller
      }

      this.$inertia.post(route('books.update', this.editBookData.id), formData, {
        preserveScroll: true,
        onSuccess: () => {
          this.closeModal();
        },
      });
    },

    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.editBookData.image_url = file;
      }
    },

    closeModal() {
      this.showModal = false;
      this.editBookData = {
        id: null,
        title: '',
        author: '',
        course: '',
        availability: 'Available',
        availabilityDate: '',
        image_url: null
      };
    },
    showRequestModal(book) {
      this.requestForm.book_id = book.id;
      this.showRequestModal = true;
    },
    closeRequestModal() {
      this.requestForm = {
        type: 'borrow',
        request_date: '',
        return_date: '',
        book_id: null
      };
      this.showRequestModal = false;
    },
    submitRequest() {
      this.$inertia.post(route('book-requests.store'), this.requestForm);
      this.closeRequestModal();
    }
  }
};
</script>

<style>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>
