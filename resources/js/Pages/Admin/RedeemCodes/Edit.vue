<script setup>
import { ref, onMounted } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";
import moment from "moment";
import { Notyf } from "notyf";

const props = defineProps({
  redeemCode: Object,
});

const form = useForm({
  code: props.redeemCode.code,
  description: props.redeemCode.description,
  status: props.redeemCode.status || "active",
  expires_at: props.redeemCode.expires_at
    ? moment(props.redeemCode.expires_at).format("YYYY-MM-DD")
    : "",
  details:
    props.redeemCode.details.length > 0
      ? props.redeemCode.details.map((detail) => ({
          item_name: detail.item_name,
          item_description: detail.item_description,
          item_quantity: detail.item_quantity,
          item_code: detail.item_code,
        }))
      : [
          {
            item_name: "",
            item_description: "",
            item_quantity: 1,
            item_code: "",
          },
        ],
});

const addDetail = () => {
  form.details.push({
    item_name: "",
    item_description: "",
    item_quantity: 1,
    item_code: "",
  });
};

const removeDetail = (index) => {
  if (form.details.length > 1) {
    form.details.splice(index, 1);
  }
};

const submit = () => {
  form.put(`/administrator-panel/redeem-codes/${props.redeemCode.id}`, {
    onSuccess: () => {
      new Notyf().success({
        message: "Redeem code updated successfully",
        duration: 4000,
      });
    },
  });
};
</script>

<template>
  <Layout>
    <div class="">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b bg-gray-50">
          <h2 class="text-2xl font-bold text-gray-800">Edit Redeem Code</h2>
          <p class="text-gray-600 mt-1">Update the details of the redemption code</p>
        </div>

        <form @submit.prevent="submit" class="p-8">
          <!-- Main Code Section -->
          <div class="space-y-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-1">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Redemption Code</label>
                <input
                  v-model="form.code"
                  type="text"
                  placeholder="Enter code"
                  class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all"
                />
                <div v-if="form.errors.code" class="text-red-500 text-sm">
                  {{ form.errors.code }}
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Expiration Date</label>
                <input
                  v-model="form.expires_at"
                  type="date"
                  class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                />
              </div>

              <div class="md:col-span-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Description</label>
                <textarea
                  v-model="form.description"
                  rows="3"
                  placeholder="Enter description"
                  class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                ></textarea>
              </div>
            </div>

            <!-- Status Section -->
            <div class="space-y-2">
              <label class="text-sm font-semibold text-gray-700">Status</label>
              <select
                v-model="form.status"
                class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <!-- Items Section -->
            <div class="space-y-6">
              <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Item Details</h3>
                <button
                  type="button"
                  @click="addDetail"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                  <span class="mr-2">+</span> Add Item
                </button>
              </div>

              <div class="space-y-4">
                <div
                  v-for="(detail, index) in form.details"
                  :key="index"
                  class="p-6 border rounded-lg bg-gray-50 relative"
                >
                  <button
                    type="button"
                    @click="removeDetail(index)"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500"
                    :disabled="form.details.length === 1"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>

                  <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                      <label class="text-sm font-semibold text-gray-700">Item Name</label>
                      <input
                        v-model="detail.item_name"
                        type="text"
                        placeholder="Enter item name"
                        class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <div class="space-y-2">
                      <label class="text-sm font-semibold text-gray-700">Item Code</label>
                      <input
                        v-model="detail.item_code"
                        type="text"
                        placeholder="Enter item code"
                        class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <div class="space-y-2">
                      <label class="text-sm font-semibold text-gray-700">Quantity</label>
                      <input
                        v-model.number="detail.item_quantity"
                        type="number"
                        min="1"
                        class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <div class="space-y-2">
                      <label class="text-sm font-semibold text-gray-700"
                        >Item Description</label
                      >
                      <textarea
                        v-model="detail.item_description"
                        placeholder="Enter item description"
                        rows="2"
                        class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
            <Link
              href="/administrator-panel/redeem-codes"
              class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition"
            >
              Cancel
            </Link>
            <button
              type="submit"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
              :disabled="form.processing"
            >
              {{ form.processing ? "Updating..." : "Update Redeem Code" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>
