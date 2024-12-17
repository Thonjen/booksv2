<template>
  <Layout>
    <div class="ml-64 p-8 flex-1 bg-cover bg-center" style="background-image: url('/img/background.jpg')">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold">Student List</h2>
          <button @click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Add New Student
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Full Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Student ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Course & Section
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Gender
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Phone Number
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">{{ student.fullname }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ student.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ student.studentid }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ student.courseSection }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ student.gender }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ student.phone_number || 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button @click="editStudent(student)" class="text-blue-600 hover:text-blue-800 mr-2">
                    Edit
                  </button>
                  <button @click="confirmDelete(student)" class="text-red-600 hover:text-red-800">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Modal :show="showModal" @close="closeModal">
      <div class="p-6">
        <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Student' : 'Add New Student' }}</h3>
        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <input v-model="form.fullname" type="text" placeholder="Full Name" class="w-full p-2 border rounded">
            <input v-model="form.email" type="email" placeholder="Email" class="w-full p-2 border rounded">
            <input v-model="form.studentid" type="text" placeholder="Student ID" class="w-full p-2 border rounded">
            <input v-model="form.courseSection" type="text" placeholder="Course & Section" class="w-full p-2 border rounded">
            <select v-model="form.gender" class="w-full p-2 border rounded">
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
            <input 
              v-model="form.phone_number" 
              type="tel" 
              placeholder="Phone Number" 
              class="w-full p-2 border rounded"
            >
            <div v-if="!isEditing">
              <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 border rounded">
              <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-2 border rounded mt-2">
            </div>
            <div v-if="isEditing" class="space-y-4 border-t pt-4 mt-4">
              <h4 class="font-medium">Change Password (optional)</h4>
              <input 
                v-model="form.current_password" 
                type="password" 
                placeholder="Current Password" 
                class="w-full p-2 border rounded"
              >
              <input 
                v-model="form.new_password" 
                type="password" 
                placeholder="New Password" 
                class="w-full p-2 border rounded"
              >
              <input 
                v-model="form.new_password_confirmation" 
                type="password" 
                placeholder="Confirm New Password" 
                class="w-full p-2 border rounded"
              >
            </div>
            <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">
                {{ form.errors.current_password }}
            </div>
            <div v-if="form.errors.new_password" class="text-red-500 text-sm mt-1">
                {{ form.errors.new_password }}
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="closeModal" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="showDeleteModal" @close="closeDeleteModal">
      <div class="p-6">
        <h3 class="text-lg font-medium mb-4">Confirm Delete</h3>
        <p>Are you sure you want to delete this student?</p>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="closeDeleteModal" class="px-4 py-2 border rounded">Cancel</button>
          <button @click="deleteStudent" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
        </div>
      </div>
    </Modal>
  </Layout>
</template>

<script setup>
import { ref } from 'vue';
import Layout from '@/Layouts/FrontEndLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  students: {
    type: Array,
    required: true
  }
});

const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const selectedStudent = ref(null);

const form = useForm({
  fullname: '',
  email: '',
  studentid: '',
  courseSection: '',
  gender: '',
  password: '',
  password_confirmation: '',
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
  phone_number: '',
});

const openCreateModal = () => {
  isEditing.value = false;
  form.reset();
  showModal.value = true;
};

const editStudent = (student) => {
  isEditing.value = true;
  selectedStudent.value = student;
  form.fullname = student.fullname;
  form.email = student.email;
  form.studentid = student.studentid;
  form.courseSection = student.courseSection;
  form.gender = student.gender;
  form.phone_number = student.phone_number;
  showModal.value = true;
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('students.update', selectedStudent.value.id), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post(route('students.store'), {
      onSuccess: () => closeModal(),
    });
  }
};

const confirmDelete = (student) => {
  selectedStudent.value = student;
  showDeleteModal.value = true;
};

const deleteStudent = () => {
  form.delete(route('students.delete', selectedStudent.value.id), {
    onSuccess: () => closeDeleteModal(),
  });
};

const closeModal = () => {
  showModal.value = false;
  form.reset();
  form.current_password = '';
  form.new_password = '';
  form.new_password_confirmation = '';
  selectedStudent.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  selectedStudent.value = null;
};
</script>