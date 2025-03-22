<script setup>
import { ref, computed } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
  redeemCodes: Object,
});

// Add these variables for bulk selection
const selectedItems = ref([]);
const selectAll = ref(false);

// Toggle select all functionality
const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedItems.value = props.redeemCodes.data.map((code) => code.id);
  } else {
    selectedItems.value = [];
  }
};

// Watch for changes to selectedItems to update selectAll state
const anySelected = computed(() => selectedItems.value.length > 0);
const allSelected = computed(
  () =>
    props.redeemCodes.data.length > 0 &&
    selectedItems.value.length === props.redeemCodes.data.length
);

// Update selectAll when all items are manually selected/deselected
const updateSelectAllState = () => {
  selectAll.value = allSelected.value;
};

// Bulk delete confirmation
const showDeleteConfirm = ref(false);

const confirmBulkDelete = () => {
  if (selectedItems.value.length === 0) return;
  showDeleteConfirm.value = true;
};

const performBulkDelete = () => {
  router.post(
    "/administrator-panel/redeem-codes/bulk-delete",
    { ids: selectedItems.value },
    {
      onSuccess: () => {
        selectedItems.value = [];
        selectAll.value = false;
        showDeleteConfirm.value = false;
      },
    }
  );
};
</script>

<template>
  <Layout>
    <div class="">
      <!-- Header Section -->
      <div class="mb-8">
        <div
          class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6"
        >
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Redeem Codes</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track all redeem codes</p>
          </div>
          <Link
            href="/administrator-panel/redeem-codes/create"
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 group"
          >
            <svg
              class="h-5 w-5 mr-2 group-hover:animate-pulse"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              />
            </svg>
            New Redeem Code
          </Link>
        </div>
      </div>

      <!-- Bulk Actions Bar - Appears when items are selected -->
      <div
        v-if="anySelected"
        class="mb-4 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 flex items-center justify-between animate-fadeIn"
      >
        <div class="flex items-center">
          <span class="text-sm font-medium text-blue-800 mr-2">
            {{ selectedItems.length }} item{{ selectedItems.length > 1 ? "s" : "" }}
            selected
          </span>
          <button
            @click="confirmBulkDelete"
            class="ml-4 inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200"
          >
            <svg
              class="h-3.5 w-3.5 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
            Delete Selected
          </button>
        </div>
        <button
          @click="
            selectedItems = [];
            selectAll = false;
          "
          class="text-xs text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          Clear selection
        </button>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50">
                <th scope="col" class="pl-6 py-4 w-10">
                  <div class="flex items-center">
                    <input
                      type="checkbox"
                      v-model="selectAll"
                      @change="toggleSelectAll"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                  </div>
                </th>
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
                  Description
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Expires
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="code in redeemCodes.data"
                :key="code.id"
                class="hover:bg-gray-50 transition-colors duration-200"
                :class="{ 'bg-blue-50': selectedItems.includes(code.id) }"
              >
                <td class="pl-6 py-4">
                  <div class="flex items-center">
                    <input
                      type="checkbox"
                      :value="code.id"
                      v-model="selectedItems"
                      @change="updateSelectAllState"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ code.code }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500 line-clamp-2">
                    {{ code.description }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                    :class="{
                      'bg-green-100 text-green-800': code.status == 'active',
                      'bg-red-100 text-red-800': code.status != 'active',
                      'bg-yellow-100 text-yellow-800': code.status == 'claimed',
                    }"
                  >
                    <span class="relative flex h-2 w-2 mr-2">
                      <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                        :class="{
                          'bg-green-400': code.status == 'active',
                          'bg-red-400': code.status == 'inactive',
                          'bg-yellow-400': code.status == 'claimed',
                        }"
                      >
                      </span>
                      <span
                        class="relative inline-flex rounded-full h-2 w-2"
                        :class="{
                          'bg-green-500': code.status == 'active',
                          'bg-red-500': code.status == 'inactive',
                          'bg-yellow-500': code.status == 'claimed',
                        }"
                      >
                      </span>
                    </span>
                    <template v-if="code?.history">
                      {{ code.status }} - {{ code.history.account_name }} -
                      {{ code.history.character?.m_szName }}
                    </template>
                    <template v-else>
                      {{ code?.status }}
                    </template>
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  <span
                    :class="{
                      'text-red-600':
                        code.expires_at && moment(code.expires_at).isBefore(moment()),
                    }"
                  >
                    {{
                      code.expires_at
                        ? moment(code.expires_at).format("MMM DD, YYYY")
                        : "Never"
                    }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex items-center justify-center space-x-3">
                    <Link
                      :href="`/administrator-panel/redeem-codes/${code.id}/edit`"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                    >
                      <svg
                        class="h-4 w-4 mr-1.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                      Edit
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination (if available in redeemCodes.links) -->
        <div
          v-if="redeemCodes.links"
          class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
        >
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ redeemCodes.from }}</span>
                to
                <span class="font-medium">{{ redeemCodes.to }}</span>
                of
                <span class="font-medium">{{ redeemCodes.total }}</span>
                results
              </p>
            </div>
            <div>
              <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
              >
                <template v-for="(link, i) in redeemCodes.links" :key="i">
                  <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer',
                      link.active
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      i === 0 ? 'rounded-l-md' : '',
                      i === redeemCodes.links.length - 1 ? 'rounded-r-md' : '',
                    ]"
                    v-html="link.label"
                  ></Link>
                  <span
                    v-else
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500',
                      i === 0 ? 'rounded-l-md' : '',
                      i === redeemCodes.links.length - 1 ? 'rounded-r-md' : '',
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

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteConfirm"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <!-- Background overlay -->
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>

        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-red-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Delete {{ selectedItems.length }} redeem code{{
                    selectedItems.length > 1 ? "s" : ""
                  }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete the selected redeem codes? This action
                    cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="performBulkDelete"
            >
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="showDeleteConfirm = false"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
