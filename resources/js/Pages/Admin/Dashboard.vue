<script setup>
import { defineComponent, ref, watch } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { Notyf } from "notyf";
import moment from "moment";
import alertify from "alertifyjs";

const props = defineProps([
  "gcashDonates",
  "gcashStatus",
  "gcashSearch",
  "paypalDonates",
  "paypalSearch",
  "totalDonateInGCash",
  "totalDonateInPaypal",
  "totalNoOfGCashDonates",
  "totalNoOfPaypalDonates",
  "totalNoOfAccounts",
  "totalNoOfCharacters",
]);
const searchQuery = ref(props.gcashSearch);
const filterStatus = ref(props.gcashStatus);
const paypalSearch = ref(props.paypalSearch);

defineComponent({
  Head,
});

const renderPage = (page, page_key = "gcash_page") => {
  const url = new URL(window.location.href);
  url.searchParams.set(page_key, page.label);
  router.visit(url.toString(), {
    preserveScroll: true,
  });
};

const search = () => {
  const url = new URL(window.location.href);
  url.searchParams.set("search", searchQuery.value);
  router.visit(url.toString(), {
    preserveScroll: true,
  });
};

const searchPaypal = () => {
  const url = new URL(window.location.href);
  url.searchParams.set("search_paypal", paypalSearch.value);
  router.visit(url.toString(), {
    preserveScroll: true,
  });
};

watch([filterStatus], () => {
  const url = new URL(window.location.href);
  url.searchParams.set("search", searchQuery.value);
  url.searchParams.set("status", filterStatus.value);
  router.visit(url.toString(), {
    preserveScroll: true,
  });
});

const sendCash = (idx) => {
  if (confirm("Are you sure you want to send cash to this account?")) {
    axios
      .post(`/administrator-panel/gcash-donates/send-cash`, {
        idx,
      })
      .then((response) => {
        new Notyf().success(response.data.message);
        router.reload();
      })
      .catch((error) => {
        new Notyf().error(error.response.data.message);
      });
  } else {
    new Notyf().error("Cancelled");
  }
};

const deleteCash = (idx) => {
  if (confirm("Are you sure you want to delete this donation?")) {
    axios
      .post(`/administrator-panel/gcash-donates/delete-cash`, {
        idx
      })
      .then((response) => {
        new Notyf().success(response.data.message);
        router.reload();
      })
      .catch((error) => {
        new Notyf().error(error.response.data.message);
      });
  } else {
    new Notyf().error("Cancelled");
  }
};
</script>
<template>
  <layout>
    <template #header>
      <div class="mb-4">
        <h1 class="text-2xl font-semibold text-gray-900">Administration Dashboard</h1>
        <p class="text-sm text-gray-600">
          Monitor and manage donations and user statistics
        </p>
      </div>
    </template>

    <!-- Stats Cards -->
    <div class="mb-5">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Server Stats Group -->
        <div class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
          <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900">Server Statistics</h3>
            <dl class="mt-5 grid grid-cols-2 gap-5">
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Accounts</dt>
                <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                  {{ totalNoOfAccounts }}
                </dd>
              </div>
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Total Characters
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                  {{ totalNoOfCharacters }}
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- GCash Stats Group -->
        <div class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
          <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900">GCash Donations</h3>
            <dl class="mt-5 grid grid-cols-2 gap-5">
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Count</dt>
                <dd class="mt-1 text-3xl font-semibold text-emerald-600">
                  {{ totalNoOfGCashDonates }}
                </dd>
              </div>
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                <dd class="mt-1 text-3xl font-semibold text-emerald-600">
                  {{ totalDonateInGCash }}
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- PayPal Stats Group -->
        <div class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
          <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900">PayPal Donations</h3>
            <dl class="mt-5 grid grid-cols-2 gap-5">
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Count</dt>
                <dd class="mt-1 text-3xl font-semibold text-blue-600">
                  {{ totalNoOfPaypalDonates }}
                </dd>
              </div>
              <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                <dd class="mt-1 text-3xl font-semibold text-blue-600">
                  {{ totalDonateInPaypal }}
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <!-- GCash Donations Section -->
    <div class="">
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">GCash Donations</h2>
            <div class="flex space-x-3">
              <input
                type="text"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Search by Reference Number"
                @keyup.enter="search"
                v-model="searchQuery"
              />
              <select
                v-model="filterStatus"
                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">All Status</option>
                <option value="on_process">On Process</option>
                <option value="success">Success</option>
              </select>
            </div>
          </div>
        </div>

        <!-- GCash Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Reference #
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Transaction #
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Username
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Date
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Amount
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  DP Points
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Current Cash
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <!-- Keep existing tbody content -->
              <tr
                v-for="gcashDonate in gcashDonates.data"
                :key="gcashDonate"
                class="hover:bg-gray-50"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ gcashDonate.reference_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ gcashDonate.transaction_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ gcashDonate?.user?.email }} /
                  {{ gcashDonate?.account }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ moment(gcashDonate.created_at).format("MMMM Do YYYY, h:mm a") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseInt(gcashDonate?.donate_g_cash?.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseInt(gcashDonate?.donate_g_cash?.equivalent_donate_points) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseFloat(gcashDonate?.account_information?.cash).toFixed(2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center capitalize">
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset"
                    :class="{
                      'bg-yellow-50 text-yellow-700 ring-yellow-600/20':
                        gcashDonate.status === 'pending',
                      'bg-blue-50 text-blue-700 ring-blue-600/20':
                        gcashDonate.status === 'on_process',
                      'bg-green-50 text-green-700 ring-green-600/20':
                        gcashDonate.status === 'success',
                    }"
                  >
                    <span class="relative flex h-2 w-2 mr-2">
                      <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                        :class="{
                          'bg-yellow-400': gcashDonate.status === 'pending',
                          'bg-blue-400': gcashDonate.status === 'on_process',
                          'bg-green-400': gcashDonate.status === 'success',
                        }"
                      >
                      </span>
                      <span
                        class="relative inline-flex rounded-full h-2 w-2"
                        :class="{
                          'bg-yellow-500': gcashDonate.status === 'pending',
                          'bg-blue-500': gcashDonate.status === 'on_process',
                          'bg-green-500': gcashDonate.status === 'success',
                        }"
                      >
                      </span>
                    </span>
                    {{ gcashDonate.status.replace("_", " ") }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="relative inline-block text-left">
                    <div class="flex space-x-2 justify-center">
                      <button
                        v-if="gcashDonate.status === 'on_process'"
                        @click="sendCash(gcashDonate.id)"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Send Cash
                      </button>
                        <button
                        @click="deleteCash(gcashDonate.id)"
                        v-if="gcashDonate.status === 'on_process' || gcashDonate.status === 'pending'"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                        Delete
                        </button>
                      <Link
                        :href="`/administrator-panel/account/${gcashDonate?.account}`"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                      >
                        Profile
                      </Link>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-4 flex justify-end px-5 pb-5">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <template v-for="page in gcashDonates.links" :key="page">
              <a
                @click="renderPage(page, 'gcash_page')"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer',
                  page.label === currentPage
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                ]"
                v-html="page.label"
              ></a>
            </template>
          </nav>
        </div>
      </div>
    </div>

    <!-- PayPal Donations Section -->
    <div class="mt-5">
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">PayPal Donations</h2>
            <div class="flex space-x-3">
              <input
                type="text"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Search by Ref. #"
                @keyup.enter="searchPaypal"
                v-model="paypalSearch"
              />
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Reference #
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Transaction #
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Username
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Date
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Amount
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  DP Points
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Current Cash
                </th>
                <th
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="paypalDonates in paypalDonates.data"
                :key="paypalDonates"
                class="hover:bg-gray-50"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ paypalDonates.reference_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ paypalDonates.transaction_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ paypalDonates?.user?.email }} /
                  {{ paypalDonates?.account }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ moment(paypalDonates.created_at).format("MMMM Do YYYY, h:mm a") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseInt(paypalDonates?.donate_paypal?.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseInt(paypalDonates?.donate_paypal?.equivalent_donate_points) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                  {{ parseFloat(paypalDonates?.account_information?.cash).toFixed(2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <Link
                    :href="`/administrator-panel/account/${paypalDonates?.account}`"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Profile
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 flex justify-end px-5 pb-5">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <template v-for="page in paypalDonates.links" :key="page">
              <a
                @click="renderPage(page, 'paypal_page')"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer',
                  page.label === currentPage
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                ]"
                v-html="page.label"
              ></a>
            </template>
          </nav>
        </div>
      </div>
    </div>
  </layout>
</template>
<style scoped>
.cursor-pointer {
  cursor: pointer;
}

.hover-effect {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-effect:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.card {
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  border-radius: 12px;
}
.text-primary {
  color: #0d6efd !important;
}
.text-muted {
  color: #6c757d !important;
}
</style>
