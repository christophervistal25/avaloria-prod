<script setup>
import { defineComponent, ref, watchEffect, computed } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import moment from "moment";
import { Notyf } from "notyf";

const props = defineProps(["user"]);
const loading = ref({
  points: false,
  credentials: false,
  ban: false
});

defineComponent({
  Head,
});

const points = ref({
  votepoint: 0,
  cash: 0,
});

const credentials = ref({
  username: "",
  password: "",
});

const ban = ref({
  date: "",
});

const formattedBanDate = computed(() => {
  return ban.value.date ? moment(ban.value.date).format("YYYY-MM-DD") : "";
});

const accountStatus = computed(() => {
  if (!props.user?.account_detail?.BlockTime) return { text: "Active", color: "green" };
  const banDate = moment(props.user.account_detail.BlockTime);
  if (banDate.isBefore(moment())) return { text: "Active", color: "green" };
  return { text: "Banned", color: "red" };
});

const submitPoints = () => {
  loading.value.points = true;
  axios
    .post(`/administrator-panel/account/${props.user.account}/points`, points.value)
    .then((res) => {
      if (res.status === 200) {
        new Notyf().success(res.data.message);
      }
    })
    .catch((err) => {
      new Notyf().error(err.response.data.message);
    })
    .finally(() => {
      loading.value.points = false;
    });
};

const submitCredentials = () => {
  if (!credentials.value.password) {
    new Notyf().error("Please enter a new password");
    return;
  }
  
  if (confirm("Are you sure you want to change this user's password?")) {
    loading.value.credentials = true;
    axios
      .post(
        `/administrator-panel/account/${props.user.account}/credentials`,
        credentials.value
      )
      .then((res) => {
        if (res.status === 200) {
          new Notyf().success(res.data.message);
          credentials.value.password = "";
        }
      })
      .catch((err) => {
        new Notyf().error(err.response.data.message);
      })
      .finally(() => {
        loading.value.credentials = false;
      });
  }
};

const submitBan = () => {
  if (confirm(`Are you sure you want to ${ban.value.date ? 'ban' : 'unban'} this account?`)) {
    loading.value.ban = true;
    axios
      .post(`/administrator-panel/account/${props.user.account}/ban`, ban.value)
      .then((res) => {
        if (res.status === 200) {
          new Notyf().success(res.data.message);
        }
      })
      .catch((err) => {
        new Notyf().error(err.response.data.message);
      })
      .finally(() => {
        loading.value.ban = false;
      });
  }
};

watchEffect(() => {
  if (props.user) {
    points.value.votepoint = props.user.votepoint || 0;
    points.value.cash = props.user.cash || 0;
    
    credentials.value.username = props.user.account;

    ban.value.date = props.user.account_detail?.BlockTime || "";
  }
});
</script>

<template>
  <layout>
    <template #header>
      <div class="mb-8 border-b border-gray-200 pb-5">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Account Management</h1>
            <p class="text-sm text-gray-600 mt-1">Manage user account details, points and restrictions</p>
          </div>
        </div>
      </div>
    </template>
    
    <!-- Account Overview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
      <div class="flex justify-between items-center mb-4 pb-3 border-b">
        <h2 class="text-xl font-bold text-gray-900">Account Overview</h2>
        <div :class="`px-3 py-1 rounded-full text-xs font-medium ${accountStatus.color === 'green' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
          <span class="flex items-center">
            <span :class="`w-2 h-2 rounded-full mr-1 ${accountStatus.color === 'green' ? 'bg-green-500' : 'bg-red-500'}`"></span>
            {{ accountStatus.text }}
          </span>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500 shadow-sm">
          <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Username</h3>
          <div class="flex items-center">
            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-900">{{ user.account }}</p>
          </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500 shadow-sm">
          <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Vote Points</h3>
          <div class="flex items-center">
            <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-900">{{ points.votepoint }}</p>
          </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-yellow-500 shadow-sm">
          <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Cash Points</h3>
          <div class="flex items-center">
            <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-900">{{ points.cash }}</p>
          </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-gray-500 shadow-sm">
          <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Ban Status</h3>
          <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-900">{{ formattedBanDate || 'No ban' }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
      <!-- Points Form -->
      <div class="bg-white rounded-lg shadow-sm p-6 transition-all border border-gray-200 hover:shadow-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Points Management</h3>
        <div class="space-y-4">
          <div class="transition-all">
            <label class="block text-sm font-medium text-gray-700 mb-1">Vote Points</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">🗳️</span>
              </div>
              <input type="number" v-model="points.votepoint" 
                     class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" />
            </div>
          </div>
          <div class="transition-all">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cash Points</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">💰</span>
              </div>
              <input type="number" v-model="points.cash" 
                     class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" />
            </div>
          </div>
          <button @click="submitPoints" 
                  :disabled="loading.points"
                  class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all flex justify-center items-center">
            <svg v-if="loading.points" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading.points ? 'Updating...' : 'Update Points' }}
          </button>
        </div>
      </div>

      <!-- Credentials Form -->
      <div class="bg-white rounded-lg shadow-sm p-6 transition-all border border-gray-200 hover:shadow-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Account Credentials</h3>
        <div class="space-y-4">
          <div class="transition-all">
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">👤</span>
              </div>
              <input type="text" v-model="credentials.username" readonly
                    class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md transition-all" />
            </div>
          </div>
          <div class="transition-all">
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">🔒</span>
              </div>
              <input type="password" v-model="credentials.password" placeholder="Enter new password"
                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" />
            </div>
          </div>
          <button @click="submitCredentials"
                  :disabled="loading.credentials"
                  class="w-full py-2 px-4 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all flex justify-center items-center">
            <svg v-if="loading.credentials" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading.credentials ? 'Updating...' : 'Reset Password' }}
          </button>
        </div>
      </div>

      <!-- Ban Form -->
      <div class="bg-white rounded-lg shadow-sm p-6 transition-all border border-gray-200 hover:shadow-md">
        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Account Restrictions</h3>
        <div class="space-y-4">
          <div class="transition-all">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ban Until</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">🚫</span>
              </div>
              <input type="text" v-model="ban.date" 
                    class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" />
            </div>
          </div>
          <button @click="submitBan"
                  :disabled="loading.ban"
                  :class="`w-full py-2 px-4 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all flex justify-center items-center ${
                    ban.date ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                  }`">
            <svg v-if="loading.ban" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading.ban ? 'Processing...' : (ban.date ? 'Ban Account' : 'Remove Ban') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-lg shadow-sm transition-all border border-gray-200 hover:shadow-md overflow-hidden mb-6">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-900">Transaction History</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Balance</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Current Balance</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="!user?.purchases || user.purchases.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                No transaction history available
              </td>
            </tr>
            <tr v-for="(cash, index) in user?.purchases" :key="cash.id" 
                class="transition-all hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ cash.id || index + 1 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">{{ cash.beforeCash || 0 }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">{{ cash.afterCash }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                <span :class="`px-2 py-1 rounded-full text-xs font-medium ${
                  (cash.afterCash - (cash.beforeCash || 0)) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }`">
                  {{ (cash.afterCash - (cash.beforeCash || 0)) > 0 ? '+' : '' }}{{ cash.afterCash - (cash.beforeCash || 0) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                {{ moment(cash.Date).format("YYYY-MM-DD hh:mm A") }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-right">
        <span class="text-sm text-gray-600">Total transactions: {{ user?.purchases?.length || 0 }}</span>
      </div>
    </div>
  </layout>
</template>
