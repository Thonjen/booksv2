<template>
    <Layout>
        <div class="ml-64 p-8 flex-1">
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
                <h1 class="text-2xl font-bold mb-6">Edit Profile</h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input 
                            v-model="form.fullname" 
                            type="text" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        <div v-if="form.errors.fullname" class="text-red-500 text-sm mt-1">
                            {{ form.errors.fullname }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input 
                            v-model="form.email" 
                            type="email" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Student ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input 
                            v-model="form.studentid" 
                            type="text" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        <div v-if="form.errors.studentid" class="text-red-500 text-sm mt-1">
                            {{ form.errors.studentid }}
                        </div>
                    </div>

                    <!-- Course Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Section</label>
                        <input 
                            v-model="form.courseSection" 
                            type="text" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        <div v-if="form.errors.courseSection" class="text-red-500 text-sm mt-1">
                            {{ form.errors.courseSection }}
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select 
                            v-model="form.gender" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <div v-if="form.errors.gender" class="text-red-500 text-sm mt-1">
                            {{ form.errors.gender }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '../Layouts/FrontEndLayout.vue';

const props = defineProps({
    user: Object
});

const form = useForm({
    fullname: props.user.fullname,
    email: props.user.email,
    studentid: props.user.studentid,
    courseSection: props.user.courseSection,
    gender: props.user.gender,
});

const submit = () => {
    form.put(route('profile.update'));
};
</script>