<template>
  <Link href="/" class="absolute top-5 left-5 z-50">
    <img src="/img/logo3.png" alt="BOOK CLOUD Logo" class="max-w-[220px] h-auto">
  </Link>
  <div class="flex items-center justify-center min-h-screen bg-cover bg-center relative" style="background-image: url('/img/sign.jpg');">
    <div class="absolute inset-0 bg-[#081c2d] bg-opacity-90"></div>
    <div class="relative bg-[#081c2d] rounded-xl shadow-2xl p-8 max-w-md w-full border border-gray-700">
      <h2 class="text-3xl font-extrabold text-center text-white mb-4">Welcome Back</h2>
      <p class="text-center text-gray-400 mb-6">Log into your account</p>
      <form @submit.prevent="loginUser" id="loginForm" class="space-y-4">
        <div>
          <input 
            type="email" 
            v-model="email" 
            placeholder="Email" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#0a2a43] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
          >
        </div>
        <div class="relative">
          <input 
            :type="passwordVisible ? 'text' : 'password'" 
            v-model="password" 
            placeholder="Password" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#0a2a43] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 pr-12 transition"
          >
          <span 
            @click="togglePasswordVisibility" 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-teal-400 transition">
            <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </span>
        </div>
        <p class="text-center text-sm text-gray-400">
          Don’t have an account? 
          <Link href="/register" class="text-teal-400 font-medium hover:underline">Register</Link>
        </p>
        <div>
          <button 
            type="submit" 
            :disabled="isLoading" 
            class="w-full py-3 text-sm font-bold text-white bg-teal-500 rounded-lg hover:bg-teal-600 focus:outline-none focus:ring-4 focus:ring-teal-300 disabled:opacity-50 transition">
            {{ isLoading ? 'Logging in...' : 'Login' }}
          </button>
        </div>
        <p id="loginErrorMessage" class="text-center text-red-500 text-sm">{{ loginErrorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Link,
  },
  data() {
    return {
      email: '',
      password: '',
      loginErrorMessage: '',
      isLoading: false,
      passwordVisible: false,
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async loginUser() {
      this.isLoading = true;
      this.loginErrorMessage = '';

      const loginData = {
        email: this.email,
        password: this.password,
      };

      try {
        const response = await axios.post('/api/login', loginData);
        if (response.data.success) {
          window.location.href = response.data.redirect;
        }
      } catch (error) {
        this.loginErrorMessage = 'Invalid credentials. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
