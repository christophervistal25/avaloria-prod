<script setup>
import { ref } from "vue";
import Layout from "@/Pages/Layouts/Layout.vue";
import { Head, usePage, router, Link } from "@inertiajs/vue3";
import Vote from "@/components/Vote.vue";

import moment from "moment";
import axios from "axios";
import { Notyf } from "notyf";

const props = defineProps({
  email : String,
  gCashPurchases: {
    type: Array,
    default: () => [],
  },
  characters: {
    type: Array,
    default: () => [],
  },
  paypalPurchases: {
    type: Array,
    default: () => [],
  },
  userTotalDonateInGcash: {
    type: Number,
    default: 0,
  },
  userTotalDonateInPaypal: {
    type: Number,
    default: 0,
  },
  accounts: {
    type: Array,
    default: () => [],
  },
  voteLogs: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();

const isGCashVisible = ref(false);
const isPayPalVisible = ref(false);
const isVoteLogsVisible = ref(false);
// New modal state for password change
const isPasswordModalOpen = ref(false);
const isModalSubmitting = ref(false);
const passwordErrors = ref({});

// Change password modal form
const passwordModalForm = ref({
  email : props.email,
  selectedAccount: "",
  new_password: "",
  new_password_confirmation: ""
});



// Reactive forms and data
const passwordForm = ref({
  current_password: "",
  new_password: "",
  confirmed_password: "",
});

const accountForm = ref({
  username: "",
  password: "",
  confirm_password: "",
});

const redeemForm = ref({
  selectedAccount: "",
  code: "",
});




// Form submission handlers
const resetPassword = () => {
  axios
    .post("/user-account/change-password", passwordForm.value)
    .then((response) => {
      if (response.status === 200) {
        new Notyf().success("Password updated successfully");
        passwordForm.value = {
          current_password: "",
          new_password: "",
          confirm_password: "",
        };
      }
    })
    .catch((error) => {
      // display validation errors
      console.log(error.response.status);
      if (error.response.status === 422) {
        const errors = error.response.data.errors;
        for (const key in errors) {
          new Notyf().error(errors[key][0]);
        }
      }
    });
};

const createAccount = () => {
  axios
    .post("/user-account/in-game-account", accountForm.value)
    .then((response) => {
      if (response.status === 201) {
        new Notyf().success("Account created successfully");
        accountForm.value = {
          username: "",
          password: "",
          confirm_password: "",
        };

        router.visit(location.href, {
          preserveState: true,
          preserveScroll: true,
          only: ["accounts"],
        });
      }
    })
    .catch((error) => {
      console.log(error);
      if (error.status === 422) {
        const errors = error.response.data.errors;
        for (const key in errors) {
          new Notyf().error(errors[key][0]);
        }
      }
    });
};

const redeemCode = () => {
  axios
    .post("/user-account/redeem-code", redeemForm.value)
    .then((response) => {
      if (response.status === 200) {
        new Notyf().success("Code redeemed successfully");
        redeemForm.value = {
          selectedAccount: "",
          code: "",
        };
      }
    })
    .catch((error) => {
      if (error.response.status === 422 || error.response.status === 400) {
        const [errors] = error.response.data;
        new Notyf({
          duration: 5000,
        }).error(errors.message);
      }
    });
};

const vote = () => {
};

// Add these methods for toggling visibility
const toggleGCash = () => {
  isGCashVisible.value = !isGCashVisible.value;
};

const togglePayPal = () => {
  isPayPalVisible.value = !isPayPalVisible.value;
};

// Add this with your other toggle methods
const toggleVoteLogs = () => {
  isVoteLogsVisible.value = !isVoteLogsVisible.value;
};

const submitPasswordModal = () => {
  isModalSubmitting.value = true;
  axios.post(`/user-account/new-password`, passwordModalForm.value)
    .then((response) => {
      if (response.status === 200) {
        new Notyf().success("Password updated successfully");
        isPasswordModalOpen.value = false;
        passwordModalForm.value = {
          current_password: "",
          new_password: "",
          new_password_confirmation: ""
        };
      }
    })
    .catch((error) => {
      if (error.response.status === 422) {
        passwordErrors.value = error.response.data.errors;
      }
    })
    .finally(() => {
      isModalSubmitting.value = false;
    });
};
</script>

<template>
  <Layout>
    <div class="dashboard-wrapper relative min-h-screen">
      <div
        class="absolute inset-0 bg-gradient-to-b from-[#0A0A15]/90 to-transparent h-64 pointer-events-none"
      ></div>

      <div class="mx-auto px-12 py-10 relative">
        <!-- Dashboard Header with Animation -->
        <div
          class="flex items-center justify-between mb-8 border-b border-gray-800/70 pb-6 fade-in-down"
        >
          <div>
            <h1
              class="text-3xl font-bold text-white mb-2 tracking-tight glow-text"
            >
              Account Dashboard
            </h1>
            <p class="text-sm text-gray-300">
              Manage your game accounts and security settings
            </p>
          </div>
        </div>

        <!-- Dashboard Statistics -->
        <div
          class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8 fade-in-up"
        >
          <div
            class="bg-gradient-to-br from-yellow-900/30 to-yellow-700/20 rounded-xl p-5 border border-yellow-700/30 shadow-lg hover:shadow-yellow-900/10 transition-all duration-300"
          >
            <div class="flex items-center justify-between">
              <span class="text-gray-300 text-sm">GCash Donations</span>
              <div class="p-2 bg-yellow-500/20 rounded-lg">
                <svg
                  class="w-5 h-5 text-yellow-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-4">
              <p class="text-2xl font-bold text-yellow-400">
                ₱ {{ parseFloat(userTotalDonateInGcash).toFixed(2) }}
              </p>
              <p class="text-gray-400 text-xs mt-1">
                {{ gCashPurchases.length }} transactions
              </p>
            </div>
          </div>

          <div
            class="bg-gradient-to-br from-blue-900/30 to-blue-700/20 rounded-xl p-5 border border-blue-700/30 shadow-lg hover:shadow-blue-900/10 transition-all duration-300"
          >
            <div class="flex items-center justify-between">
              <span class="text-gray-300 text-sm">PayPal Donations</span>
              <div class="p-2 bg-blue-500/20 rounded-lg">
                <svg
                  class="w-5 h-5 text-blue-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-4">
              <p class="text-2xl font-bold text-blue-400">
                € {{ parseFloat(userTotalDonateInPaypal).toFixed(2) }}
              </p>
              <p class="text-gray-400 text-xs mt-1">
                {{ paypalPurchases.length }} transactions
              </p>
            </div>
          </div>

          <div
            class="bg-gradient-to-br from-purple-900/30 to-purple-700/20 rounded-xl p-5 border border-purple-700/30 shadow-lg hover:shadow-purple-900/10 transition-all duration-300"
          >
            <div class="flex items-center justify-between">
              <span class="text-gray-300 text-sm">Vote Activity</span>
              <div class="p-2 bg-purple-500/20 rounded-lg">
                <svg
                  class="w-5 h-5 text-purple-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-4">
              <p class="text-2xl font-bold text-purple-400">
                {{ voteLogs?.length || 0 }}
              </p>
              <p class="text-gray-400 text-xs mt-1">Total votes</p>
            </div>
          </div>

          <div
            class="bg-gradient-to-br from-green-900/30 to-green-700/20 rounded-xl p-5 border border-green-700/30 shadow-lg hover:shadow-green-900/10 transition-all duration-300"
          >
            <div class="flex items-center justify-between">
              <span class="text-gray-300 text-sm">Game Accounts</span>
              <div class="p-2 bg-green-500/20 rounded-lg">
                <svg
                  class="w-5 h-5 text-green-500"
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
              </div>
            </div>
            <div class="mt-4">
              <p class="text-2xl font-bold text-green-400">
                {{ accounts.length }}
              </p>
              <p class="text-gray-400 text-xs mt-1">Registered accounts</p>
            </div>
          </div>
        </div>

        <!-- Main Grid Layout with improved spacing -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-8">
            <!-- GCash Section with improved visuals -->
            <section
              class="bg-gray-900/60 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 overflow-hidden hover:shadow-yellow-900/5 transition-all duration-300"
            >
              <div
                class="p-6 border-b border-gray-800 flex items-center justify-between cursor-pointer hover:bg-gray-800/30 transition-colors duration-200"
                @click="toggleGCash"
              >
                <h2 class="text-xl font-semibold text-white flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-yellow-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                  </svg>
                  GCash Donations
                  <span
                    class="ml-3 text-xs bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full"
                  >
                    {{ gCashPurchases.length }} transactions
                  </span>
                </h2>
                <div class="flex items-center">
                  <span class="text-gray-400 mr-2 text-sm font-medium"
                    >Total:</span
                  >
                  <span class="text-yellow-400 font-bold text-lg"
                    >₱ {{ parseFloat(userTotalDonateInGcash).toFixed(2) }}</span
                  >
                  <svg
                    class="w-5 h-5 ml-3 text-gray-400 transition-transform duration-300"
                    :class="{ 'rotate-180': isGCashVisible }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    />
                  </svg>
                </div>
              </div>

              <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 -translate-y-4"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-4"
              >
                <div v-show="isGCashVisible" class="overflow-hidden">
                  <div class="p-6 overflow-x-auto">
                    <div
                      v-if="!gCashPurchases || gCashPurchases.length === 0"
                      class="text-center py-10"
                    >
                      <div
                        class="bg-yellow-900/10 h-20 w-20 flex items-center justify-center rounded-full mx-auto mb-4"
                      >
                        <svg
                          class="w-10 h-10 text-yellow-700"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M9 17h6m-3-3v-3m-3 9h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"
                          />
                        </svg>
                      </div>
                      <p class="mt-4 text-gray-300 font-medium">
                        No transactions yet
                      </p>
                      <p class="mt-2 text-sm text-gray-500">
                        Your GCash donations will appear here
                      </p>
                    </div>

                    <table
                      v-else
                      class="min-w-full divide-y divide-gray-800 border-collapse"
                    >
                      <thead>
                        <tr>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Transaction ID
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Reference #
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Account
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Date
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Amount
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Donate Points
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-gray-900/30 divide-y divide-gray-800">
                        <tr
                          v-for="donate in gCashPurchases"
                          :key="donate.id"
                          class="hover:bg-gray-800/50 transition-colors"
                        >
                          <td
                            class="px-6 py-4 text-sm font-medium text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.transaction_id || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.reference_number || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.account || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{
                              donate.created_at
                                ? moment(donate.created_at).format(
                                    "MMM DD, YYYY • HH:mm"
                                  )
                                : "N/A"
                            }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 text-right whitespace-nowrap"
                          >
                            ₱
                            {{
                              parseFloat(
                                donate.donate_g_cash?.amount || 0
                              ).toFixed(2)
                            }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-yellow-400 font-medium text-right whitespace-nowrap"
                          >
                            {{
                              parseFloat(
                                donate.donate_g_cash
                                  ?.equivalent_donate_points || 0
                              ).toFixed(0)
                            }}
                          </td>
                          <td class="px-6 py-4 text-right whitespace-nowrap">
                            <span
                              :class="{
                                'px-3 py-1 text-xs rounded-full min-w-[100px] inline-block text-center font-medium': true,
                                'bg-green-500/20 text-green-400':
                                  donate.status === 'success',
                                'bg-yellow-500/20 text-yellow-400':
                                  donate.status === 'on_process',
                                'bg-blue-500/20 text-blue-400':
                                  donate.status === 'pending',
                                'bg-red-500/20 text-red-400':
                                  donate.status === 'cancelled',
                              }"
                            >
                              <span
                                v-if="donate.status === 'success'"
                                class="inline-block w-2 h-2 rounded-full bg-green-400 mr-1.5"
                              ></span>
                              <span
                                v-else-if="donate.status === 'on_process'"
                                class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-1.5"
                              ></span>
                              <span
                                v-else-if="donate.status === 'pending'"
                                class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-1.5"
                              ></span>
                              <span
                                v-else
                                class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1.5"
                              ></span>
                              {{
                                donate.status
                                  ?.replace(/_/g, " ")
                                  .toUpperCase() || "N/A"
                              }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </transition>
            </section>

            <!-- PayPal section with improved visuals -->
            <section
              class="bg-gray-900/60 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 overflow-hidden hover:shadow-blue-900/5 transition-all duration-300"
            >
              <div
                class="p-6 border-b border-gray-800 flex items-center justify-between cursor-pointer hover:bg-gray-800/30 transition-colors duration-200"
                @click="togglePayPal"
              >
                <h2 class="text-xl font-semibold text-white flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-blue-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                    />
                  </svg>
                  PayPal Donations
                  <span
                    class="ml-3 text-xs bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full"
                  >
                </span>
                {{ paypalPurchases.length }} transactions
                </h2>
                <div class="flex items-center">
                  <span class="text-gray-400 mr-2 text-sm font-medium"
                    >Total:</span
                  >
                  <span class="text-blue-400 font-bold text-lg"
                    >€
                    {{ parseFloat(userTotalDonateInPaypal).toFixed(2) }}</span
                  >
                  <svg
                    class="w-5 h-5 ml-3 text-gray-400 transition-transform duration-300"
                    :class="{ 'rotate-180': isPayPalVisible }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    />
                  </svg>
                </div>
              </div>

              <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 -translate-y-4"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-4"
              >
                <div v-show="isPayPalVisible" class="overflow-hidden">
                  <div class="p-6 overflow-x-auto">
                    <div
                      v-if="!paypalPurchases || paypalPurchases.length === 0"
                      class="text-center py-10"
                    >
                      <div
                        class="bg-blue-900/10 h-20 w-20 flex items-center justify-center rounded-full mx-auto mb-4"
                      >
                        <svg
                          class="w-10 h-10 text-blue-700"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M9 17h6m-3-3v-3m-3 9h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"
                          />
                        </svg>
                      </div>
                      <p class="mt-4 text-gray-300 font-medium">
                        No transactions yet
                      </p>
                      <p class="mt-2 text-sm text-gray-500">
                        Your PayPal donations will appear here
                      </p>
                    </div>

                    <table
                      v-else
                      class="min-w-full divide-y divide-gray-800 border-collapse"
                    >
                      <thead>
                        <tr>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Transaction ID
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Reference #
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Account
                          </th>
                          <th
                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Date
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Amount
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Donate Points
                          </th>
                          <th
                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-400 uppercase tracking-wider"
                          >
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-gray-900/30 divide-y divide-gray-800">
                        <tr
                          v-for="donate in paypalPurchases"
                          :key="donate.id"
                          class="hover:bg-gray-800/50 transition-colors"
                        >
                          <td
                            class="px-6 py-4 text-sm font-medium text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.transaction_id || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.reference_number || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{ donate.account || "N/A" }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap"
                          >
                            {{
                              donate.created_at
                                ? moment(donate.created_at).format(
                                    "MMM DD, YYYY • HH:mm"
                                  )
                                : "N/A"
                            }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-gray-300 text-right whitespace-nowrap"
                          >
                            €
                            {{
                              parseFloat(
                                donate.donate_paypal?.amount || 0
                              ).toFixed(2)
                            }}
                          </td>
                          <td
                            class="px-6 py-4 text-sm text-blue-400 font-medium text-right whitespace-nowrap"
                          >
                            {{
                              parseFloat(
                                donate.donate_paypal
                                  ?.equivalent_donate_points || 0
                              ).toFixed(0)
                            }}
                          </td>
                          <td class="px-6 py-4 text-right whitespace-nowrap">
                            <span
                              :class="{
                                'px-3 py-1 text-xs rounded-full min-w-[100px] inline-block text-center font-medium': true,
                                'bg-green-500/20 text-green-400':
                                  donate.status === 'success',
                                'bg-yellow-500/20 text-yellow-400':
                                  donate.status === 'on_process',
                                'bg-blue-500/20 text-blue-400':
                                  donate.status === 'pending',
                                'bg-red-500/20 text-red-400':
                                  donate.status === 'cancelled',
                              }"
                            >
                              <span
                                v-if="donate.status === 'success'"
                                class="inline-block w-2 h-2 rounded-full bg-green-400 mr-1.5"
                              ></span>
                              <span
                                v-else-if="donate.status === 'on_process'"
                                class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-1.5"
                              ></span>
                              <span
                                v-else-if="donate.status === 'pending'"
                                class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-1.5"
                              ></span>
                              <span
                                v-else
                                class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1.5"
                              ></span>
                              {{
                                donate.status
                                  ?.replace(/_/g, " ")
                                  .toUpperCase() || "N/A"
                              }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </transition>
            </section>

            <section
              class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50"
            >
              <div
                class="p-6 border-b border-gray-800 flex items-center justify-between cursor-pointer"
                @click="toggleVoteLogs"
              >
                <h2 class="text-xl font-semibold text-white flex items-center">
                  <svg
                    class="w-5 h-5 mr-2 text-purple-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                  </svg>
                  Vote Logs
                </h2>
                <span class="text-purple-500 font-bold">{{
                  voteLogs?.length
                }}</span>
              </div>
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 -translate-y-2"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-2"
              >
                <div v-show="isVoteLogsVisible" class="overflow-hidden">
                  <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                      <thead>
                        <tr>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase"
                          >
                            Account
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase"
                          >
                            IP Address
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase"
                          >
                            Voted At
                          </th>
                          <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase"
                          >
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-800">
                        <tr
                          v-for="log in voteLogs"
                          :key="log.id"
                          class="hover:bg-gray-800/50"
                        >
                          <td class="px-6 py-4 text-sm text-gray-300">
                            {{ log.account }}
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-300">
                            {{ log.ip_address }}
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-300">
                            {{
                              log.voted_at
                                ? moment(log.voted_at).format(
                                    "MMMM DD, YYYY HH:mm:ss"
                                  )
                                : "N/A"
                            }}
                          </td>
                          <td class="px-6 py-4 text-right">
                            <span
                              class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400"
                            >
                              COMPLETED
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </transition>
            </section>

            <!-- In-Game Accounts Section -->
            <section
              class="bg-gray-900/60 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 overflow-hidden hover:shadow-blue-900/5 transition-all duration-300"
            >
              <div
                class="p-6 border-b border-gray-800 flex items-center justify-between"
              >
                <h2 class="text-xl font-semibold text-white flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-blue-500"
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
                  Game Accounts
                  <span
                    class="ml-3 text-xs bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full"
                  >
                    {{ accounts.length }} accounts
                  </span>
                </h2>
              </div>

              <div class="p-6 overflow-x-auto">
                <div
                  v-if="!accounts || accounts.length === 0"
                  class="text-center py-10"
                >
                  <div
                    class="bg-blue-900/10 h-20 w-20 flex items-center justify-center rounded-full mx-auto mb-4"
                  >
                    <svg
                      class="w-10 h-10 text-blue-700"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                      />
                    </svg>
                  </div>
                  <p class="mt-4 text-gray-300 font-medium">
                    No game accounts yet
                  </p>
                  <p class="mt-2 text-sm text-gray-500">
                    Create your first game account below
                  </p>
                </div>

                <table
                  v-else
                  class="min-w-full divide-y divide-gray-800 border-collapse"
                >
                  <thead>
                    <tr>
                      <th
                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                      >
                        Account
                      </th>
                      <th
                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"
                      >
                        Characters
                      </th>
                      <th
                        class="px-6 py-3.5 text-center text-xs font-medium text-gray-400 uppercase tracking-wider"
                      >
                        <div class="flex items-center justify-center">
                          <svg
                            class="w-4 h-4 mr-1.5 text-yellow-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                              clip-rule="evenodd"
                            />
                          </svg>
                          Cash Points
                        </div>
                      </th>
                      <th
                        class="px-6 py-3.5 text-center text-xs font-medium text-gray-400 uppercase tracking-wider"
                      >
                        <div class="flex items-center justify-center">
                          <svg
                            class="w-4 h-4 mr-1.5 text-purple-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"
                            />
                          </svg>
                          Vote Points
                        </div>
                      </th>
                      <th
                        class="px-6 py-3.5 text-center text-xs font-medium text-gray-400 uppercase tracking-wider"
                      >
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-gray-900/30 divide-y divide-gray-800">
                    <tr
                      v-for="account in accounts"
                      :key="account.account"
                      class="hover:bg-gray-800/50 transition-colors duration-200"
                    >
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div
                            class="flex-shrink-0 h-10 w-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-bold shadow-inner"
                          >
                            {{ account.account.charAt(0).toUpperCase() }}
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-white">
                              {{ account.account }}
                            </div>
                            <div class="text-xs text-gray-500">
                              Member since {{ new Date().getFullYear() }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4">
                        <div
                          v-if="
                            !account?.account_information?.characters?.length
                          "
                          class="text-sm text-gray-500 italic"
                        >
                          No characters
                        </div>
                        <div v-else class="grid gap-2">
                          <div
                            v-for="char in account?.account_information
                              ?.characters"
                            :key="char.m_szName"
                            class="flex items-center py-1 px-2 rounded-md bg-gray-800/50 border border-gray-700/30"
                          >
                            <div
                              class="h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-2 text-xs text-white font-bold"
                            >
                              {{ char.m_nLevel }}
                            </div>
                            <span class="text-sm text-white font-medium">{{
                              char.m_szName
                            }}</span>
                            <span
                              class="ml-auto text-xs px-2 py-0.5 rounded-full bg-gray-700/50 text-gray-300"
                            >
                              {{ char.job || "Vagrant" }}
                            </span>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center">
                          <span
                            class="text-sm font-semibold px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-400"
                          >
                            {{ account?.account_information?.cash || 0 }}
                          </span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center">
                          <span
                            class="text-sm font-semibold px-3 py-1 rounded-full bg-purple-500/10 text-purple-400"
                          >
                            {{ account?.account_information?.votepoint || 0 }}
                          </span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <Link
                          :href="`/donates?account=${account.account}`"
                          class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors duration-200"
                        >
                          <svg
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                          </svg>
                          Donate
                        </Link>
                        <button
                          @click="isPasswordModalOpen = true; passwordModalForm.selectedAccount = account.account"
                          class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors duration-200 ml-2"
                        >
                          <svg
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                            />
                          </svg>
                          Change Password
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>

            <!-- Create Account Section -->
            <section
              class="bg-gray-900/60 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 overflow-hidden hover:shadow-green-900/5 transition-all duration-300"
            >
              <div
                class="p-6 border-b border-gray-800 flex items-center justify-between"
              >
                <h3 class="text-xl font-semibold text-white flex items-center">
                  <svg
                    class="w-5 h-5 mr-3 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                  Create Game Account
                </h3>
                <div
                  class="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-400"
                >
                  New
                </div>
              </div>

              <div class="p-6">
                <div
                  class="mb-5 p-4 bg-gray-800/50 rounded-lg border border-gray-700/50"
                >
                  <div class="flex items-center text-sm text-gray-300">
                    <svg
                      class="w-5 h-5 mr-2 text-blue-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                    <span
                      >Create a separate account for each character you play.
                      Your password should include uppercase letters, numbers
                      and special characters.</span
                    >
                  </div>
                </div>

                <form @submit.prevent="createAccount" class="space-y-5">
                  <div class="group">
                    <label
                      class="block text-sm font-medium text-gray-300 mb-2 flex items-center"
                    >
                      Username
                      <span class="ml-1 text-xs text-gray-500"
                        >(3-12 characters)</span
                      >
                    </label>
                    <div class="relative">
                      <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                      >
                        <svg
                          class="w-5 h-5 text-gray-500"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                          />
                        </svg>
                      </div>
                      <input
                        v-model="accountForm.username"
                        type="text"
                        class="w-full bg-gray-800/70 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter username"
                        required
                      />
                    </div>
                  </div>

                  <div class="group">
                    <label
                      class="block text-sm font-medium text-gray-300 mb-2 flex items-center"
                    >
                      Password
                      <span class="ml-1 text-xs text-gray-500"
                        >(min. 8 characters)</span
                      >
                    </label>
                    <div class="relative">
                      <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                      >
                        <svg
                          class="w-5 h-5 text-gray-500"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                          />
                        </svg>
                      </div>
                      <input
                        v-model="accountForm.password"
                        type="password"
                        class="w-full bg-gray-800/70 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter password"
                        required
                      />
                    </div>
                  </div>

                  <div class="group">
                    <label class="block text-sm font-medium text-gray-300 mb-2"
                      >Confirm Password</label
                    >
                    <div class="relative">
                      <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                      >
                        <svg
                          class="w-5 h-5 text-gray-500"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                          />
                        </svg>
                      </div>
                      <input
                        v-model="accountForm.confirmed_password"
                        type="password"
                        class="w-full bg-gray-800/70 border border-gray-700 rounded-lg pl-10 pr-4 py-3 text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                        placeholder="Confirm password"
                        required
                      />
                    </div>
                  </div>

                  <div class="pt-2">
                    <button
                      type="submit"
                      class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-medium py-3 rounded-lg transition-all duration-150 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-900 flex items-center justify-center"
                    >
                      <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                      </svg>
                      Create New Account
                    </button>
                    <p class="mt-3 text-xs text-center text-gray-500">
                      By creating an account, you agree to our
                      <a href="#" class="text-green-400 hover:underline"
                        >Terms of Service</a
                      >
                    </p>
                  </div>
                </form>
              </div>
            </section>
          </div>

          <!-- Right Column -->
          <div class="space-y-8">
            <!-- Security Section -->
            <section
              class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 p-6"
            >
              <h2 class="text-xl font-semibold mb-6 flex items-center">
                <svg
                  class="w-5 h-5 mr-2 text-yellow-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  />
                </svg>
                Security
              </h2>
              <form @submit.prevent="resetPassword" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2"
                    >Current Password</label
                  >
                  <input
                    v-model="passwordForm.current_password"
                    type="password"
                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2"
                    >New Password</label
                  >
                  <input
                    v-model="passwordForm.new_password"
                    type="password"
                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2"
                    >Confirm Password</label
                  >
                  <input
                    v-model="passwordForm.confirm_password"
                    type="password"
                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                </div>
                <button
                  type="submit"
                  class="w-full bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-700 hover:to-yellow-600 text-white font-medium py-2.5 rounded-lg transition-all duration-150 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                >
                  Update Password
                </button>
              </form>
            </section>

            <!-- Vote & Rewards Section -->
            <section
              class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 p-6"
            >
              <h2 class="text-xl font-semibold mb-6 flex items-center">
                <svg
                  class="w-5 h-5 mr-2 text-purple-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a4 4 0 00-3.841 2.855l-1.5 6A4 4 0 004.045 16H7m6 0v-3m-3 3h.008v.008H10V16zm0 0h4"
                  />
                </svg>
                Vote & Rewards
              </h2>
              <div class="space-y-6">
                <!-- Vote Section -->
                <Vote :accounts="accounts" />
                <!-- Redeem Code Section -->
                <div>
                  <h3 class="text-lg font-medium text-red-400 mb-4">
                    Redeem Code
                  </h3>
                  <form @submit.prevent="redeemCode" class="space-y-4">
                    <select
                      v-model="redeemForm.selectedAccount"
                      type="text"
                      placeholder="Enter your code"
                      class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2.5 text-dark focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      required
                    >
                      <option
                        v-for="(character, index) in characters"
                        :key="index"
                        :value="character.m_idPlayer"
                      >
                        {{ character.m_szName }}
                      </option>
                    </select>

                    <input
                      v-model="redeemForm.code"
                      type="text"
                      placeholder="Enter your code"
                      class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      required
                    />
                    <button
                      type="submit"
                      class="w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-medium py-2.5 rounded-lg transition-all duration-150 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-900"
                    >
                      Redeem Code
                    </button>
                  </form>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
    



    <!-- Modal Backdrop -->
    <div 
      v-if="isPasswordModalOpen" 
      class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40"
      @click="closePasswordModal"
    ></div>

    <!-- Modal Content -->
    <transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      enter-to-class="opacity-100 translate-y-0 sm:scale-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100 translate-y-0 sm:scale-100"
      leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
      <div 
        v-if="isPasswordModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        @click="isPasswordModalOpen = false"
        aria-modal="true"
      >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div 
            class="relative inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800"
            @click.stop
          >
            <form @submit.prevent="submitPasswordModal">
              <div class="bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-900/50 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Lock Icon -->
                    <svg class="h-6 w-6 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                      Change Password
                    </h3>
                    <div class="mt-4 space-y-4">
                      <!-- New Password -->
                      <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-300">
                          New Password
                        </label>
                        <div class="mt-1">
                          <input 
                            id="new_password" 
                            v-model="passwordModalForm.new_password" 
                            type="password" 
                            name="new_password" 
                            required 
                            class="shadow-sm bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-700 rounded-md text-white px-4 py-2.5 transition-all duration-200 backdrop-blur-sm hover:bg-gray-800/80 focus:shadow-[0_0_10px_rgba(99,102,241,0.3)] placeholder-gray-500"
                          />
                          <p v-if="passwordErrors.new_password" class="mt-1 text-sm text-red-500">
                            {{ passwordErrors.new_password[0] }}
                          </p>
                        </div>
                      </div>

                      <!-- Confirm New Password -->
                      <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300">
                          Confirm New Password
                        </label>
                        <div class="mt-1">
                          <input 
                            id="new_password_confirmation" 
                            v-model="passwordModalForm.new_password_confirmation" 
                            type="password" 
                            name="new_password_confirmation" 
                            required 
                            class="shadow-sm bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-700 rounded-md text-white px-4 py-2.5 transition-all duration-200 backdrop-blur-sm hover:bg-gray-800/80 focus:shadow-[0_0_10px_rgba(99,102,241,0.3)] placeholder-gray-500"
                            placeholder="Confirm your new password"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                  type="submit" 
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                  :disabled="isModalSubmitting"
                >
                  <svg v-if="isModalSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isModalSubmitting ? 'Updating...' : 'Update Password' }}
                </button>
                <button 
                  type="button" 
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  @click="isPasswordModalOpen = false"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>


  </Layout>
</template>

<style scoped>
.container {
  max-width: 1400px;
}
</style>
