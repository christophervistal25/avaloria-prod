<script setup>
import { Link } from "@inertiajs/vue3";
import Layout from "@layouts/Layout.vue";
import { ref } from "vue";
import axios from "axios";
import { Notyf } from "notyf";
const form = ref({
  username: "",
  password: "",
});

const accountLogin = () => {
  axios
    .post("/login", form.value)
    .then((response) => {
      window.location.href = "/administrator-panel/dashboard";
    })
    .catch((error) => {
      new Notyf().error("Invalid credentials. Please try again.");
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

      <!-- Login Container -->
      <div class="max-w-lg w-full relative">
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
              AVALORIA FLYFF
            </span>
          </h2>
        </div>

        <!-- Login Panel -->
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
            <form class="space-y-6" @submit.prevent="accountLogin()">
              <!-- Username Input -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  EMAIL
                </label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="form.username"
                    class="block w-full px-4 py-3 rounded bg-[#0A0A15] border border-white/10 text-white placeholder-gray-600 focus:border-white/20 focus:ring-1 focus:ring-white/20 transition duration-200 focus:transition-all focus:duration-300 focus:ease-in-out"
                    placeholder="Enter your email address"
                  />
                </div>
              </div>

              <!-- Password Input -->
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                  PASSWORD
                </label>
                <div class="relative">
                  <input
                    type="password"
                    v-model="form.password"
                    class="block w-full px-4 py-3 rounded bg-[#0A0A15] border border-white/10 text-white placeholder-gray-600 focus:border-white/20 focus:ring-1 focus:ring-white/20 transition duration-200"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              <!-- Options -->
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="form.remember"
                    class="h-4 w-4 rounded bg-[#0A0A15] border-white/10 text-blue-500 focus:ring-white/20 focus:ring-offset-0"
                  />
                  <label class="ml-2 block text-sm text-gray-400"> Remember me </label>
                </div>
                <Link
                  href="/forgot-password"
                  class="text-sm text-gray-400 hover:text-white transition duration-200"
                >
                  Forgot password?
                </Link>
              </div>

              <!-- Login Button -->
              <button
                type="submit"
                class="w-full px-4 py-3 bg-gradient-to-r from-gray-800 to-gray-700 text-white rounded font-medium hover:from-gray-700 hover:to-gray-600 focus:outline-none focus:ring-2 focus:ring-white/10 focus:ring-offset-2 focus:ring-offset-[#080810] transition duration-200 relative group overflow-hidden border border-white/5"
              >
                <span class="relative z-10">SIGN IN</span>
                <div
                  class="absolute inset-0 bg-gradient-to-r from-white to-gray-100 opacity-0 group-hover:opacity-5 transition-opacity duration-200"
                ></div>
              </button>

              <!-- Register Link -->
              <div class="text-center">
                <p class="text-sm text-gray-500">
                  Don't have an account?
                  <Link
                    href="/register"
                    class="text-gray-300 hover:text-white font-medium transition duration-200"
                  >
                    Create one now
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
