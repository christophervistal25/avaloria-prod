<script setup>
import { Link } from "@inertiajs/vue3";
import Layout from "@layouts/Layout.vue";
import { ref } from "vue";
import { Notyf } from "notyf";

const errors = ref([]);
const form = ref({
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});
const loading = ref(false);

const submitRegister = () => {
  loading.value = true;
  axios
    .post("/register", form.value)
    .then((response) => {
      if (response.status === 200) {
        new Notyf().success({
          message: "Account created successfully! Please wait while redirecting...",
          duration: 3000,
        });
        setTimeout(() => {
          window.location.href = "/user-account/dashboard";
        }, 3000);
      }
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = Object.values(error.response.data.errors).flat();
      } else {
        new Notyf().error({
          message: "An error occurred. Please try again.",
          duration: 3000,
        });
      }
    })
    .finally(() => {
      loading.value = false;
    });
};
</script>

<template>
  <Layout>
    <div
      class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative"
    >
      <!-- Animated Background Effects -->
      <div class="absolute inset-0 overflow-hidden">
        <div
          class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-900/10 via-transparent to-transparent animate-pulse-slow"
        ></div>
        <div
          class="absolute inset-0 bg-[url('https://static.wixstatic.com/media/cce79b_5477a9a1a0d4475aa2c956eb9a6c2f94~mv2.png/v1/fill/w_980,h_551,al_c,q_90,usm_0.66_1.00_0.01,enc_auto/cce79b_5477a9a1a0d4475aa2c956eb9a6c2f94~mv2.png')] bg-cover bg-center opacity-5"
        ></div>
        <div
          class="absolute inset-0 bg-[conic-gradient(from_90deg_at_50%_50%,#0A0A0F_0%,#1E2646_50%,#0A0A0F_100%)] opacity-50"
        ></div>
      </div>

      <!-- Register Container -->
      <div class="max-w-xl w-full relative">
        <!-- Game Logo -->
        <div class="text-center mb-8">
          <img
            src="https://avaloriaflyff.online/images/logo.png"
            alt="Avaloria Flyff"
            class="h-24 mx-auto mb-4"
          />
          <h2 class="text-4xl font-bold text-white tracking-tight">
            <span
              class="bg-gradient-to-r from-gray-100 via-white to-gray-100 bg-clip-text text-transparent"
            >
              JOIN THE ADVENTURE
            </span>
          </h2>
          <p class="mt-2 text-gray-400">Create your account and begin your journey</p>
        </div>

        <!-- Register Panel -->
        <div class="relative">
          <!-- Decorative Corners -->
          <div
            class="absolute -top-2 -left-2 w-6 h-6 border-t-2 border-l-2 border-white/10"
          ></div>
          <div
            class="absolute -top-2 -right-2 w-6 h-6 border-t-2 border-r-2 border-white/10"
          ></div>
          <div
            class="absolute -bottom-2 -left-2 w-6 h-6 border-b-2 border-l-2 border-white/10"
          ></div>
          <div
            class="absolute -bottom-2 -right-2 w-6 h-6 border-b-2 border-r-2 border-white/10"
          ></div>

          <!-- Main Panel -->
          <div
            class="bg-[#080810]/95 backdrop-blur-xl backdrop-saturate-150 rounded-lg p-8 border border-white/10 shadow-[0_0_20px_rgba(255,255,255,0.03)]"
          >
            <!-- Errors -->
            <div v-if="errors.length > 0" class="mb-6">
              <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                role="alert"
              >
                <strong class="font-bold">Oops! Something went wrong:</strong>
                <ul class="list-disc list-inside mt-2">
                  <li v-for="error in errors" :key="error">
                    {{ error }}
                  </li>
                </ul>
              </div>
            </div>

            <form class="space-y-6" @submit.prevent="submitRegister">
              <!-- Email Input -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  EMAIL ADDRESS
                </label>
                <div class="relative">
                  <input
                    type="email"
                    v-model="form.email"
                    class="block w-full px-4 py-3 rounded bg-[#0A0A15] border border-white/10 text-white placeholder-gray-600 focus:border-white/20 focus:ring-1 focus:ring-white/20 transition duration-200"
                    placeholder="Enter your email address"
                  />
                </div>
              </div>

              <!-- Password Fields -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    PASSWORD
                  </label>
                  <input
                    type="password"
                    v-model="form.password"
                    class="block w-full px-4 py-3 rounded bg-[#0A0A15] border border-white/10 text-white placeholder-gray-600 focus:border-white/20 focus:ring-1 focus:ring-white/20 transition duration-200"
                    placeholder="••••••••"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">
                    CONFIRM PASSWORD
                  </label>
                  <input
                    type="password"
                    v-model="form.password_confirmation"
                    class="block w-full px-4 py-3 rounded bg-[#0A0A15] border border-white/10 text-white placeholder-gray-600 focus:border-white/20 focus:ring-1 focus:ring-white/20 transition duration-200"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              <!-- Terms Checkbox -->
              <div class="flex items-start space-x-3">
                <div class="flex items-center h-5">
                  <input
                    type="checkbox"
                    v-model="form.terms"
                    class="h-4 w-4 rounded bg-[#0A0A15] border-white/10 text-blue-500 focus:ring-white/20 focus:ring-offset-0"
                  />
                </div>
                <label class="text-sm text-gray-400">
                  I agree to the
                  <Link href="/terms" class="text-blue-400 hover:text-blue-300"
                    >Terms of Service</Link
                  >
                  and
                  <Link href="/privacy" class="text-blue-400 hover:text-blue-300"
                    >Privacy Policy</Link
                  >
                </label>
              </div>

              <!-- Register Button -->
              <button
                type="submit"
                :disabled="loading"
                class="w-full px-4 py-3 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded font-medium hover:from-gray-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500/50 focus:ring-offset-2 focus:ring-offset-[#080810] transition duration-200 relative group overflow-hidden border border-gray-500/10"
              >
                <span v-if="!loading" class="relative z-10">CREATE ACCOUNT</span>
                <span v-else class="relative z-10">
                  <svg
                    class="animate-spin h-5 w-5 text-white mx-auto"
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
                </span>
                <div
                  class="absolute inset-0 bg-gradient-to-r from-white to-transparent opacity-0 group-hover:opacity-5 transition-opacity duration-200"
                ></div>
              </button>

              <!-- Login Link -->
              <div class="text-center">
                <p class="text-sm text-gray-500">
                  Already have an account?
                  <Link
                    href="/login"
                    class="text-gray-300 hover:text-white font-medium transition duration-200"
                  >
                    Sign in instead
                  </Link>
                </p>
              </div>
            </form>
          </div>
        </div>

        <!-- Game Info -->
        <div class="mt-8 text-center space-y-2">
          <p class="text-sm text-gray-500">Recommended: Chrome / Firefox / Edge</p>
          <p class="text-xs text-gray-600">© 2025 Avaloria Flyff. All rights reserved.</p>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.animate-pulse-slow {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 0.1;
  }
  50% {
    opacity: 0.3;
  }
}
</style>
