<script setup>
import { ref, computed, watch } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import moment from "moment";
import debounce from "lodash/debounce";

const props = defineProps({
  redeemHistories: Object,
  todayCount: Number,
  filters: Object,
});

// Initialize search and filter with values from URL
const searchQuery = ref(props.filters.search);
const selectedDateRange = ref(props.filters.date_range);

// Debounced search function to prevent too many requests
const performSearch = debounce(() => {
  router.get(
    "/administrator-panel/redeem-history",
    {
      search: searchQuery.value,
      date_range: selectedDateRange.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
}, 500);

// Watch for changes to search query and date range
watch(searchQuery, performSearch);
watch(selectedDateRange, performSearch);

// Statistics
const statistics = computed(() => {
  const total = props.redeemHistories.total;
  const today = props.todayCount || 0;

  return { total, today };
});

// Format timestamp for better readability
const formatTimestamp = (timestamp) => {
  const date = moment(timestamp);
  return {
    date: date.format("MMM DD, YYYY"),
    time: date.format("hh:mm A"),
    relative: date.fromNow(),
  };
};
</script>

<template>
  <Layout>
    <div class="space-y-6">
      <!-- Header Section -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Redemption Logs</h1>
          <p class="text-sm text-gray-500 mt-1">
            Complete history of redeemed promotion codes
          </p>
        </div>
      </div>

      <!-- Stats & Filter Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all"
        >
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div>
                <p
                  class="text-sm font-medium text-gray-500 uppercase tracking-wider"
                >
                  Total Redemptions
                </p>
                <div class="mt-2 flex items-baseline">
                  <p class="text-3xl font-bold text-gray-900">
                    {{ statistics.total }}
                  </p>
                  <span class="ml-2 text-sm font-medium text-gray-500"
                    >all time</span
                  >
                </div>
              </div>
              <div class="p-3 rounded-full bg-blue-100">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-7 w-7 text-blue-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="bg-blue-50 px-6 py-2">
            <div class="flex items-center text-xs font-medium text-blue-600">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              All redemptions recorded in the system
            </div>
          </div>
        </div>

        <div
          class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all"
        >
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div>
                <p
                  class="text-sm font-medium text-gray-500 uppercase tracking-wider"
                >
                  Today's Redemptions
                </p>
                <div class="mt-2 flex items-baseline">
                  <p class="text-3xl font-bold text-gray-900">
                    {{ statistics.today }}
                  </p>
                  <span
                    class="ml-2 text-sm font-medium text-emerald-500"
                    v-if="statistics.today > 0"
                    >active</span
                  >
                  <span class="ml-2 text-sm font-medium text-gray-400" v-else
                    >no activity</span
                  >
                </div>
              </div>
              <div class="p-3 rounded-full bg-emerald-100">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-7 w-7 text-emerald-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
          </div>
          <div class="bg-emerald-50 px-6 py-2">
            <div class="flex items-center text-xs font-medium text-emerald-600">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              Current day's activity
            </div>
          </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <div class="space-y-4">
            <div class="relative">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-gray-400 absolute left-3 top-3"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by code, account or character..."
                class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1"
                >Date Range</label
              >
              <select
                v-model="selectedDateRange"
                class="w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2 px-3"
              >
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="week">Last 7 Days</option>
                <option value="month">Last 30 Days</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Table Section -->
      <div
        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50">
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Code
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Redeemed Items
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Account / Character
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Date & Time
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="code in redeemHistories.data"
                :key="code.id"
                class="hover:bg-gray-50 transition-colors duration-200"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-blue-600">
                    {{ code.code }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="space-y-1">
                    <div
                      v-if="code?.redeem_code?.details?.length"
                      class="flex flex-wrap gap-1"
                    >
                      <span
                        v-for="(detail, idx) in code.redeem_code.details"
                        :key="idx"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                      >
                        {{ detail.item_name }} × {{ detail.item_quantity }}
                      </span>
                    </div>
                    <div v-else class="text-sm text-gray-500">No items</div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900">{{
                      code.account?.account
                    }}</span>
                    <span class="text-xs text-gray-500"
                      >#{{ code.character_id }} -
                      {{ code.character?.m_szName }}</span
                    >
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900">{{
                      formatTimestamp(code.created_at).date
                    }}</span>
                    <span class="text-xs text-gray-500">{{
                      formatTimestamp(code.created_at).time
                    }}</span>
                    <span class="text-xs text-gray-400 italic">{{
                      formatTimestamp(code.created_at).relative
                    }}</span>
                  </div>
                </td>
              </tr>

              <tr v-if="redeemHistories.data.length === 0">
                <td
                  colspan="4"
                  class="px-6 py-10 text-center text-sm text-gray-500"
                >
                  <div
                    class="flex flex-col items-center justify-center space-y-3"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-10 w-10 text-gray-300"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                      />
                    </svg>
                    <p>No redemption logs found matching your criteria</p>
                    <button
                      v-if="searchQuery || selectedDateRange !== 'all'"
                      @click="
                        searchQuery = '';
                        selectedDateRange = 'all';
                      "
                      class="text-blue-600 hover:text-blue-800 font-medium text-sm"
                    >
                      Clear filters
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div
          v-if="redeemHistories.links"
          class="bg-white px-6 py-4 border-t border-gray-200"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ redeemHistories.from }}</span> to
                <span class="font-medium">{{ redeemHistories.to }}</span> of
                <span class="font-medium">{{ redeemHistories.total }}</span>
                entries
              </p>
            </div>
            <div>
              <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
              >
                <template v-for="(link, i) in redeemHistories.links" :key="i">
                  <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      link.active
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      i === 0 ? 'rounded-l-md' : '',
                      i === redeemHistories.links.length - 1
                        ? 'rounded-r-md'
                        : '',
                    ]"
                    v-html="link.label"
                  ></Link>
                  <span
                    v-else
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500',
                      i === 0 ? 'rounded-l-md' : '',
                      i === redeemHistories.links.length - 1
                        ? 'rounded-r-md'
                        : '',
                    ]"
                    v-html="link.label"
                  ></span>
                </template>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>