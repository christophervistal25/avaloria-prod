<template>
  <layout>
    <div class="">
      <div class="mb-8">
        <div
          class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6"
        >
          <div>
            <h1 class="text-2xl font-bold text-gray-800">GCash Donate Setup</h1>
            <p class="text-sm text-gray-500 mt-1">Manage Game Gcash donates</p>
          </div>
          <button
            @click="openAddModal"
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
            Add New Donation
          </button>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50">
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Name
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Mobile Number
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Amount
                </th>
                <th
                  scope="col"
                  class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  DP Points
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
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Created By
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
                v-for="donate in donates"
                :key="donate.id"
                class="hover:bg-gray-50 transition-colors duration-200"
              >
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 uppercase"
                >
                  {{ donate.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ donate.mobile_number }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium"
                >
                  ₱{{ donate.amount }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium"
                >
                  {{ donate.equivalent_donate_points }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ donate.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-800': donate.is_active == 1,
                      'bg-red-100 text-red-800': donate.is_active != 1,
                    }"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full mr-1.5"
                      :class="{
                        'bg-green-500': donate.is_active == 1,
                        'bg-red-500': donate.is_active != 1,
                      }"
                    >
                    </span>
                    {{ donate.is_active == 1 ? "Active" : "Inactive" }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ donate.user_who_create?.username }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <button
                    @click="editDonate(donate)"
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
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tailwind Modal for Adding -->
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="showAddModal" class="fixed z-50 inset-0 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
        >
          <!-- Overlay -->
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
          </div>

          <!-- Modal -->
          <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full"
            >
              <!-- Rest of your modal content remains the same -->
              <!-- Header -->
              <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h3 class="text-xl font-semibold text-gray-900">Add New Donation</h3>
                  <button
                    @click="closeAddModal"
                    class="text-gray-400 hover:text-gray-500 transition-colors duration-200"
                  >
                    <svg
                      class="h-6 w-6"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Body -->
              <div class="bg-white px-6 py-4">
                <form @submit.prevent="submitNewGcashDonate" class="space-y-4">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label
                        for="name"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Name</label
                      >
                      <input
                        type="text"
                        id="name"
                        v-model="formData.name"
                        class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                        required
                      />
                    </div>
                    <div>
                      <label
                        for="mobile_number"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Mobile Number</label
                      >
                      <input
                        type="text"
                        id="mobile_number"
                        v-model="formData.mobile_number"
                        class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                        required
                      />
                    </div>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label
                        for="amount"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Amount</label
                      >
                      <div class="relative rounded-md shadow-sm">
                        <div
                          class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                          <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <input
                          type="number"
                          id="amount"
                          v-model="formData.amount"
                          class="block w-full pl-7 pr-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                          required
                        />
                      </div>
                    </div>
                    <div>
                      <label
                        for="equivalent_donate_points"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Donate Points</label
                      >
                      <input
                        type="number"
                        id="equivalent_donate_points"
                        v-model="formData.equivalent_donate_points"
                        class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                        required
                      />
                    </div>
                  </div>

                  <div>
                    <label
                      for="description"
                      class="block text-sm font-medium text-gray-700 mb-1"
                      >Description</label
                    >
                    <textarea
                      id="description"
                      v-model="formData.description"
                      rows="3"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white resize-none"
                    ></textarea>
                  </div>

                  <div class="flex items-center">
                    <input
                      type="checkbox"
                      v-model="formData.is_active"
                      class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3"
                    />
                    <label class="text-sm text-gray-700">Active Status</label>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                      >QR Code Upload</label
                    >
                    <div
                      class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-500 transition-colors duration-200"
                    >
                      <div class="space-y-1 text-center">
                        <svg
                          class="mx-auto h-12 w-12 text-gray-400"
                          stroke="currentColor"
                          fill="none"
                          viewBox="0 0 48 48"
                        >
                          <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                          <label
                            for="qr_path"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200"
                          >
                            <span>Upload a file</span>
                            <input
                              id="qr_path"
                              type="file"
                              class="sr-only"
                              @change="formData.qr_path = $event.target.files[0]"
                            />
                          </label>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Footer -->
              <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="closeAddModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    @click="submitNewGcashDonate"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Save Changes
                  </button>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </Transition>

    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <!-- Tailwind Modal for Editing -->
      <div v-if="showEditModal" class="fixed z-50 inset-0 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
        >
          <!-- Overlay -->
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
          </div>

          <!-- Modal -->
          <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full"
          >
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Edit Donation</h3>
                <button
                  @click="closeEditModal"
                  class="text-gray-400 hover:text-gray-500 transition-colors duration-200"
                >
                  <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Body -->
            <div class="bg-white px-6 py-4">
              <form @submit.prevent="submitEditGcashDonate" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label
                      for="edit_name"
                      class="block text-sm font-medium text-gray-700 mb-1"
                      >Name</label
                    >
                    <input
                      type="text"
                      id="edit_name"
                      v-model="editFormData.name"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                      required
                    />
                  </div>
                  <div>
                    <label
                      for="edit_mobile_number"
                      class="block text-sm font-medium text-gray-700 mb-1"
                      >Mobile Number</label
                    >
                    <input
                      type="text"
                      id="edit_mobile_number"
                      v-model="editFormData.mobile_number"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                      required
                    />
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label
                      for="edit_amount"
                      class="block text-sm font-medium text-gray-700 mb-1"
                      >Amount</label
                    >
                    <div class="relative rounded-md shadow-sm">
                      <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                      >
                        <span class="text-gray-500 sm:text-sm">₱</span>
                      </div>
                      <input
                        type="number"
                        id="edit_amount"
                        v-model="editFormData.amount"
                        class="block w-full pl-7 pr-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                        disabled
                        required
                      />
                    </div>
                  </div>
                  <div>
                    <label
                      for="edit_equivalent_donate_points"
                      class="block text-sm font-medium text-gray-700 mb-1"
                      >Donate Points</label
                    >
                    <input
                      type="number"
                      id="edit_equivalent_donate_points"
                      v-model="editFormData.equivalent_donate_points"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                      disabled
                      required
                    />
                  </div>
                </div>

                <div>
                  <label
                    for="edit_description"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Description</label
                  >
                  <textarea
                    id="edit_description"
                    v-model="editFormData.description"
                    rows="3"
                    class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white resize-none"
                  ></textarea>
                </div>

                <div class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="editFormData.is_active"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3"
                  />
                  <label class="text-sm text-gray-700">Active Status</label>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1"
                    >QR Code Upload</label
                  >
                  <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-500 transition-colors duration-200"
                  >
                    <div class="space-y-1 text-center">
                      <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <path
                          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>
                      <div class="flex text-sm text-gray-600">
                        <label
                          for="edit_qr_path"
                          class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200"
                        >
                          <span>Upload a file</span>
                          <input
                            id="edit_qr_path"
                            type="file"
                            class="sr-only"
                            @change="editFormData.qr_path = $event.target.files[0]"
                          />
                        </label>
                      </div>
                      <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="closeEditModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  @click="submitEditGcashDonate"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Save Changes
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </layout>
</template>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>

<script setup>
import { ref } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { router } from "@inertiajs/vue3";
import { Notyf } from "notyf";

const props = defineProps(["donates"]);

const formData = ref({
  id: null,
  name: "",
  mobile_number: "",
  amount: 0,
  equivalent_donate_points: 0,
  description: "",
  is_active: true,
  qr_path: null,
});

const editFormData = ref({
  id: null,
  name: "",
  mobile_number: "",
  amount: 0,
  equivalent_donate_points: 0,
  description: "",
  is_active: true,
  qr_path: null,
});

const showAddModal = ref(false);
const showEditModal = ref(false);

const openAddModal = () => {
  formData.value = {
    id: null,
    name: "",
    mobile_number: "",
    amount: 0,
    equivalent_donate_points: 0,
    description: "",
    is_active: true,
    qr_path: null,
  };
  showAddModal.value = true;
};

const closeAddModal = () => {
  showAddModal.value = false;
};

const openEditModal = () => {
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

const submitNewGcashDonate = () => {
  const fd = new FormData();
  fd.append("name", formData.value.name);
  fd.append("mobile_number", formData.value.mobile_number);
  fd.append("amount", formData.value.amount);
  fd.append("equivalent_donate_points", formData.value.equivalent_donate_points);
  fd.append("description", formData.value.description);
  fd.append("is_active", formData.value.is_active);
  fd.append("qr_path", formData.value.qr_path);

  axios
    .post(`/administrator-panel/gcash-donate-setup`, fd)
    .then((response) => {
      if (response.status === 201) {
        new Notyf().success("New GCash donation has been successfully added");
        router.visit(location.href, {
          preserveScroll: true,
          preserveState: true,
        });
      }
    })
    .catch((error) => {
      new Notyf().error("Error in submitting new gcash donate");
    });
};

const submitEditGcashDonate = () => {
  const fd = new FormData();
  fd.append("name", editFormData.value.name);
  fd.append("mobile_number", editFormData.value.mobile_number);
  fd.append("amount", editFormData.value.amount);
  fd.append("equivalent_donate_points", editFormData.value.equivalent_donate_points);
  fd.append("description", editFormData.value.description);
  fd.append("is_active", editFormData.value.is_active);
  fd.append("_method", "PUT");
  if (editFormData.value.qr_path) {
    fd.append("qr_path", editFormData.value.qr_path);
  }

  axios
    .post(`/administrator-panel/gcash-donate-setup/${editFormData.value.id}`, fd)
    .then((response) => {
      if (response.status === 200) {
        new Notyf().success("GCash donation details have been successfully updated");
        router.visit(location.href, {
          preserveScroll: true,
          preserveState: true,
        });
      }
    })
    .catch((error) => {
      new Notyf().error("Error in updating gcash donate");
    });
};

const editDonate = (donate) => {
  donate.is_active = donate.is_active == 1;
  editFormData.value = { ...donate, qr_path: null };
  openEditModal();
};
</script>
