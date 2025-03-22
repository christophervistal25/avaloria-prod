<script setup>
import { ref } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Notyf } from "notyf";

// Add these new refs for the dialog
const showQuantityDialog = ref(false);
const codeQuantity = ref(1);
const generationError = ref(null);

const form = useForm({
  description: "",
  status: "active",
  expires_at: "",
  quantity: 1, // Add this field to the form
  details: [
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
  form.details.splice(index, 1);
};

// Add a function to show the quantity dialog
const showGenerationDialog = () => {
  generationError.value = null;
  showQuantityDialog.value = true;
};

// Modified submit function
const submit = () => {
  // Update the form quantity value before submission
  form.quantity = codeQuantity.value;

  form.post("/administrator-panel/redeem-codes", {
    onSuccess: () => {
      new Notyf().success({
        message: `${
          codeQuantity.value > 1 ? codeQuantity.value + " redeem codes" : "Redeem code"
        } created successfully`,
        duration: 4000,
      });
      showQuantityDialog.value = false;
      form.reset();
      codeQuantity.value = 1;
    },
    onError: () => {
      showQuantityDialog.value = false;
    },
  });
};
</script>

<template>
  <Layout>
    <div class="">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b bg-gray-50">
          <h2 class="text-2xl font-bold text-gray-800">Create New Redeem Code</h2>
          <p class="text-gray-600 mt-1">
            Fill in the details to create a new redemption code
          </p>
        </div>

        <form @submit.prevent="showGenerationDialog" class="p-8">
          <!-- Main Code Section -->
          <div class="space-y-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-1">
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

            <!-- display the status here -->
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
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-red-100 rounded-full p-1"
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
              {{ form.processing ? "Creating..." : "Create Redeem Code" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Quantity Dialog Modal -->
    <div
      v-if="showQuantityDialog"
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

        <!-- Modal panel -->
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-blue-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Generate Redeem Codes
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    How many copies of
                    <span class="font-semibold">{{ form.code }}</span> would you like to
                    generate?
                  </p>
                  <div class="mt-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700"
                      >Quantity</label
                    >
                    <input
                      type="number"
                      min="1"
                      max="100"
                      id="quantity"
                      v-model.number="codeQuantity"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Enter a value between 1 and 100
                    </p>
                  </div>
                  <p v-if="generationError" class="mt-2 text-sm text-red-600">
                    {{ generationError }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="submit"
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="form.processing || codeQuantity < 1"
            >
              {{ form.processing ? "Generating..." : "Generate Codes" }}
            </button>
            <button
              @click="showQuantityDialog = false"
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="form.processing"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
