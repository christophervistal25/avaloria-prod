<script setup>
import { defineComponent, ref, computed } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
  users: {
    type: Object,
    required: true,
  },
  currentPage: {
    type: [String, Number],
    required: true,
  },
  search: {
    type: String,
    default: "",
  },
  cashFilter: {
    type: Boolean,
  },
});

const searchAccount = ref(props.search);
const showFilters = ref(false);

// Stats computation
const stats = computed(() => ({
  totalAccounts: props.users.total,
  activeAccounts: props.users.data.filter(
    (user) => user.account_detail?.m_chLoginAuthority === "A"
  ).length,
  totalCharacters: props.users.data.reduce(
    (acc, user) => acc + parseInt(user.no_of_characters_count),
    0
  ),
  totalCash: props.users.data.reduce(
    (acc, user) => acc + parseInt(user.cash || 0),
    0
  ),
}));

const renderPage = (page) => {
  page.url = page.url.replace("http", "https");
  router.visit(page.url, {
    preserveScroll: true,
    preserveState: true,
  });
};

const accountFilter = () => {
  router.visit(
    `/administrator-panel/accounts?search=${searchAccount.value || ""}&page=${
      props.currentPage
    }`,
    { preserveScroll: true }
  );
};

const setCashFilter = (filter) => {
  router.visit(
    `/administrator-panel/accounts?search=${searchAccount.value || ""}&cash=${
      filter === "with" ? 1 : 0
    }&page=${props.currentPage}`,
    { preserveScroll: true }
  );
};

const formatDate = (date) => moment(date).format("MMM D, YYYY h:mm A");

const getAccountStatus = (authority) => {
  const statuses = {
    A: { text: "Active", class: "bg-green-100 text-green-800" },
    B: { text: "Blocked", class: "bg-red-100 text-red-800" },
    T: { text: "Temporary", class: "bg-yellow-100 text-yellow-800" },
  };
  return (
    statuses[authority] || {
      text: `${authority}`,
      class: "bg-gray-100 text-gray-800",
    }
  );
};
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-gray-50/30 py-8">
      <div class="">
        <!-- Enhanced Header with Stats -->
        <div class="mb-8">
          <div
            class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg"
          >
            <div class="absolute inset-0"></div>
            <div class="relative p-8">
              <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                  <h1
                    class="text-2xl font-bold text-white sm:text-3xl flex items-center"
                  >
                    <svg
                      class="h-8 w-8 text-blue-100 mr-3"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                      />
                    </svg>
                    Accounts Management
                  </h1>
                  <p class="mt-2 text-blue-100">
                    Manage and monitor all user accounts across the platform
                  </p>
                </div>
              </div>

              <!-- Quick Stats -->
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-blue-500/20 rounded-lg">
                      <svg
                        class="h-6 w-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm text-blue-100">Total Accounts</div>
                      <div class="text-2xl font-bold text-white">
                        {{ stats.totalAccounts }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-green-500/20 rounded-lg">
                      <svg
                        class="h-6 w-6 text-white"
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
                    <div class="ml-4">
                      <div class="text-sm text-blue-100">Active Accounts</div>
                      <div class="text-2xl font-bold text-white">
                        {{ stats.activeAccounts }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-purple-500/20 rounded-lg">
                      <svg
                        class="h-6 w-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm text-blue-100">Total Characters</div>
                      <div class="text-2xl font-bold text-white">
                        {{ stats.totalCharacters }}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-yellow-500/20 rounded-lg">
                      <svg
                        class="h-6 w-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm text-blue-100">Total Cash</div>
                      <div class="text-2xl font-bold text-white">
                        {{ stats.totalCash }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
          <!-- Enhanced Search and Filters -->
          <div class="p-5 border-b border-gray-100">
            <div
              class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
              <div class="relative flex-1 max-w-lg">
                <input
                  v-model="searchAccount"
                  type="text"
                  @keyup.enter="accountFilter"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition-all duration-200 sm:text-sm"
                  placeholder="Search by account or email..."
                />
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <svg
                    class="h-5 w-5 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                  </svg>
                </div>
              </div>

              <div class="flex space-x-2">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                  <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium  border rounded-l-lg border-r-0"
                    :class="[
                      !cashFilter
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50',
                    ]"
                    @click="setCashFilter('without')"
                  >
                    All
                  </button>
                  <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium  border rounded-r-lg"
                    :class="[
                      cashFilter
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50',
                    ]"
                    @click="setCashFilter('with')"
                  >
                    With Cash
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Enhanced Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Account
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Characters
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Created
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Status
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Email
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Vote Points
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Cash
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="user in users.data"
                  :key="user.account"
                  class="hover:bg-gray-50/50 transition-all duration-150"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div
                          class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm"
                        >
                          <span class="text-sm font-semibold text-white">
                            {{ user.account.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900">
                          {{ user.account }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span
                      class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                    >
                      {{ user.no_of_characters_count }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      {{ formatDate(user?.account_detail?.regdate) }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span
                      :class="[
                        'px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full',
                        getAccountStatus(
                          user?.account_detail?.m_chLoginAuthority
                        ).class,
                      ]"
                    >
                      {{
                        getAccountStatus(
                          user?.account_detail?.m_chLoginAuthority
                        ).text
                      }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                      {{ user?.account_detail?.email }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ user.votepoint }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ user.cash }}
                    </div>
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2"
                  >
                    <Link
                      :href="`/administrator-panel/account/${user.account}`"
                      class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                    >
                      Profile
                    </Link>
                    <Link
                      v-if="user.no_of_characters_count > 0"
                      :href="`/administrator-panel/account/${user.account}/characters`"
                      class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all"
                    >
                      Characters
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Enhanced Pagination -->
          <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ users.from }}</span>
                  to
                  <span class="font-medium">{{ users.to }}</span>
                  of
                  <span class="font-medium">{{ users.total }}</span>
                  results
                </p>
              </div>
              <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
              >
                <template v-for="page in users.links" :key="page">
                  <a
                    @click="renderPage(page)"
                    :class="[
                      page.label === currentPage
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer',
                    ]"
                    v-html="page.label"
                  ></a>
                </template>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.bg-grid-white\/10 {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(255 255 255 / 0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}
</style>