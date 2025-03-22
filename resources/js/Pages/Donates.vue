<script setup>
import { defineComponent, onMounted, ref } from "vue";
import Layout from "@layouts/Layout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { Notyf } from "notyf";

defineComponent({
  Head,
  Link,
});

const page = usePage();
const props = defineProps({
  accounts: {
    type: Object,
    required: true,
  },
  csrf_token: {
    type: String,
    required: true,
  },
  paypalDonates: {
    type: Object,
    required: true,
  },
  gcashDonates: {
    type: Object,
    required: true,
  },
});

const selectedPaypalDonate = ref(null);
const selectedGcashDonate = ref(null);
const selectedAccount = ref(null);

const isSubmittingPaypal = ref(false);
const isSubmittingGCash = ref(false);

const handlePaypalSubmit = () => {
  if (!selectedPaypalDonate.value || !selectedAccount.value || isSubmittingPaypal.value) {
    return;
  }
  isSubmittingPaypal.value = true;
};

const handleGCashSubmit = () => {
  if (!selectedGcashDonate.value || !selectedAccount.value || isSubmittingGCash.value) {
    return;
  }
  isSubmittingGCash.value = true;
};


onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const account = urlParams.get("account");
  if (account) {
    selectedAccount.value = account;
  }
});
</script>

<template>
  <layout>
    <Head>
      <title>{{ page.props.appName }} | Buy Donate Points</title>
    </Head>

    <div class="container mx-auto px-4 py-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Buy Donate Points</h1>
        <p class="text-gray-400">Support our server and receive exclusive benefits</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- PayPal Section -->
        <section
          class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50"
        >
          <div class="p-6 border-b border-gray-800">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-white flex items-center">
                <svg
                  class="w-5 h-5 mr-2 text-blue-500"
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
                PayPal Donation
              </h2>
            </div>

            <form @submit="handlePaypalSubmit" action="/paypal" method="POST" class="space-y-6">
              <input type="hidden" name="_token" :value="csrf_token" />

              <!-- Account Select -->
              <div class="mb-4">
                <label for="account" class="block text-sm font-medium text-gray-300"
                  >Select Account</label
                >
                <select
                  id="account"
                  v-model="selectedAccount"
                  name="account"
                  class="mt-1 block w-full py-2 px-3 border border-gray-700 bg-gray-800 text-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                >
                  <option
                    v-for="(account, index) in accounts"
                    :key="index"
                    :value="account.account"
                  >
                    {{ account?.account_information?.account }}
                  </option>
                </select>
              </div>

              <div class="grid gap-4">
                <label
                  v-for="donate in paypalDonates"
                  :key="donate.id"
                  class="relative flex items-center p-4 cursor-pointer bg-gray-800/50 rounded-lg border border-gray-700 hover:border-blue-500/50 transition-colors"
                  :class="{
                    'border-blue-500': selectedPaypalDonate === donate.id,
                  }"
                >
                  <input
                    type="radio"
                    name="donate"
                    :value="donate.id"
                    v-model="selectedPaypalDonate"
                    class="hidden"
                  />
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <span class="text-white font-medium">{{ donate.description }}</span>
                      <span class="text-blue-400 font-bold"
                        >€{{ parseInt(donate.amount) }}</span
                      >
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                      Receive
                      {{ parseInt(donate.equivalent_donate_points) }}
                      Donate Points
                    </p>
                  </div>
                  <div
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 rounded-full border-2 flex items-center justify-center"
                    :class="[
                      selectedPaypalDonate === donate.id
                        ? 'border-blue-500 bg-blue-500/20'
                        : 'border-gray-600',
                    ]"
                  >
                    <div
                      v-if="selectedPaypalDonate === donate.id"
                      class="w-2 h-2 bg-blue-500 rounded-full"
                    ></div>
                  </div>
                </label>
              </div>

              <button
                type="submit"
                :disabled="!selectedPaypalDonate || !selectedAccount || isSubmittingPaypal"
                :class="[
                  'w-full font-medium py-3 rounded-lg transition-colors flex items-center justify-center space-x-2',
                  selectedPaypalDonate && selectedAccount && !isSubmittingPaypal
                    ? 'bg-blue-600 hover:bg-blue-700 text-white'
                    : 'bg-gray-700 text-gray-400 cursor-not-allowed',
                ]"
              >
                <span v-if="isSubmittingPaypal">Processing...</span>
                <span v-else>Donate with PayPal</span>
                <svg
                  v-if="!isSubmittingPaypal"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                  />
                </svg>
                <svg 
                  v-else 
                  class="animate-spin ml-2 h-5 w-5 text-white" 
                  xmlns="http://www.w3.org/2000/svg" 
                  fill="none" 
                  viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </button>
            </form>
          </div>
        </section>

        <!-- GCash Section -->
        <section
          class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50"
        >
          <div class="p-6 border-b border-gray-800">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-semibold text-white flex items-center">
                <svg
                  class="w-5 h-5 mr-2 text-green-500"
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
                GCash Donation
              </h2>
            </div>

            <form @submit="handleGCashSubmit" action="/donate-gcash" method="POST" class="space-y-6">
              <input type="hidden" name="_token" :value="csrf_token" />

              <!-- Account Select -->
              <div class="mb-4">
                <label for="account" class="block text-sm font-medium text-gray-300"
                  >Select Account</label
                >
                <select
                  id="account"
                  v-model="selectedAccount"
                  name="idx"
                  class="mt-1 block w-full py-2 px-3 border border-gray-700 bg-gray-800 text-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
                  <option
                    v-for="(account, i) in accounts"
                    :key="i"
                    :value="account.account"
                  >
                    {{ account?.account_information?.account }}
                  </option>
                </select>
              </div>

              <div class="grid gap-4">
                <label
                  v-for="donate in gcashDonates"
                  :key="donate.id"
                  class="relative flex items-center p-4 cursor-pointer bg-gray-800/50 rounded-lg border border-gray-700 hover:border-green-500/50 transition-colors"
                  :class="{
                    'border-green-500': selectedGcashDonate === donate.id,
                  }"
                >
                  <input
                    type="radio"
                    name="donate"
                    :value="donate.id"
                    v-model="selectedGcashDonate"
                    class="hidden"
                  />
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <span class="text-white font-medium">{{ donate.description }}</span>
                      <span class="text-green-400 font-bold"
                        >₱{{ parseInt(donate.amount) }}</span
                      >
                    </div>
                    <p class="text-sm text-gray-400 mt-1">
                      Receive
                      {{ parseInt(donate.equivalent_donate_points) }}
                      Donate Points
                    </p>
                  </div>
                  <div
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 rounded-full border-2 flex items-center justify-center"
                    :class="[
                      selectedGcashDonate === donate.id
                        ? 'border-green-500 bg-green-500/20'
                        : 'border-gray-600',
                    ]"
                  >
                    <div
                      v-if="selectedGcashDonate === donate.id"
                      class="w-2 h-2 bg-green-500 rounded-full"
                    ></div>
                  </div>
                </label>
              </div>

              <button
                type="submit"
                :disabled="!selectedGcashDonate || !selectedAccount || isSubmittingGCash"
                :class="[
                  'w-full font-medium py-3 rounded-lg transition-colors flex items-center justify-center space-x-2',
                  selectedGcashDonate && selectedAccount && !isSubmittingGCash
                    ? 'bg-green-600 hover:bg-green-700 text-white'
                    : 'bg-gray-700 text-gray-400 cursor-not-allowed',
                ]"
              >
                <span v-if="isSubmittingGCash">Processing...</span>
                <span v-else>Donate with GCash</span>
                <svg
                  v-if="!isSubmittingGCash"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                  />
                </svg>
                <svg 
                  v-else 
                  class="animate-spin ml-2 h-5 w-5 text-white" 
                  xmlns="http://www.w3.org/2000/svg" 
                  fill="none" 
                  viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </button>
            </form>
          </div>
        </section>
      </div>
    </div>
  </layout>
</template>

<style scoped>
.container {
  max-width: 1400px;
}

.custom-select {
  background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'><path fill='%23ffffff' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 8px 10px;
}

.custom-select:focus {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

@media (max-width: 1024px) {
  .container {
    max-width: 100%;
  }
}

.disabled-donate {
  pointer-events: none;
  opacity: 0.5;
}

/* Add animation for the spinner */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
</style>