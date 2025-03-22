<script setup>
import { defineComponent, ref } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@layouts/AdminLayout.vue";
import { Notyf } from "notyf";

const form = ref({
  title: "",
  type: "",
  link: "",
  description: "",
});

const isSubmitting = ref(false);
const submitForm = () => {
  isSubmitting.value = true;

  axios
    .post("/administrator-panel/downloads/create", form.value)
    .then((res) => {
      isSubmitting.value = false;

      if (res.status === 200) {
        // router.visit("/administrator-panel/downloads");
        new Notyf().success("Download Link Created Successfully");
      }
    })
    .catch((err) => {
      isSubmitting.value = false;
      console.log(err);
    });
};
</script>
<template>
  <layout>
    <div class="mt-6">
      <div class="bg-white shadow-md rounded-lg p-6">
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label for="Title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input
              type="text"
              id="Title"
              v-model="form.title"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2"
              required
            />
          </div>
          <div class="mb-4">
            <label for="Link" class="block text-sm font-medium text-gray-700">Link:</label>
            <input
              type="url"
              id="Link"
              v-model="form.link"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2"
              required
            />
          </div>
          <div class="mb-4">
            <label for="Type" class="block text-sm font-medium text-gray-700">Type:</label>
            <select v-model="form.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2">
              <option value="patcher">Patcher</option>
              <option value="client">Full Client</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="Description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea
              id="Description"
              v-model="form.description"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2"
            ></textarea>
          </div>
          <div class="flex justify-end">
            <button
              type="submit"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
              :disabled="isSubmitting"
            >
              <span v-if="isSubmitting">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              <span v-else>Submit</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<style scoped></style>
