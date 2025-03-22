<script setup>
import axios from "axios";
import { onMounted, ref, computed } from "vue";

const donationRanking = ref([]);
const loading = ref(true);
const sortBy = ref("totalDonation"); 
const sortDirection = ref("desc");
const totalDonatePoints = ref(0);

// Fetch donation rankings data
onMounted(() => {
  loading.value = true;
  axios
    .get("/administrator-panel/donate-rankings/fetch")
    .then((response) => {
      totalDonatePoints.value = response.data.totalDonatePoints;
      donationRanking.value = response.data.rankings;
      loading.value = false;
    })
    .catch((error) => {
      console.error("Error fetching donation rankings:", error);
      loading.value = false;
    });
});

// Calculate donations with separate currency totals
const overallStats = computed(() => {
  if (!donationRanking.value.length) {
    return {
      total: 0,
      pesoTotal: 0,
      euroTotal: 0,
      users: 0,
      average: 0,
    };
  }

  let pesoTotal = 0;
  let euroTotal = 0;

  donationRanking.value.forEach((item) => {
    // Calculate GCash donations (in PHP)
    if (item.user.g_cash_donates && item.user.g_cash_donates.length) {
      item.user.g_cash_donates.forEach((donate) => {
        if (donate.status === "success") {
          pesoTotal += parseFloat(donate.donate_g_cash.amount || 0);
        }
      });
    }

    // Calculate PayPal donations (in EUR)
    if (item.user.paypal_donates && item.user.paypal_donates.length) {
      item.user.paypal_donates.forEach((donate) => {
        if (donate.status === "COMPLETED") {
          euroTotal += parseFloat(donate.donate_paypal.amount || 0);
        }
      });
    }
  });

  const usersWithDonations = donationRanking.value.filter(
    (item) => item.totalDonation > 0
  ).length;
  const total = donationRanking.value.reduce(
    (sum, item) => sum + item.totalDonation,
    0
  );

  return {
    total: total,
    pesoTotal: pesoTotal,
    euroTotal: euroTotal,
    users: usersWithDonations,
    average: usersWithDonations ? (total / usersWithDonations).toFixed(2) : 0,
  };
});

// Sort function for the ranking data - now only allows sorting by totalDonation
const sortedRankings = computed(() => {
  return [...donationRanking.value].sort((a, b) => {
    // Only allow sorting by totalDonation
    let modifier = sortDirection.value === "desc" ? -1 : 1;
    return modifier * (a.totalDonation - b.totalDonation);
  });
});

// Format currency function with type parameter
const formatCurrency = (amount, type = "total") => {
  if (type === "gcash") {
    // Format as Philippine Peso (PHP)
    return new Intl.NumberFormat("en-PH", {
      style: "currency",
      currency: "PHP",
      minimumFractionDigits: 2,
    }).format(amount);
  } else if (type === "paypal") {
    // Format as Euro (EUR)
    return new Intl.NumberFormat("en-DE", {
      // Changed from en-EU to en-DE for better Euro formatting
      style: "currency",
      currency: "EUR",
      minimumFractionDigits: 2,
    }).format(amount);
  } else {
    // Default format - USD for total
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",
      minimumFractionDigits: 2,
    }).format(amount);
  }
};

// Calculate donation amounts by type for a user
const getUserDonationsByType = (user) => {
  let gcashTotal = 0;
  let paypalTotal = 0;

  if (user.g_cash_donates && user.g_cash_donates.length) {
    user.g_cash_donates.forEach((donate) => {
      if (donate.status === "success") {
        gcashTotal += parseFloat(donate.donate_g_cash.amount || 0);
      }
    });
  }

  if (user.paypal_donates && user.paypal_donates.length) {
    user.paypal_donates.forEach((donate) => {
      if (donate.status === "COMPLETED") {
        paypalTotal += parseFloat(donate.donate_paypal.amount || 0);
      }
    });
  }

  return {
    gcash: gcashTotal,
    paypal: paypalTotal,
  };
};

// Toggle sort direction - now only used for totalDonation
const toggleSort = (column) => {
  // Only allow sorting for totalDonation column
  if (column === "totalDonation") {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  }
};
</script>

<template>
  <div>
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
      <div
        class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center"
      >
        <div class="rounded-full bg-blue-100 p-3 mr-4">
          <svg
            class="w-8 h-8 text-blue-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Donations</p>
          <p class="text-2xl font-bold text-gray-800">
            (₱)(€) {{ overallStats.total }}
          </p>
        </div>
      </div>

      <!-- New Widget for Total Donation Points -->
      <div
        class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center"
      >
        <div class="rounded-full bg-green-100 p-3 mr-4">
          <svg
            class="w-8 h-8 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Total Donation Points</p>
          <p class="text-2xl font-bold text-gray-800">
            {{ totalDonatePoints }}
          </p>
        </div>
      </div>

      <div
        class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center"
      >
        <div class="rounded-full bg-green-100 p-3 mr-4">
          <svg
            class="w-8 h-8 text-green-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
            ></path>
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Donors</p>
          <p class="text-2xl font-bold text-gray-800">
            {{ overallStats.users }}
          </p>
        </div>
      </div>

      <div
        class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center"
      >
        <div class="rounded-full bg-green-100 p-3 mr-4">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8 text-green-600"
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
        <div>
          <p class="text-sm font-medium text-gray-500">GCash Total</p>
          <p class="text-2xl font-bold text-gray-800">
            {{ formatCurrency(overallStats.pesoTotal, "gcash") }}
          </p>
        </div>
      </div>

      <div
        class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex items-center"
      >
        <div class="rounded-full bg-purple-100 p-3 mr-4">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8 text-purple-600"
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
        <div>
          <p class="text-sm font-medium text-gray-500">PayPal Total</p>
          <p class="text-2xl font-bold text-gray-800">
            {{ formatCurrency(overallStats.euroTotal, "paypal") }}
          </p>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <svg
        class="animate-spin h-10 w-10 text-blue-600"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        ></path>
      </svg>
    </div>

    <!-- Main Rankings Table -->
    <div
      v-else-if="donationRanking.length > 0"
      class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200"
    >
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-900">Donation Rankings</h3>
        <p class="text-sm text-gray-500 mt-1">
          Ranked by total donation amount
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Rank
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Email
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Accounts
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                PayPal (€)
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                GCash (₱)
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                @click="toggleSort('totalDonation')"
              >
                <div class="flex items-center justify-end">
                  Total Amount
                  <svg
                    :class="[
                      'opacity-100',
                      sortDirection === 'desc' ? 'transform rotate-180' : '',
                    ]"
                    class="ml-1 w-4 h-4 transition-transform"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(item, index) in sortedRankings"
              :key="item.user.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <template v-if="index === 0">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 mr-1 text-yellow-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 001.414 1.414L9 6.414l4.293 4.293a1 1 0 001.414-1.414l-5-5z"
                          clip-rule="evenodd"
                        />
                      </svg>
                      #1
                    </span>
                  </template>
                  <span v-else class="text-gray-500 ms-4">{{ index + 1 }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                  {{ item.user.email }}
                </div>
                <div class="text-xs text-gray-500">
                  Member since
                  {{ new Date(item.user.created_at).toLocaleDateString() }}
                </div>
              </td>
              <td class="px-6 py-4">
                <!-- Display account details -->
                <div
                  v-if="
                    item?.user?.account_details &&
                    item.user.account_details.length > 0
                  "
                >
                  <div
                    v-for="(accountDetail, accIndex) in item.user
                      .account_details"
                    :key="accIndex"
                    class="text-xs text-gray-500 mb-1"
                  >
                    <a
                      class="text-blue-500"
                      :href="`/administrator-panel/account/${accountDetail.account}`"
                      target="_blank"
                    >
                      <span class="font-medium">{{
                        accountDetail?.account || "N/A"
                      }}</span>
                      <span v-if="accountDetail?.account_information"
                        >(Cash:
                        {{
                          accountDetail.account_information?.cash || 0
                        }})</span
                      >
                    </a>
                  </div>
                </div>
                <div v-else class="text-xs text-gray-500">
                  No accounts linked
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex flex-col items-center">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                  >
                    {{ item.user.paypal_donates.length }}
                  </span>
                  <span class="text-xs text-gray-600 mt-1">
                    {{
                      getUserDonationsByType(item.user).paypal > 0
                        ? formatCurrency(
                            getUserDonationsByType(item.user).paypal,
                            "paypal"
                          )
                        : "-"
                    }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex flex-col items-center">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                  >
                    {{ item.user.g_cash_donates.length }}
                  </span>
                  <span class="text-xs text-gray-600 mt-1">
                    {{
                      getUserDonationsByType(item.user).gcash > 0
                        ? formatCurrency(
                            getUserDonationsByType(item.user).gcash,
                            "gcash"
                          )
                        : "-"
                    }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                <span
                  v-if="item.totalDonation > 0"
                  class="font-bold text-gray-900"
                  >(₱)(€) {{ item.totalDonation }}</span
                >
                <span v-else class="text-gray-500">0.00</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-else
      class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200"
    >
      <svg
        class="mx-auto h-12 w-12 text-gray-400"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        ></path>
      </svg>
      <h3 class="mt-2 text-lg font-medium text-gray-900">
        No donation data available
      </h3>
      <p class="mt-1 text-sm text-gray-500">
        There are no donation rankings to display at this time.
      </p>
    </div>
  </div>
</template>

<style scoped>
/* Animation for sorting */
.transform {
  transition: transform 0.15s ease-in-out;
}

/* Override for better empty state visual */
.rounded-full {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>