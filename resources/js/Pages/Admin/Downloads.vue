<script setup>
import { watch, ref, defineComponent } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import moment from "moment";
import { Notyf } from "notyf";

const props = defineProps(["downloads"]);

defineComponent({
  Head,
});

const deleteDownload = async (id) => {
  if (confirm("Are you sure you want to delete this download link?")) {
    await axios.post(`/administrator-panel/downloads/delete/${id}`);
    new Notyf().success("Download link deleted successfully.");
    router.visit(location.href);
  }
};
</script>

<template>
  <layout>
    <div class="">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Downloads Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage game client and patch downloads</p>
          </div>
          <Link
            href="/administrator-panel/downloads/create"
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 group"
          >
            <svg class="h-5 w-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Download
          </Link>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50">
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Link</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="download in downloads" :key="download.id" class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ download.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ download.title }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500 line-clamp-2">{{ download.description }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase"
                        :class="{
                          'bg-blue-100 text-blue-800': download.type === 'client',
                          'bg-green-100 text-green-800': download.type === 'patch',
                        }">
                    {{ download.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a :href="download.link" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 hover:underline truncate max-w-xs block">
                    {{ download.link }}
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ moment(download.created_at).format("MMM D, YYYY") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ moment(download.updated_at).format("MMM D, YYYY") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex items-center justify-center space-x-3">
                    <Link
                      :href="`/administrator-panel/downloads/edit/${download.id}`"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                    >
                      <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Edit
                    </Link>

                    <button
                      @click="deleteDownload(download.id)"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200"
                    >
                      <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </layout>
</template>

<style scoped>
/* Only keep styles that might be needed for specific overrides */
</style>
<style scoped></style>
<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
