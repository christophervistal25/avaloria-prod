<script setup>
import { defineComponent, ref } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { Notyf } from "notyf";

const props = defineProps(["donations"]);

const donate = ref({
  name: "",
  amount: 0,
  equivalent_donate_points: 0,
  description: "",
  is_active: true,
});

const editingDonate = ref(null);

// const submitDonation = () => {
//   const url = editingDonate.value
//     ? `/administrator-panel/paypal-donate-setup/${editingDonate.value.id}`
//     : "/administrator-panel/paypal-donate-setup";
//   const method = editingDonate.value ? "put" : "post";

//   axios[method](url, donate.value)
//     .then((response) => {
//       donate.value = {
//         name: "",
//         amount: "",
//         equivalent_donate_points: "",
//         description: "",
//         is_active: true,
//       };
//       editingDonate.value = null;
//       const modal = document.getElementById("addDonate");
//       const bootstrapModal = bootstrap.Modal.getInstance(modal);
//       bootstrapModal.hide();

//       router.visit(location.href);
//       new Notyf().success("Donation saved successfully!");
//     })
//     .catch((err) => {
//       if (err.response.status === 422) {
//         donate.value = {
//           ...donate.value,
//           ...err.response.data.errors,
//         };
//       }
//       new Notyf().error("Failed to save donation. Please check the form.");
//     });
// };


const showModal = ref(false);

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  donate.value = {
    name: "",
    amount: "",
    equivalent_donate_points: "",
    description: "",
    is_active: true,
  };
  editingDonate.value = null;
};

const submitDonation = () => {
  const url = editingDonate.value
    ? `/administrator-panel/paypal-donate-setup/${editingDonate.value.id}`
    : "/administrator-panel/paypal-donate-setup";
  const method = editingDonate.value ? "put" : "post";

  axios[method](url, donate.value)
    .then((response) => {
      closeModal();
      router.visit(location.href);
      new Notyf().success("Donation saved successfully!");
    })
    .catch((err) => {
      if (err.response.status === 422) {
        donate.value = {
          ...donate.value,
          ...err.response.data.errors,
        };
      }
      new Notyf().error("Failed to save donation. Please check the form.");
    });
};

const editDonate = (donation) => {
  donation.amount = parseInt(donation.amount);
  donation.is_active = donation.is_active == 1;
  donation.equivalent_donate_points = parseInt(donation.equivalent_donate_points);
  donate.value = { ...donation };
  editingDonate.value = donation;
  openModal();
};
</script>

<template>
  <layout>
    <div class="">

      <div class="mb-8">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Paypal Donates Setup</h1>
            <p class="text-sm text-gray-500 mt-1">Manage game paypal donates</p>
          </div>
          <button
              @click="openModal"
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 group"
          >
            <svg class="h-5 w-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
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
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">DP Points</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created By</th>
                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="donate in donations" :key="donate.id" class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ donate.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">
                  <span class="inline-flex items-center">
                    ₱{{ parseInt(donate.amount) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">
                  {{ parseInt(donate.equivalent_donate_points) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 uppercase">{{ donate.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="{
                          'bg-green-100 text-green-800': donate.is_active == 1,
                          'bg-red-100 text-red-800': donate.is_active != 1
                        }">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5"
                          :class="{
                            'bg-green-500': donate.is_active == 1,
                            'bg-red-500': donate.is_active != 1
                          }">
                    </span>
                    {{ donate.is_active == 1 ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ donate?.user_who_create?.username }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <button
                    @click="editDonate(donate)"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                  >
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal -->
      <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
    <div v-if="showModal" class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <!-- Overlay -->
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
          </div>

          <!-- Modal Panel -->
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
            <!-- Modal Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">{{ editingDonate ? "Edit Donation" : "Add New Donation" }}</h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Modal Body -->
            <div class="bg-white px-6 py-4">
              <form @submit.prevent="submitDonation" class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                  <input type="text" id="name" v-model="donate.name" 
                    class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white"
                    required />
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" id="amount" v-model="donate.amount"
                      :disabled="editingDonate"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white disabled:bg-gray-100"
                      required />
                  </div>
                  <div>
                    <label for="equivalent_donate_points" class="block text-sm font-medium text-gray-700 mb-1">Donate Points</label>
                    <input type="number" id="equivalent_donate_points" v-model="donate.equivalent_donate_points"
                      :disabled="editingDonate"
                      class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white disabled:bg-gray-100"
                      required />
                  </div>
                </div>

                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <textarea id="description" v-model="donate.description" rows="3"
                    class="block w-full px-4 py-2 rounded-md border-gray-300 bg-gray-50 text-gray-900 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white hover:bg-white resize-none"></textarea>
                </div>

                <div class="flex items-center">
                  <input type="checkbox" v-model="donate.is_active" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3" />
                  <label class="text-sm text-gray-700">Active Status</label>
                </div>
              </form>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
              <div class="flex justify-end space-x-3">
                <button type="button" @click="closeModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Cancel
                </button>
                <button type="submit" @click="submitDonation"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  {{ editingDonate ? "Update" : "Save Changes" }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </Transition>
    </div>
  </layout>
</template>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>