<template>
    <div>
      <!-- Change Password Button -->
      <button 
        @click="openModal" 
        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
      >
        Change Password
      </button>
  
      <!-- Modal Backdrop -->
      <div 
        v-if="isModalOpen" 
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"
        @click="closeModal"
      ></div>
  
      <!-- Modal Content -->
      <div 
        v-if="isModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
      >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div 
            class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            @click.stop
          >
            <form @submit.prevent="submitForm">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Lock Icon -->
                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                      Change Password
                    </h3>
                    <div class="mt-4 space-y-4">
                      <!-- Current Password -->
                      <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                          Current Password
                        </label>
                        <div class="mt-1">
                          <input 
                            id="current_password" 
                            v-model="form.current_password" 
                            type="password" 
                            name="current_password" 
                            required 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          />
                          <p v-if="errors.current_password" class="mt-1 text-sm text-red-600">
                            {{ errors.current_password }}
                          </p>
                        </div>
                      </div>
  
                      <!-- New Password -->
                      <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">
                          New Password
                        </label>
                        <div class="mt-1">
                          <input 
                            id="new_password" 
                            v-model="form.new_password" 
                            type="password" 
                            name="new_password" 
                            required 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          />
                          <p v-if="errors.new_password" class="mt-1 text-sm text-red-600">
                            {{ errors.new_password }}
                          </p>
                        </div>
                      </div>
  
                      <!-- Confirm New Password -->
                      <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">
                          Confirm New Password
                        </label>
                        <div class="mt-1">
                          <input 
                            id="new_password_confirmation" 
                            v-model="form.new_password_confirmation" 
                            type="password" 
                            name="new_password_confirmation" 
                            required 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                  type="submit" 
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                  :disabled="isSubmitting"
                >
                  <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isSubmitting ? 'Updating...' : 'Update Password' }}
                </button>
                <button 
                  type="button" 
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  @click="closeModal"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  // Modal state
  const isModalOpen = ref(false)
  const isSubmitting = ref(false)
  const errors = ref({})
  
  // Form data
  const form = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  })
  
  // Open the modal
  const openModal = () => {
    isModalOpen.value = true
    // Reset the form when opening
    form.value = {
      current_password: '',
      new_password: '',
      new_password_confirmation: ''
    }
    errors.value = {}
  }
  
  // Close the modal
  const closeModal = () => {
    isModalOpen.value = false
  }
  
  // Handle form submission
  const submitForm = async () => {
    isSubmitting.value = true
    errors.value = {}
    
    try {
      // Send AJAX request to the server
      const response = await axios.post('/user-account/new-password', form.value)
      
      // Handle success
      isSubmitting.value = false
      closeModal()
      
      // Show success message (you can customize this or emit an event)
      alert('Password changed successfully!')
    } catch (error) {
      isSubmitting.value = false
      
      // Handle validation errors
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors
      } else {
        // Handle other errors
        alert('Something went wrong. Please try again.')
        console.error('Error changing password:', error)
      }
    }
  }
  </script>