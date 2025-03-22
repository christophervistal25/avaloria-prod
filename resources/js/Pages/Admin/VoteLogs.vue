<script setup>
import { defineProps, ref, computed } from 'vue'
import Layout from '@layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import moment from 'moment'

const props = defineProps({
  voteLogs: {
    type: Array,
    required: true
  }
})

const searchQuery = ref('')

// Enhanced filtering
const filteredLogs = computed(() => {
  return props.voteLogs.filter(log => 
    log.account.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    log.ip_address.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Statistics for the overview cards
const stats = computed(() => ({
  totalVotes: props.voteLogs.length,
  todayVotes: props.voteLogs.filter(log => 
    moment(log.voted_at).isSame(moment(), 'day')
  ).length,
  monthlyVotes: props.voteLogs.filter(log => 
    moment(log.voted_at).isSame(moment(), 'month')
  ).length
}))

const formatDate = (date) => moment(date).format('MMM D, YYYY h:mm A')
</script>

<template>
  <Layout>
        <h1 class="text-2xl font-semibold text-gray-900">Pingback Vote Logs</h1>
        <p class=" text-sm text-gray-600">Monitor and manage vote logs of the user</p>
    <div class="min-h-screen bg-gray-50/30 py-8">
      <div class="">
        <!-- Enhanced Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
          <!-- Enhanced Search Bar -->
          <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div class="relative flex-1 max-w-lg">
                <input
                  v-model="searchQuery"
                  type="text"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-gray-50 
                         placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent 
                         focus:bg-white transition-all duration-200 sm:text-sm"
                  placeholder="Search by account or IP address..."
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Enhanced Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
              <thead>
                <tr class="bg-gray-50">
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Account
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    IP Address
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Voted At
                  </th>
                  <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="vote in filteredLogs" :key="vote.id" 
                    class="hover:bg-gray-50/50 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 
                                  flex items-center justify-center shadow-sm">
                          <span class="text-sm font-semibold text-white">
                            {{ vote.account.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900">{{ vote.account }}</div>
                        <div class="text-xs text-gray-500">User ID: #{{ vote.id }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                      </svg>
                      <span class="text-sm text-gray-900">{{ vote.ip_address }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span class="text-sm text-gray-500">{{ formatDate(vote.voted_at) }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      <svg class="h-2 w-2 text-green-400 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3"/>
                      </svg>
                      Successful
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Enhanced Empty State -->
          <div v-if="filteredLogs.length === 0" 
               class="flex flex-col items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
            <div class="relative h-24 w-24 text-gray-400 mb-4">
              <svg class="absolute inset-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <svg class="absolute inset-0 opacity-50 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No vote logs found</h3>
            <p class="mt-1 text-sm text-gray-500 max-w-sm text-center">
              No records match your search criteria. Try adjusting your search or filters.
            </p>
            <button class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium 
                         text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 
                         focus:ring-offset-2 focus:ring-blue-500">
              Clear Filters
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>