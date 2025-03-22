<script setup>
import axios from "axios";
import { defineComponent, ref } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { Notyf } from "notyf";

const page = usePage();
const props = defineProps(["info"]);

const account = ref({
  username: props.info.account,
  email: props.info.account_detail?.email,
  password: "",
  password_confirmation: "",
});

const isSubmit = ref(false);

const updateAccount = async () => {
  isSubmit.value = true;
  try {
    let response = await axios.put(`/profile-update`, account.value);
    isSubmit.value = false;
    if (response.status === 200) {
      new Notyf().success(response.data.message);
    }
    if (response.data.status === "success") {
      alert(response.data.message);
    }
  } catch (error) {
    isSubmit.value = false;
    console.log(error);
  }
};
</script>
<template>
  <layout>
    <div class="card bg-dark">
      <div
        class="card-header rounded-0 fw-bold text-white border border-warning p-3 border-top-0 border-start-0 border-end-0"
        style="letter-spacing: 1.2px"
      >
        <h5
          style="
            text-shadow: 1px 1px 0 #575454, -1px -1px 0 #575454, 1px -1px 0 #575454,
              -1px 1px 0 #575454, 1px 1px 0 #575454;
          "
        >
          PROFILE
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="updateAccount">
          <div>
            <div class="form-group mb-2">
              <label for="name" class="text-white">Username</label>
              <input
                type="text"
                class="form-control bg-dark text-white border border-warning shadow-none"
                :value="account.username"
                readonly
              />
            </div>

            <div class="form-group mt-2 mb-2">
              <label for="name" class="text-white">Email</label>
              <input
                type="text"
                class="form-control bg-dark text-white border border-warning shadow-none"
                :value="account.email"
                readonly
              />
            </div>

            <div class="form-group mt-2">
              <label for="name" class="text-white">New Password</label>
              <input
                type="password"
                v-model="account.password"
                class="form-control bg-dark text-white border border-warning shadow-none"
              />
            </div>

            <div class="form-group mt-2">
              <label for="name" class="text-white">Confirm New Password</label>
              <input
                type="password"
                v-model="account.password_confirmation"
                class="form-control bg-dark text-white border border-warning shadow-none"
              />
            </div>

            <div class="form-group mt-2">
              <button
                class="btn btn-warning float-end border border-warning text-white"
                type="submit"
                :disabled="isSubmit"
              >
                <span v-if="isSubmit" class="spinner-border spinner-border-sm"></span>
                <span v-else>Update</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>
