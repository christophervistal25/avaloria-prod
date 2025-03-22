<script setup>
import Layout from "@layouts/Layout.vue";
import moment from "moment";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps(["transaction", "gCashRecord"]);
const referenceNumber = ref("");
const isSubmitting = ref(false);
const errors = ref({});
const submitGcashDonation = async () => {
  isSubmitting.value = true;
  try {
    await axios.post("/gcash-checkout", {
      reference_number: referenceNumber.value,
      transaction_id: props.transaction.transaction_id,
    });
    router.visit("/user-account/dashboard");
  } catch (error) {
    errors.value = error.response.data.errors;
    isSubmitting.value = false;
  }
};
</script>
<template>
  <layout>
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Card Container -->
        <div
          class="bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-xl border border-gray-800/50 overflow-hidden"
        >
          <!-- Header -->
          <div
            class="bg-gradient-to-r from-green-600/20 to-green-500/20 border-b border-gray-800 p-6"
          >
            <div class="flex items-center space-x-3">
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
                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              <h1 class="text-2xl font-bold text-white">GCash Donation</h1>
            </div>
          </div>

          <!-- Content -->
          <div class="p-6 space-y-6">
            <!-- QR Code Section -->
            <div class="text-center space-y-4">
              <p class="text-green-400 text-sm font-medium">
                Scan the QR code below to donate, then enter the reference number of your
                GCash transaction
              </p>
              <div class="bg-white p-4 rounded-lg inline-block">
                <img
                  :src="`/storage/images/${gCashRecord.qr_path}`"
                  alt="GCash QR Code"
                  class="w-48 h-48 object-contain mx-auto"
                />
              </div>
            </div>

            <!-- Transaction Details -->
            <div class="grid gap-6">
              <!-- Reference Number Input -->
              <div>
                <label
                  for="reference_number"
                  class="block text-sm font-medium text-gray-400"
                >
                  Reference Number
                  <span class="text-green-400 text-xs ml-1">
                    (Enter the reference number from your GCash transaction)
                  </span>
                </label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="reference_number"
                    v-model="referenceNumber"
                    :class="[
                      'w-full px-4 py-2 bg-gray-800/50 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent',
                      errors.reference_number ? 'border-red-500' : 'border-gray-700',
                    ]"
                    placeholder="Enter reference number"
                  />
                  <p v-if="errors.reference_number" class="mt-1 text-sm text-red-500">
                    {{ errors.reference_number[0] }}
                  </p>
                </div>
              </div>

              <!-- Amount Display -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-800/30 p-4 rounded-lg border border-gray-700">
                  <label class="block text-sm font-medium text-gray-400"
                    >Required Amount</label
                  >
                  <div class="mt-1 text-xl font-semibold text-white">
                    ₱{{ parseFloat(gCashRecord.amount).toFixed(2) }}
                  </div>
                </div>

                <div class="bg-gray-800/30 p-4 rounded-lg border border-gray-700">
                  <label class="block text-sm font-medium text-gray-400"
                    >Donate Points</label
                  >
                  <div class="mt-1 text-xl font-semibold text-green-400">
                    {{
                      parseInt(gCashRecord.equivalent_donate_points).toLocaleString()
                    }}
                    Points
                  </div>
                </div>
              </div>

              <!-- Transaction Date -->
              <div class="bg-gray-800/30 p-4 rounded-lg border border-gray-700">
                <label class="block text-sm font-medium text-gray-400"
                  >Transaction Date</label
                >
                <div class="mt-1 text-white">
                  {{ moment(transaction.created_at).format("MMMM Do YYYY, h:mm:ss A") }}
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
              <button
                @click="submitGcashDonation"
                :disabled="isSubmitting"
                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-500 text-white font-medium rounded-lg shadow-lg hover:from-green-700 hover:to-green-600 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center space-x-2"
              >
                <span>{{ isSubmitting ? "Processing..." : "Submit Payment" }}</span>
                <svg
                  v-if="!isSubmitting"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                  />
                </svg>
                <svg
                  v-else
                  class="w-5 h-5 animate-spin"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>
