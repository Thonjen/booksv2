<template>
  <Link href="/" class="absolute top-5 left-5 z-50">
    <img src="/img/logo3.png" alt="BOOK CLOUD Logo" class="max-w-[220px] h-auto">
  </Link>
  <div class="flex items-center justify-center min-h-screen bg-cover bg-center relative" style="background-image: url('/img/sign.jpg');">
    <div class="absolute inset-0 bg-[#081c2d]  from-indigo-500 via-purple-500 to-teal-500  opacity-80"></div>
    <div class="relative bg-[#081c2d]   rounded-xl shadow-2xl p-8 max-w-lg w-full">
      <h2 class="text-3xl font-extrabold text-center text-white mb-4">Create Account</h2>
      <p class="text-center text-gray-300 mb-6">Getting started is easy</p>
      <form id="registrationForm" @submit.prevent="registerUser" class="space-y-4">
        <div>
          <input 
            type="text" 
            v-model="fullname" 
            placeholder="Full Name" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  transition"
          >
        </div>
        <div>
          <input 
            type="email" 
            v-model="email" 
            placeholder="Email" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  transition"
          >
        </div>
        <div>
          <input 
            type="text" 
            v-model="studentid" 
            placeholder="Student ID" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  transition"
          >
        </div>
        <div>
          <input 
            type="text" 
            v-model="courseSection" 
            placeholder="Course & Section" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  transition"
          >
        </div>
        <div>
          <select 
            v-model="gender" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  transition"
          >
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="relative">
          <input 
            :type="passwordVisible ? 'text' : 'password'" 
            v-model="password" 
            placeholder="Password" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  pr-12 transition"
          >
          <span 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-teal-600  transition" 
            @click="togglePasswordVisibility('password')"
          >
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="relative">
          <input 
            :type="confirmPasswordVisible ? 'text' : 'password'" 
            v-model="confirmPassword" 
            placeholder="Confirm Password" 
            required 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500  focus:border-teal-500  pr-12 transition"
          >
          <span 
            class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-teal-600 transition" 
            @click="togglePasswordVisibility('confirmPassword')"
          >
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div>
          <input 
            type="tel" 
            v-model="phone_number" 
            placeholder="Phone Number" 
            class="w-full px-4 py-3 border border-gray-600 rounded-md text-sm bg-[#2E3B4E] text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
          >
        </div>
        <p class="text-center text-sm text-gray-400">
          Already have an account? 
          <Link href="/login" class="text-teal-500 font-medium hover:underline">Login</Link>
        </p>
        <div>
          <button 
            :disabled="isLoading" 
            type="submit" 
            class="w-full py-3 text-sm font-bold text-white bg-teal-500  rounded-lg hover:bg-teal-600 focus:outline-none focus:ring-4 focus:ring-pink-300 disabled:opacity-50 transition"
          >
            {{ isLoading ? 'Registering...' : 'Create Account' }}
          </button>
        </div>
        <p id="registrationErrorMessage" class="text-center text-red-500 text-sm">{{ registrationMessage }}</p>
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
      fullname: '',
      email: '',
      studentid: '',
      courseSection: '',
      gender: '',
      password: '',
      confirmPassword: '',
      phone_number: '',
      registrationMessage: '',
      passwordVisible: false,
      confirmPasswordVisible: false,
      isLoading: false,
    };
  },
  methods: {
    togglePasswordVisibility(field) {
      if (field === 'password') {
        this.passwordVisible = !this.passwordVisible;
      } else if (field === 'confirmPassword') {
        this.confirmPasswordVisible = !this.confirmPasswordVisible;
      }
    },
    async registerUser() {
      if (this.password !== this.confirmPassword) {
        this.registrationMessage = 'Passwords do not match!';
        return;
      }
      const userData = {
        fullname: this.fullname,
        email: this.email,
        studentid: this.studentid,
        courseSection: this.courseSection,
        gender: this.gender,
        password: this.password,
        password_confirmation: this.confirmPassword,
        phone_number: this.phone_number,
      };
      this.isLoading = true;
      try {
        const response = await axios.post('/api/register', userData);
        if (response.data.success) {
          window.location.href = response.data.redirect || '/student/home';
        }
      } catch (error) {
        if (error.response && error.response.data.error) {
          this.registrationMessage = Object.values(error.response.data.error).join(', ');
        } else {
          this.registrationMessage = 'An error occurred. Please try again.';
        }
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
