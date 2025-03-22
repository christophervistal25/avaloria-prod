<script setup>
import { watch, ref, defineComponent } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import moment from "moment";
import { Notyf } from "notyf";

const props = defineProps(["news", "currentPage", "search"]);

defineComponent({
  Head,
});

const newsType = ref([
  "News",
  "Event",
  "Patch",
  "Promotion",
  "Announcement",
  "Maintenance",
]);

const searchAccount = ref(props.search);
const renderPage = (page) => {
  page.url = page.url.replace("http", "https");
  router.visit(page.url, {
    preserveScroll: true,
    preserveState: true,
  });
};

const deleteNews = (id) => {
  if (confirm("Are you sure you want to delete this news?")) {
    axios
      .delete(`/administrator-panel/delete-news/${id}`)
      .then((res) => {
        if (res.status === 200) {
          new Notyf().success("News deleted successfully");
          router.visit(location.href, {
            preserveScroll: true,
            preserveState: true,
          });
        }
      })
      .catch((err) => new Notyf().error(err.response.data.message));
  }
};
</script>
<template>
  <layout>
    <div class="">
      <div class="mb-8">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">News Management</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage all news articles</p>
          </div>
          <Link
            href="/administrator-panel/create-news"
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 group"
          >
            <svg 
              class="h-5 w-5 mr-2 group-hover:animate-pulse" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M12 4v16m8-8H4"
              />
            </svg>
            Add New Article
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
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="n in news.data" :key="n" class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ n.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ n.title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ moment(n.Date).format("MMMM Do YYYY, h:mm a") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="{
                          'bg-blue-100 text-blue-800': n.type === 1,
                          'bg-green-100 text-green-800': n.type === 2,
                          'bg-yellow-100 text-yellow-800': n.type === 3,
                          'bg-purple-100 text-purple-800': n.type === 4,
                          'bg-gray-100 text-gray-800': n.type === 5,
                          'bg-red-100 text-red-800': n.type === 6,
                        }">
                    {{ newsType[n.type - 1] }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="{
                          'bg-green-100 text-green-800': n.display == 1,
                          'bg-red-100 text-red-800': n.display != 1
                        }">
                    <span class="relative flex h-2 w-2 mr-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                            :class="{
                              'bg-green-400': n.display == 1,
                              'bg-red-400': n.display != 1
                            }">
                      </span>
                      <span class="relative inline-flex rounded-full h-2 w-2"
                            :class="{
                              'bg-green-500': n.display == 1,
                              'bg-red-500': n.display != 1
                            }">
                      </span>
                    </span>
                    {{ n.display == 1 ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex space-x-2 justify-center">
                    <Link
                      :href="`/administrator-panel/edit-news/${n.id}`"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                    >
                      <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                      Edit
                    </Link>
                    <button
                      @click="deleteNews(n.id)"
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

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ news.from || 0 }}</span>
                to
                <span class="font-medium">{{ news.to || 0 }}</span>
                of
                <span class="font-medium">{{ news.total || 0 }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <template v-for="(link, index) in news.links" :key="index">
                  <div
                    v-if="link.url === null"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                    v-html="link.label"
                  />
                  <Link
                    v-else
                    :href="link.url"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                    :class="{ 'bg-indigo-50 text-indigo-600': link.active }"
                    v-html="link.label"
                  />
                </template>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>
<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
