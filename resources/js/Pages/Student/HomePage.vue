<template>
    <!-- Header Section -->
    <header class="bg-[#081c2d] text-white py-3 px-6 shadow-sm mb-4 flex items-center justify-between">
      <!-- Logo Section -->
      <div class="flex items-center space-x-4">
        <img src="/img/logo2.png" alt="BOOK CLOUD Logo" class="max-w-[150px] h-auto" />
      </div>

      <!-- Search Bar Section (Centered) -->
      <div class="flex-1 mx-6">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Search for books..."
          class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200"
        />
      </div>

      <!-- Profile Section -->
      <div class="flex items-center space-x-4">
        <!-- Profile Button -->
        <button 
          @click="toggleDropdown" 
          class="w-10 h-10 rounded-full bg-[#00509e] flex items-center justify-center text-white text-sm font-semibold shadow-md transition-all hover:scale-105"
        >
          {{ userInitial }}
        </button>

        <!-- User Info -->
        <div class="flex flex-col items-end space-y-0.5">
          <p class="text-sm font-semibold">{{ user.fullname }}</p>
          <p class="text-xs text-gray-400">{{ user.studentid }}</p>
          <p class="text-xs text-gray-400">{{ user.phone_number || 'No phone number' }}</p>
        </div>

        <!-- Dropdown Menu -->
        <div v-if="showDropdown" class="absolute top-12 right-0 w-44 bg-white rounded-lg shadow-md py-1 z-50">
          <button 
            @click="openProfileModal"
            class="block w-full text-left px-4 py-1 text-gray-800 hover:bg-gray-100 rounded-md transition-all duration-200"
          >
            <i class="fas fa-user-circle mr-2 text-lg"></i>
            Edit Profile
          </button>
          <Link 
            :href="route('logout')" 
            method="post" 
            as="button"
            class="block w-full text-left px-4 py-1 text-gray-800 hover:bg-gray-100 rounded-md transition-all duration-200"
          >
            <i class="fas fa-sign-out-alt mr-2 text-lg"></i>
            Logout
          </Link>
        </div>
      </div>
    </header>

    <!-- Book Cards -->
    <main class="container mx-auto p-10">
      <h1 class="text-2xl font-semibold mb-6">Available Books</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="book in filteredBooks" 
        :key="book.id" 
        @click="openModal(book)"
        class="bg-white shadow-md rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition transform">
        <img 
          :src="book.image_url || '/img/default-book.jpg'" 
          alt="Book Cover" 
          class="w-full h-56 object-cover"
        />
        <div class="p-4">
          <h6 class="text-lg font-semibold text-gray-800 truncate">{{ book.title }}</h6>
          <p class="text-sm text-gray-600 mt-2">{{ book.description }}</p>
          <p class="text-sm text-gray-600 mt-1">Author: {{ book.author }}</p>
          <span 
            :class="{
              'text-green-600 font-medium': book.availability === 'Available',
              'text-red-600 font-medium': book.availability === 'Borrowed',
              'text-yellow-600 font-medium': book.availability === 'Reserved'
            }"
            class="block mt-2">
            {{ book.availability }}
          </span>
        </div>
      </div>
    </div>
    </main>
    <!-- Modal for Book Request -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      @click.self="closeModal">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md animate-fade-in relative">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Book</h3>
        <form @submit.prevent="submitRequest" class="space-y-4">
          <!-- Request Type Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Request Type:</label>
            <select 
              v-model="requestType" 
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            >
              <option value="">Select a request type</option>
              <option value="borrow">Borrow</option>
              <option value="reserve">Reserve</option>
            </select>
          </div>

          <!-- Start Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Start Date:</label>
            <input 
              type="date" 
              v-model="startDate"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              :min="new Date().toISOString().split('T')[0]"
              required
            />
          </div>

          <!-- Return Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              {{ requestType === 'borrow' ? 'Return Date' : 'Until Date' }}:
            </label>
            <input 
              type="date" 
              v-model="returnDate"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              :min="startDate"
              required
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="!requestType || !startDate || !returnDate"
            >
              Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Profile Edit Modal -->
    <div 
      v-if="isProfileModalOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      @click.self="closeProfileModal"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md animate-fade-in">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Profile</h3>
        <form @submit.prevent="updateProfile" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name:</label>
            <input 
              type="text" 
              v-model="profileForm.fullname"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email:</label>
            <input 
              type="email" 
              v-model="profileForm.email"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Phone Number:</label>
            <input 
              type="tel" 
              v-model="profileForm.phone_number"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="closeProfileModal"
              class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
            >
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

</template>

<script>
  import { Link, usePage, router } from '@inertiajs/vue3';

  export default {
    components: {
      Link
    },
    props: {
      books: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        searchQuery: '',
        isModalOpen: false,
        isProfileModalOpen: false,
        showDropdown: false,
        selectedBook: null,
        requestType: '',
        startDate: '',
        returnDate: '',
        profileForm: {
          fullname: '',
          email: '',
          phone_number: ''
        }
      }
    },
    computed: {
      user() {
        return usePage().props.auth.user;
      },
      userInitial() {
        return this.user.fullname.charAt(0);
      },
      filteredBooks() {
        return this.books.filter(book => 
          book.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
          book.author.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
    },
    methods: {
      toggleDropdown() {
        this.showDropdown = !this.showDropdown;
      },
      openModal(book) {
        this.selectedBook = book;
        this.isModalOpen = true;
      },
      closeModal() {
        this.isModalOpen = false;
        this.selectedBook = null;
      },
      submitRequest() {
    if (this.requestType === 'reserve') {
        router.post(route('student.reserve-book'), {
            book_id: this.selectedBook.id,
            reservation_date: this.startDate,
            return_date: this.returnDate
        }, {
            onSuccess: () => {
                this.closeModal();
                window.location.reload();
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    } else if (this.requestType === 'borrow') {
        router.post(route('book-requests.store'), {
            book_id: this.selectedBook.id,
            request_type: 'borrow',
            request_date: this.startDate,
            return_date: this.returnDate
        }, {
            onSuccess: () => {
                this.closeModal();
                window.location.reload();
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    }
},
      openProfileModal() {
        this.profileForm.fullname = this.user.fullname;
        this.profileForm.email = this.user.email;
        this.profileForm.phone_number = this.user.phone_number;
        this.isProfileModalOpen = true;
      },
      closeProfileModal() {
        this.isProfileModalOpen = false;
      },
      updateProfile() {
        router.put(route('profile.update.basic'), this.profileForm, {
          onSuccess: () => {
            this.closeProfileModal();
            // You can add a success notification here if you want
          },
          onError: (errors) => {
            // Handle errors if needed
            console.log(errors);
          }
        });
      }
    }
  }
</script>

<style scoped>
  /* Custom Tailwind CSS */
  .animate-fade-in {
    animation: fadeIn 0.3s ease-out;
  }

  @keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
  }
</style>
