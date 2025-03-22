<script setup>
import { Link } from "@inertiajs/vue3";
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
import Layout from "@layouts/Layout.vue";
import { ref } from "vue";
import moment from "moment";

const hoveredCard = ref(null);
const props = defineProps(["downloads"]);
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-[#0A0A0F] text-gray-100">
      <!-- Hero Section -->
      <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Left side text -->
            <div class="md:w-1/2 text-left md:pr-8">
              <h1
                class="text-5xl md:text-6xl font-extrabold text-white mb-6 tracking-tight"
              >
                Download
                <span class="relative inline-block">
                  <span
                    class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 bg-clip-text text-transparent"
                  >
                    Avaloria Flyff
                  </span>
                  <span
                    class="absolute -bottom-2 left-0 right-0 h-[2px] bg-gradient-to-r from-blue-500/0 via-blue-500/50 to-blue-500/0"
                  ></span>
                </span>
              </h1>
              <p class="text-lg text-gray-400 max-w-2xl leading-relaxed mb-6">
                Begin your adventure in the mystical world of Madrigal. Choose
                your download method and join thousands of players in a world of
                flight, fantasy, and friendship.
              </p>
            </div>

            <!-- Right side character illustration (placeholder) -->
            <div class="md:w-1/2 mt-8 md:mt-0 relative">
              <!-- Character image would go here -->
              <div
                class="relative z-10 transform hover:scale-105 transition-transform duration-700"
              >
                <img
                  src="https://avaloriaflyff.online/amos.png"
                  alt="Flyff Character"
                  class="max-w-full h-auto"
                  onerror="this.style.display='none'"
                />
              </div>
              <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-blue-500/20 rounded-full filter blur-3xl"
              ></div>
              <div
                class="absolute -top-10 -left-10 w-48 h-48 bg-purple-500/20 rounded-full filter blur-3xl"
              ></div>
            </div>
          </div>
        </div>
        <!-- Keep your existing animated background -->
      </section>

      <section class="relative py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Download Options Header -->
          <div class="mb-10 text-center">
            <h2 class="text-2xl font-bold text-white mb-2">
              Choose Your Download Method
            </h2>
            <p class="text-gray-400">
              Select the option that works best for your connection
            </p>
          </div>

          <!-- System Requirements Tab -->
          <div
            class="mb-10 p-4 rounded-xl bg-[#12141D]/80 border border-gray-800/50 backdrop-blur-xl"
          >
            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2 text-blue-400"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12zm-1-5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                  clip-rule="evenodd"
                />
              </svg>
              System Requirements
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div>
                <h4 class="font-semibold text-blue-400 mb-2">
                  Minimum Requirements:
                </h4>
                <ul class="space-y-1 text-gray-300">
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Operating System: Windows 7/8/10 (64-bit)
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Processor: Intel Core i3-3220 or AMD equivalent
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Memory: 4GB RAM
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Graphics: NVIDIA GeForce GTX 750 or equivalent
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Storage: 5GB available space
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Network: Broadband Internet connection
                  </li>
                </ul>
              </div>
              <div>
                <h4 class="font-semibold text-blue-400 mb-2">
                  Recommended Requirements:
                </h4>
                <ul class="space-y-1 text-gray-300">
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Operating System: Windows 10/11 (64-bit)
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Processor: Intel Core i5-8400 or AMD equivalent
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Memory: 8GB RAM
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Graphics: NVIDIA GeForce GTX 1060 or better
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Storage: 10GB SSD
                  </li>
                  <li class="flex items-start">
                    <svg
                      class="h-4 w-4 text-green-500 mt-0.5 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    Network: High-speed Internet connection
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Download Cards Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
              v-for="(option, index) in downloads"
              :key="index"
              class="group"
              @mouseenter="hoveredCard = index"
              @mouseleave="hoveredCard = null"
            >
              <div
                class="relative h-full overflow-hidden rounded-2xl bg-[#12141D]/80 p-8 border border-gray-800/50 transition-all duration-500 hover:border-blue-500/50 backdrop-blur-xl backdrop-saturate-150"
              >
                <!-- Keep your existing animated border effects -->
                <div
                  class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                >
                  <div
                    class="absolute top-0 left-0 w-[100px] h-[2px] bg-gradient-to-r from-blue-500 to-transparent"
                  ></div>
                  <div
                    class="absolute top-0 left-0 w-[2px] h-[100px] bg-gradient-to-b from-blue-500 to-transparent"
                  ></div>
                  <div
                    class="absolute bottom-0 right-0 w-[100px] h-[2px] bg-gradient-to-l from-blue-500 to-transparent"
                  ></div>
                  <div
                    class="absolute bottom-0 right-0 w-[2px] h-[100px] bg-gradient-to-t from-blue-500 to-transparent"
                  ></div>
                </div>

                <!-- Enhanced Download Card Content -->
                <div class="relative space-y-6">
                  <!-- Title and Description -->
                  <div>
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-4">
                        <div class="p-2 bg-blue-500/10 rounded-xl">
                          <!-- Flyff-specific icon based on download type -->
                          <svg
                            v-if="option.type === 'client'"
                            class="w-8 h-8 text-blue-400"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                          >
                            <path d="M12 2L2 19h20L12 2zm0 3l7 13H5l7-13z" />
                          </svg>
                          <svg
                            v-else
                            class="w-8 h-8 text-purple-400"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                          >
                            <path
                              d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"
                            />
                          </svg>
                        </div>
                        <div>
                          <h2 class="text-xl font-bold text-white">
                            {{ option.title }}
                          </h2>
                          <p class="text-sm text-gray-400">
                            {{ option.description }}
                          </p>
                        </div>
                      </div>
                      <span
                        class="px-3 py-1 bg-blue-500/10 text-blue-400 text-sm font-medium rounded-full"
                      >
                        {{ moment(option.created_at).format("MMM D, YYYY") }}
                      </span>
                    </div>
                  </div>

                  <!-- Additional Details -->
                  <div
                    class="grid grid-cols-2 gap-3 py-3 border-t border-b border-gray-800/50"
                  >
                    <div>
                      <div class="text-xs text-gray-500 mb-1">Type </div>
                      <div class="text-sm text-white font-medium">
                        {{ option.type.toUpperCase() }}
                      </div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500 mb-1">Version</div>
                      <div class="text-sm text-white font-medium">
                        {{ option.version || "v1.0.0" }}
                      </div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500 mb-1">Status</div>
                      <div class="flex items-center">
                        <span
                          class="h-2 w-2 bg-green-500 rounded-full mr-1.5"
                        ></span>
                        <span class="text-sm text-green-400 font-medium"
                          >Verified</span
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Improved Download Button -->
                  <a
                    :href="option.link"
                    target="_blank"
                    class="group flex items-center justify-center w-full px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl transition-all duration-300 border border-blue-500/20 hover:border-blue-400/40 shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 mr-2"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                      />
                    </svg>
                    <span>Download {{ option.type?.toUpperCase() }}</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 ml-2 transform transition-transform group-hover:translate-y-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"
                      />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="relative py-16 bg-[#0D0E15]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">
              Quick Setup Guide
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
              Follow these simple steps to get started with Avaloria Flyff
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div
              class="relative p-6 bg-[#12141D]/80 rounded-xl border border-gray-800/50 backdrop-blur-xl"
            >
              <div
                class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-blue-600 rounded-full text-white font-bold"
              >
                1
              </div>
              <h3 class="text-xl font-semibold text-white mb-3">
                Download & Install
              </h3>
              <p class="text-gray-400 mb-4">
                Download the client installer and run it. Follow the
                installation wizard to install the game.
              </p>
              <ul class="text-sm text-gray-400 space-y-2">
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Select your installation directory
                </li>
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Allow GB of free space
                </li>
              </ul>
            </div>

            <!-- Step 2 -->
            <div
              class="relative p-6 bg-[#12141D]/80 rounded-xl border border-gray-800/50 backdrop-blur-xl"
            >
              <div
                class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-blue-600 rounded-full text-white font-bold"
              >
                2
              </div>
              <h3 class="text-xl font-semibold text-white mb-3">
                Create Account
              </h3>
              <p class="text-gray-400 mb-4">
                Sign up for a free account on our website to access the game
                servers.
              </p>
              <ul class="text-sm text-gray-400 space-y-2">
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Register at our account portal
                </li>
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Sign in and create game credentials
                </li>
              </ul>
            </div>

            <!-- Step 3 -->
            <div
              class="relative p-6 bg-[#12141D]/80 rounded-xl border border-gray-800/50 backdrop-blur-xl"
            >
              <div
                class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-blue-600 rounded-full text-white font-bold"
              >
                3
              </div>
              <h3 class="text-xl font-semibold text-white mb-3">
                Play The Game
              </h3>
              <p class="text-gray-400 mb-4">
                Launch the game, log in with your account and start your
                adventure!
              </p>
              <ul class="text-sm text-gray-400 space-y-2">
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Create your first character
                </li>
                <li class="flex items-start">
                  <svg
                    class="h-4 w-4 text-blue-400 mt-0.5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  Begin your journey in Flaris
                </li>
              </ul>
            </div>
          </div>

          <!-- Need Help Section -->
          <div
            class="mt-12 p-6 bg-[#12141D]/80 rounded-xl border border-gray-800/50 backdrop-blur-xl text-center"
          >
            <h3 class="text-xl font-semibold text-white mb-3">Need Help?</h3>
            <p class="text-gray-400 mb-4">
              Encountering issues with download or installation? Check our guide
              or contact support.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
              <a
                href="#"
                class="inline-flex items-center justify-center px-4 py-2 border border-emerald-500 rounded-lg text-emerald-400 hover:bg-emerald-500/10 transition-all"
              >
                <svg
                  class="h-5 w-5 mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"
                  />
                </svg>
                Join Discord
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<style>
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