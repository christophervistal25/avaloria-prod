<script setup>
import { ref } from 'vue'
import Layout from '@layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import moment from 'moment'

const props = defineProps({
  wikipedias: Object,
})

const searchQuery = ref('')

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}
</script>
<template>
  <Layout>
    <div class="min-h-screen">
      <div class="">
        <div class="mb-8">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Wikipedia Entries </h1>
            <p class="text-sm text-gray-500 mt-1">
              Manage and organize your game's wiki content
            </p>
          </div>
          <div class="flex-shrink-0">
            <Link href="/administrator-panel/wikipedia/create"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Create New Entry
            </Link>
          </div>
        </div>
      </div>

        <div class="mb-8 flex flex-row justify-between items-center space-x-4">
          <!-- Search Section -->
          <div class="flex-1">
            <div class="relative">
              <input 
          type="text" 
          v-model="searchQuery"
          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 h-12 sm:text-sm border-gray-300 rounded-lg transition-all duration-200 hover:shadow-md"
          placeholder="Search entries..."
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
              </div>
              <div v-if="searchQuery" 
          class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
          @click="searchQuery = ''">
          <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
              </div>
            </div>
          </div>

          <!-- Create Button -->
          
        </div>

        <!-- Grid Layout for Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
          <div v-for="wiki in wikipedias.data" :key="wiki.id"
               class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-200">
            <!-- Card Header with Image -->
            <div class="relative h-48 bg-gray-200">
              <img v-if="wiki.image" :src="`/storage/${wiki.image}`"
                   class="w-full h-full object-cover" :alt="wiki.title"/>
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 text-xs font-medium rounded-full shadow-sm"
                      :class="{
                        'bg-green-100 text-green-800': wiki.is_published,
                        'bg-gray-100 text-gray-800': !wiki.is_published
                      }">
                  {{ wiki.is_published ? 'Published' : 'Draft' }}
                </span>
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ wiki.title }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ wiki.items?.length || 0 }} items
                </span>
              </div>
              
              <p class="text-sm text-gray-500 mb-4">
                {{ truncate(wiki.description.replace(/<[^>]*>/g, ''), 150) }}
              </p>

              <div class="flex items-center justify-between text-sm">
                <div class="text-gray-500">
                  <span class="flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ moment(wiki.created_at).format('MMM D, YYYY') }}
                  </span>
                </div>
                <div class="text-gray-500">By {{ wiki.user?.name || 'Unknown' }}</div>
              </div>

              <!-- Card Actions -->
              <div class="mt-6 flex justify-end space-x-3">
                <Link :href="`/administrator-panel/wikipedia/${wiki.id}/edit`"
                      class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100">
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Edit
                </Link>
                <button @click="$inertia.delete(`/administrator-panel/wikipedia/${wiki.id}`)"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100"
                        onclick="return confirm('Are you sure you want to delete this entry?')">
                  <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                  Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Empty State Card -->
          <div v-if="wikipedias.data.length === 0" class="col-span-full">
            <div class="text-center bg-white rounded-lg shadow-sm px-6 py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No entries</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by creating a new Wikipedia entry.</p>
              <div class="mt-6">
                <Link href="/administrator-panel/wikipedia/create"
                      class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                  <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                  Create New Entry
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Enhanced Pagination -->
        <div class="bg-white px-4 py-3 rounded-lg shadow-sm">
          <div class="flex items-center justify-between">
           
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="wikipedias.prev_page_url"
                :href="wikipedias.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="wikipedias.next_page_url"
                :href="wikipedias.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ wikipedias.from }}</span>
                  to
                  <span class="font-medium">{{ wikipedias.to }}</span>
                  of
                  <span class="font-medium">{{ wikipedias.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="(link, i) in wikipedias.links"
                    :key="i"
                    :href="link.url"
                    v-html="link.label"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    :class="{
                      'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                      'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                      'rounded-l-md': i === 0,
                      'rounded-r-md': i === wikipedias.links.length - 1
                    }"
                  />
                </nav>
              </div>
            </div>
          
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>