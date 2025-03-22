<script setup>
import axios from "axios";
import { onMounted, ref, computed, watch } from "vue";

// Data state
const voucherData = ref({});
const loading = ref(true);
const error = ref(null);
const refreshing = ref(false);

// Filter state
const searchQuery = ref("");
const filterSource = ref("all");
const activeTab = ref("summary");

// For tracking which items are expanded in the character/guild view
const expandedItems = ref({});

// Toggle expansion of character/guild item
const toggleExpand = (id) => {
  expandedItems.value[id] = !expandedItems.value[id];
};

// Check if an item is expanded
const isExpanded = (id) => {
  return !!expandedItems.value[id];
};

// Fetch data from the correct endpoint
const fetchVoucherData = async (forceRefresh = false) => {
  loading.value = true;
  error.value = null;

  try {
    const url = forceRefresh
      ? "/administrator-panel/donate-vouchers-per-character?refresh=true"
      : "/administrator-panel/donate-vouchers-per-character";

    const response = await axios.get(url);
    voucherData.value = response.data;

    if (
      response.data.status === "refreshing" ||
      response.data.status === "processing"
    ) {
      refreshing.value = true;
      // Poll for updates if refresh is in progress
      setTimeout(() => fetchVoucherData(), 10000); // Check again in 10 seconds
    } else {
      refreshing.value = false;
    }
  } catch (err) {
    error.value = err.message || "Failed to fetch voucher data";
  } finally {
    loading.value = false;
  }
};

// Trigger data refresh
const refreshData = () => {
  refreshing.value = true;
  fetchVoucherData(true);
};

// Combined computed vouchers from all sources
const allVouchers = computed(() => {
  if (!voucherData.value || !voucherData.value.inventories_vouchers) return [];

  const sources = {
    inventory: voucherData.value.inventories_vouchers || [],
    guild: voucherData.value.guild_inventories_vouchers || [],
    bank: voucherData.value.bank_vouchers || [],
    chest: voucherData.value.character_chest_vouchers || [],
    mail: voucherData.value.voucher_mails || [],
  };

  let result = [];

  Object.entries(sources).forEach(([sourceType, vouchers]) => {
    vouchers.forEach((voucher) => {
      result.push({
        ...voucher,
        source_type: sourceType,
        quantity: parseInt(voucher.quantity || 1), // Ensure quantity is a number
      });
    });
  });

  return result;
});

// Filtered vouchers based on search query and source filter
const filteredVouchers = computed(() => {
  let result = allVouchers.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter((voucher) => {
      // Search by character ID or item ID
      return (
        voucher.character_id?.toString().includes(query) ||
        voucher.guild_id?.toString().includes(query) ||
        voucher.item_id?.toString().includes(query)
      );
    });
  }

  if (filterSource.value !== "all") {
    result = result.filter(
      (voucher) => voucher.source_type === filterSource.value
    );
  }

  return result;
});

// Group vouchers by character/guild for better organization
const groupedVouchers = computed(() => {
  const result = {};

  filteredVouchers.value.forEach((voucher) => {
    const ownerId = voucher.character_id || voucher.guild_id;
    const ownerType = voucher.character_id ? "character" : "guild";
    const key = `${ownerType}-${ownerId}`;

    if (!result[key]) {
      result[key] = {
        id: ownerId,
        type: ownerType,
        vouchers: [],
      };
    }

    result[key].vouchers.push(voucher);
  });

  return Object.values(result);
});

// Summary statistics - UPDATED to count quantities, not just records
const voucherStats = computed(() => {
  if (!voucherData.value || !voucherData.value.inventories_vouchers) {
    return {
      totalVouchers: 0,
      uniqueCharacters: 0,
      uniqueGuilds: 0,
      sources: {},
      voucherTypes: {
        "7652": 0,
        "7653": 0,
        "7654": 0,
        "7655": 0,
      },
    };
  }

  const sources = {
    inventory: voucherData.value.inventories_vouchers || [],
    guild: voucherData.value.guild_inventories_vouchers || [],
    bank: voucherData.value.bank_vouchers || [],
    chest: voucherData.value.character_chest_vouchers || [],
    mail: voucherData.value.voucher_mails || [],
  };

  const sourceStats = {};
  const characters = new Set();
  const guilds = new Set();
  let totalVouchers = 0;

  // Track total quantity per voucher type - CHANGED to use string keys
  const voucherTypeQuantities = {
    "7652": 0, // CSV 50
    "7653": 0, // CSV 100
    "7654": 0, // CSV 500
    "7655": 0, // CSV 1000
  };

  Object.entries(sources).forEach(([sourceType, vouchers]) => {
    // Initialize source quantity counter
    sourceStats[sourceType] = 0;

    vouchers.forEach((voucher) => {
      // Use quantity rather than just counting records
      const quantity = parseInt(voucher.quantity || 1);
      sourceStats[sourceType] += quantity;
      totalVouchers += quantity;

      // Track by voucher type as well - FIXED to normalize as string
      const itemId = String(voucher.item_id);
      if (itemId && voucherTypeQuantities.hasOwnProperty(itemId)) {
        voucherTypeQuantities[itemId] += quantity;
      }

      if (voucher.character_id) characters.add(voucher.character_id);
      if (voucher.guild_id) guilds.add(voucher.guild_id);
    });
  });

  return {
    totalVouchers,
    uniqueCharacters: characters.size,
    uniqueGuilds: guilds.size,
    sources: sourceStats,
    voucherTypes: voucherTypeQuantities,
  };
});
// Format cache timestamp
const formatTimestamp = (timestamp) => {
  if (!timestamp) return "N/A";
  const date = new Date(timestamp);
  return date.toLocaleString();
};

// Initialize data fetch
onMounted(() => {
  fetchVoucherData();
});

// Map voucher item IDs to readable names
const voucherNames = {
  7652: "CSV 50",
  7653: "CSV 100",
  7654: "CSV 500",
  7655: "CSV 1000",
};

const getVoucherName = (itemId) => {
  const id = String(itemId);
  return voucherNames[id] || `Voucher (${id})`;
};

// Determine source name for display
const getSourceName = (sourceType) => {
  const sourceMap = {
    inventory: "Character Inventory",
    guild: "Guild Bank",
    bank: "Character Bank",
    chest: "Character Chest",
    mail: "Mail",
  };

  return sourceMap[sourceType] || sourceType;
};
</script>

<template>
  <div class="bg-white rounded-xl shadow">
    <!-- Header with Status and Refresh -->
    <div
      class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
    >
      <div>
        <h2 class="text-xl font-semibold text-gray-800">
          Donation Voucher Tracker
        </h2>
        <p class="text-sm text-gray-500 mt-1">
          <span v-if="voucherData.cache_info">
            Last updated:
            {{ formatTimestamp(voucherData.cache_info?.cached_at) }}
            <span
              class="text-xs ml-2 px-2 py-0.5 rounded-full"
              :class="{
                'bg-green-100 text-green-800':
                  voucherData.cache_info?.cache_age < 30,
                'bg-yellow-100 text-yellow-800':
                  voucherData.cache_info?.cache_age >= 30 &&
                  voucherData.cache_info?.cache_age < 60,
                'bg-red-100 text-red-800':
                  voucherData.cache_info?.cache_age >= 60,
              }"
            >
              {{ voucherData.cache_info?.cache_age || "0 minutes" }} old
            </span>
          </span>
          <span v-else-if="voucherData.metadata">
            Last updated: {{ formatTimestamp(voucherData.metadata?.cached_at) }}
          </span>
          <span v-else>Retrieving data...</span>
        </p>
      </div>

      <button
        @click="refreshData"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 disabled:opacity-50"
        :disabled="refreshing"
      >
        <span v-if="refreshing">
          <svg
            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline"
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
          Refreshing...
        </span>
        <span v-else>Refresh Data</span>
      </button>
    </div>

    <!-- Loading State -->
    <div
      v-if="loading && !refreshing"
      class="px-6 py-12 flex flex-col items-center justify-center"
    >
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
      <p class="mt-4 text-gray-600">Loading voucher data...</p>
    </div>

    <!-- Error State -->
    <div
      v-else-if="error"
      class="px-6 py-12 flex flex-col items-center justify-center"
    >
      <div class="rounded-full bg-red-100 p-3 mb-4">
        <svg
          class="h-8 w-8 text-red-600"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          ></path>
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900">
        Error loading voucher data
      </h3>
      <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
      <button
        @click="fetchVoucherData()"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
      >
        Try Again
      </button>
    </div>

    <!-- Refreshing State -->
    <div
      v-else-if="
        voucherData.status === 'refreshing' ||
        voucherData.status === 'processing'
      "
      class="px-6 py-8 flex flex-col items-center justify-center bg-blue-50"
    >
      <svg
        class="animate-spin h-10 w-10 text-blue-600 mb-4"
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
      <h3 class="text-lg font-medium text-gray-900">Refreshing voucher data</h3>
      <p class="mt-1 text-sm text-gray-500">
        This may take several minutes for large datasets.
      </p>
      <p class="mt-1 text-xs text-gray-500">
        The page will automatically update when complete.
      </p>
    </div>

    <!-- Main Content -->
    <div
      v-else-if="voucherData.inventories_vouchers"
      class="divide-y divide-gray-200"
    >
      <!-- Navigation Tabs -->
      <div class="border-b border-gray-200">
        <nav class="px-6 -mb-px flex space-x-6">
          <button
            @click="activeTab = 'summary'"
            :class="[
              activeTab === 'summary'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            Summary
          </button>
          <button
            @click="activeTab = 'details'"
            :class="[
              activeTab === 'details'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            Detailed View
          </button>
          <button
            @click="activeTab = 'characters'"
            :class="[
              activeTab === 'characters'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            By Character/Guild
          </button>
        </nav>
      </div>

      <!-- Summary Tab -->
      <div v-if="activeTab === 'summary'" class="p-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
          >
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Total Vouchers
              </dt>
              <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ voucherStats.totalVouchers }}
              </dd>
            </div>
          </div>

          <div
            class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
          >
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Unique Characters
              </dt>
              <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ voucherStats.uniqueCharacters }}
              </dd>
            </div>
          </div>

          <div
            class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
          >
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Unique Guilds
              </dt>
              <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{ voucherStats.uniqueGuilds }}
              </dd>
            </div>
          </div>

          <div
            class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200"
          >
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Average Per Owner
              </dt>
              <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{
                  voucherStats.uniqueCharacters + voucherStats.uniqueGuilds > 0
                    ? (
                        voucherStats.totalVouchers /
                        (voucherStats.uniqueCharacters +
                          voucherStats.uniqueGuilds)
                      ).toFixed(2)
                    : 0
                }}
              </dd>
            </div>
          </div>
        </div>

        <!-- Voucher Type Breakdown -->
        <div class="mt-8">
          <h3 class="text-lg font-medium text-gray-900">Vouchers by Type</h3>
          <div
            class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
          >
            <div
              v-for="(count, voucherType) in voucherStats.voucherTypes"
              :key="voucherType"
              class="bg-white p-4 rounded-lg border border-gray-200 flex items-center justify-between"
            >
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ getVoucherName(voucherType) }}
                </p>
                <p class="text-xs text-gray-500">
                  {{
                    Math.round((count / voucherStats.totalVouchers) * 100) || 0
                  }}% of total
                </p>
              </div>
              <div class="text-2xl font-semibold text-gray-900">
                {{ count }}
              </div>
            </div>
          </div>
        </div>

        <!-- Source Breakdown -->
        <div class="mt-8">
          <h3 class="text-lg font-medium text-gray-900">Vouchers by Source</h3>
          <div
            class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
          >
            <div
              v-for="(count, source) in voucherStats.sources"
              :key="source"
              class="bg-white p-4 rounded-lg border border-gray-200 flex items-center justify-between"
            >
              <div>
                <p class="text-sm font-medium text-gray-900">
                  {{ getSourceName(source) }}
                </p>
                <p class="text-xs text-gray-500">
                  {{
                    Math.round((count / voucherStats.totalVouchers) * 100) || 0
                  }}% of total
                </p>
              </div>
              <div class="text-2xl font-semibold text-gray-900">
                {{ count }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detailed View Tab -->
      <div v-if="activeTab === 'details'" class="p-6">
        <!-- Filters -->
        <div class="mb-6 flex flex-wrap gap-4">
          <div class="w-full sm:w-64">
            <label
              for="search"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Search</label
            >
            <input
              type="text"
              name="search"
              id="search"
              v-model="searchQuery"
              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
              placeholder="Search by ID..."
            />
          </div>

          <div class="w-full sm:w-64">
            <label
              for="source"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Filter by Source</label
            >
            <select
              id="source"
              name="source"
              v-model="filterSource"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
            >
              <option value="all">All Sources</option>
              <option value="inventory">Character Inventory</option>
              <option value="guild">Guild Bank</option>
              <option value="bank">Character Bank</option>
              <option value="chest">Character Chest</option>
              <option value="mail">Mail</option>
            </select>
          </div>
        </div>

        <!-- Results Count -->
        <p class="text-sm text-gray-500 mb-4">
          Found {{ filteredVouchers.length }} records with
          {{
            filteredVouchers.reduce(
              (sum, v) => sum + parseInt(v.quantity || 1),
              0
            )
          }}
          total vouchers
          <span v-if="searchQuery || filterSource !== 'all'">
            (filtered from {{ allVouchers.length }} total records)
          </span>
        </p>

        <!-- Results Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Owner ID
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Type
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Voucher
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Quantity
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Serial Number
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Source
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="(voucher, idx) in filteredVouchers"
                :key="idx"
                class="hover:bg-gray-50"
              >
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                >
                  {{ voucher.character_id || voucher.guild_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ voucher.character_id ? "Character" : "Guild" }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ getVoucherName(voucher.item_id) }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center"
                >
                  {{ voucher.quantity || 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ voucher.serial_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ getSourceName(voucher.source_type) }}
                </td>
              </tr>

              <!-- Empty state -->
              <tr v-if="filteredVouchers.length === 0">
                <td
                  colspan="6"
                  class="px-6 py-8 text-center text-sm text-gray-500"
                >
                  No vouchers found matching your criteria
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Character/Guild View Tab -->
      <div v-if="activeTab === 'characters'" class="p-6">
        <!-- Filters -->
        <div class="mb-6">
          <div class="w-full sm:w-64">
            <label
              for="character-search"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Search Character/Guild</label
            >
            <input
              type="text"
              name="character-search"
              id="character-search"
              v-model="searchQuery"
              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
              placeholder="Enter ID..."
            />
          </div>
        </div>

        <!-- Results Count -->
        <p class="text-sm text-gray-500 mb-4">
          Found {{ groupedVouchers.length }} characters/guilds with vouchers
        </p>

        <!-- Expandable Character List -->
        <div class="space-y-4">
          <div
            v-for="group in groupedVouchers"
            :key="`${group.type}-${group.id}`"
            class="border border-gray-200 rounded-lg"
          >
            <!-- Collapsible header -->
            <button
              @click="toggleExpand(`${group.type}-${group.id}`)"
              class="w-full px-4 py-3 flex justify-between items-center text-left text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-t-lg"
            >
              <div>
                <span class="font-medium"
                  >{{ group.type === "character" ? "Character" : "Guild" }} ID:
                  {{ group.id }}</span
                >
                <span class="ml-2 text-sm text-gray-500">
                  {{
                    group.vouchers.reduce(
                      (total, v) => total + parseInt(v.quantity || 1),
                      0
                    )
                  }}
                  vouchers in {{ group.vouchers.length }} different locations
                </span>
              </div>
              <svg
                class="h-5 w-5 text-gray-500 transition-transform duration-200"
                :class="{
                  'transform rotate-180': isExpanded(
                    `${group.type}-${group.id}`
                  ),
                }"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <!-- Collapsible content -->
            <div
              v-if="isExpanded(`${group.type}-${group.id}`)"
              class="px-4 py-3 transition-all duration-200"
            >
              <table class="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Voucher
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Quantity
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Serial Number
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Source
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="(voucher, idx) in group.vouchers"
                    :key="idx"
                    class="hover:bg-gray-50"
                  >
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                    >
                      {{ getVoucherName(voucher.item_id) }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center"
                    >
                      {{ voucher.quantity || 1 }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ voucher.serial_number }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ getSourceName(voucher.source_type) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Empty state -->
          <div
            v-if="groupedVouchers.length === 0"
            class="py-8 text-center text-sm text-gray-500 bg-gray-50 rounded-lg"
          >
            No characters or guilds found with vouchers matching your criteria
          </div>
        </div>
      </div>

      <!-- No data state -->
      <div
        v-if="
          !loading &&
          (!voucherData.inventories_vouchers ||
            voucherData.inventories_vouchers.length === 0)
        "
        class="px-6 py-12 flex flex-col items-center justify-center"
      >
        <div class="rounded-full bg-gray-100 p-3 mb-4">
          <svg
            class="h-8 w-8 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            ></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">
          No voucher data available
        </h3>
        <p class="mt-1 text-sm text-gray-500">
          Click the refresh button to fetch the latest data.
        </p>
        <button
          @click="refreshData()"
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
        >
          Refresh Data
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animation for the expand/collapse arrow */
.transition-transform {
  transition: transform 0.2s ease-in-out;
}

/* Smooth transition for the expand/collapse content */
.transition-all {
  transition: all 0.2s ease-in-out;
}
</style>