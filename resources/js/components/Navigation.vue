<template>
    <header class="relative z-50 sticky top-0" :class="{ 'nav-scrolled': isScrolled }">
      <!-- Top border -->
      <div
        class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-600/30 to-transparent"
      ></div>
  
        <nav
          class="bg-gradient-to-b from-slate-950/95 to-slate-900/90 backdrop-blur-md border-b border-slate-800/50"
        >
  
          <div class="max-w-7xl mx-auto px-4">
            <div class="relative flex items-center justify-between h-20">
              <div class="flex items-center">
        <div class="flex items-center space-x-2 bg-slate-800/70 px-3 py-1 rounded-full border border-slate-700/50 mr-4">
          <div class="relative flex">
            <div 
              :class="[
                serverStatus.isOnline ? 'bg-emerald-500' : 'bg-red-500',
                'h-2.5 w-2.5 rounded-full'
              ]"
            ></div>
            <div 
              :class="[
                serverStatus.isOnline ? 'bg-emerald-500' : 'bg-red-500',
                'animate-ping absolute inline-flex h-2.5 w-2.5 rounded-full opacity-75'
              ]"
            ></div>
          </div>
          <span class="text-xs font-medium tracking-wide text-slate-300">
            {{ serverStatus.isOnline ? 'ONLINE' : 'OFFLINE' }}
          </span>
        </div>
  
        <!-- Logo moved inside this flex container -->
        <div class="flex-shrink-0 relative group">
          <Link class="flex items-center" href="/">
            <div class="relative w-40 md:w-48 h-16">
              <img
                alt="Avaloria Logo"
                src="https://avaloriaflyff.online/images/logo.png"
                class="object-contain drop-shadow-[0_0_10px_rgba(99,102,241,0.5)]"
              />
            </div>
          </Link>
        </div>
      </div>
            
  
            <!-- Mobile menu button -->
            <div class="flex md:hidden">
              <button
                @click="isMobileMenuOpen = !isMobileMenuOpen"
                class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-200 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
              >
                <span class="sr-only">Open main menu</span>
                <svg
                  :class="{
                    hidden: isMobileMenuOpen,
                    block: !isMobileMenuOpen,
                  }"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                </svg>
                <svg
                  :class="{
                    block: isMobileMenuOpen,
                    hidden: !isMobileMenuOpen,
                  }"
                  class="h-6 w-6"
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
            </div>
  
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
              <!-- Navigation items... (same as before) -->
              <Link
                v-for="item in navigationItems"
                :key="item.path"
                :href="item.path"
                class="relative group px-4 py-2 flex items-center text-sm font-medium tracking-wider"
                :class="[
                  $page.component === item.component
                    ? 'text-indigo-400'
                    : 'text-slate-400 hover:text-slate-200',
                ]"
              >
                <div
                  class="absolute inset-0 bg-indigo-500/5 rounded-sm opacity-0 group-hover:opacity-100 transform scale-95 group-hover:scale-100 transition-all duration-300"
                ></div>
                <span class="relative z-10">{{ item.name }}</span>
              </Link>
            </div>
  
            <!-- Desktop Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
              <!-- Register Button -->
              <Link
                v-if="!$page.props.isLoggedIn"
                href="/register"
                class="relative group px-6 py-2 rounded-sm overflow-hidden"
              >
                <!-- Background glow effect -->
                <div
                  class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-violet-600/20 opacity-0 group-hover:opacity-100 transform scale-x-0 group-hover:scale-x-100 transition-all duration-500 ease-out"
                ></div>
                <!-- Border gradient -->
                <div
                  class="absolute inset-0 border border-indigo-500/20 group-hover:border-indigo-500/50 transform scale-95 group-hover:scale-100 transition-all duration-300"
                ></div>
                <!-- Shine effect -->
                <div
                  class="absolute inset-0 translate-x-full group-hover:translate-x-[-180%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/10 to-transparent"
                ></div>
                <!-- Text -->
                <span
                  class="relative z-10 text-sm font-medium text-slate-300 group-hover:text-slate-100 tracking-wider transition-colors duration-300"
                >
                  REGISTER
                </span>
              </Link>
  
              <Link
                v-if="$page.props.isAdmin"
                href="/administrator-panel/dashboard"
                class="relative group px-6 py-2 rounded-sm overflow-hidden"
              >
                <!-- Background glow effect -->
                <div
                  class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-violet-600/20 opacity-0 group-hover:opacity-100 transform scale-x-0 group-hover:scale-x-100 transition-all duration-500 ease-out"
                ></div>
                <!-- Border gradient -->
                <div
                  class="absolute inset-0 border border-indigo-500/20 group-hover:border-indigo-500/50 transform scale-95 group-hover:scale-100 transition-all duration-300"
                ></div>
                <!-- Shine effect -->
                <div
                  class="absolute inset-0 translate-x-full group-hover:translate-x-[-180%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/10 to-transparent"
                ></div>
                <!-- Text -->
                <span
                  class="relative z-10 text-sm font-medium text-slate-300 group-hover:text-slate-100 tracking-wider transition-colors duration-300"
                >
                  ADMIN
                </span>
              </Link>
  
              <Link
                v-if="$page.props.isLoggedIn"
                href="/user-account/dashboard"
                class="relative group px-6 py-2 rounded-sm overflow-hidden"
              >
                <!-- Background glow effect -->
                <div
                  class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-violet-600/20 opacity-0 group-hover:opacity-100 transform scale-x-0 group-hover:scale-x-100 transition-all duration-500 ease-out"
                ></div>
                <!-- Border gradient -->
                <div
                  class="absolute inset-0 border border-indigo-500/20 group-hover:border-indigo-500/50 transform scale-95 group-hover:scale-100 transition-all duration-300"
                ></div>
                <!-- Shine effect -->
                <div
                  class="absolute inset-0 translate-x-full group-hover:translate-x-[-180%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/10 to-transparent"
                ></div>
                <!-- Text -->
                <span
                  class="relative z-10 text-sm font-medium text-slate-300 group-hover:text-slate-100 tracking-wider transition-colors duration-300"
                >
                  ACCOUNT
                </span>
              </Link>
  
              <!-- Login Button -->
              <Link
                v-if="!$page.props.isLoggedIn"
                href="/login"
                class="relative group px-6 py-2 rounded-sm overflow-hidden"
              >
                <!-- Background effect -->
                <div
                  class="absolute inset-0 bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-colors duration-300"
                ></div>
                <!-- Shine effect -->
                <div
                  class="absolute inset-0 translate-x-full group-hover:translate-x-[-180%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/10 to-transparent"
                ></div>
                <!-- Text -->
                <span
                  class="relative z-10 text-sm font-medium text-indigo-400 group-hover:text-indigo-300 tracking-wider transition-all duration-300"
                >
                  LOGIN
                </span>
              </Link>
  
              <button
                v-if="$page.props.isLoggedIn"
              
                @click="logout"
                class="relative group px-6 py-2 rounded-sm overflow-hidden"
              >
                <!-- Background effect -->
                <div
                  class="absolute inset-0 bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-colors duration-300"
                ></div>
                <!-- Shine effect -->
                <div
                  class="absolute inset-0 translate-x-full group-hover:translate-x-[-180%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/10 to-transparent"
                ></div>
                <!-- Text -->
                <span
                  class="relative z-10 text-sm font-medium text-indigo-400 group-hover:text-indigo-300 tracking-wider transition-all duration-300"
                >
                  LOGOUT
                </span>
              </button>
            </div>
          </div>
        </div>
  
        <!-- Mobile Menu -->
        <transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="transform opacity-0 scale-95"
          enter-to-class="transform opacity-100 scale-100"
          leave-active-class="transition duration-100 ease-in"
          leave-from-class="transform opacity-100 scale-100"
          leave-to-class="transform opacity-0 scale-95"
        >
          <div
            v-if="isMobileMenuOpen"
            class="md:hidden absolute top-full left-0 right-0 bg-slate-900/95 backdrop-blur-xl border-b border-slate-800"
          >
            <div class="px-2 pt-2 pb-3 space-y-1">
              <!-- Mobile Navigation Items -->
              <Link
                v-for="item in navigationItems"
                :key="item.path"
                :href="item.path"
                class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-200"
                :class="{
                  'text-indigo-400': $page.component === item.component,
                }"
                @click="isMobileMenuOpen = false"
              >
                {{ item.name }}
              </Link>
  
              <!-- Mobile Guides -->
              <div class="relative">
                <!-- <button
                                  @click="isGuidesOpen = !isGuidesOpen"
                                  class="flex justify-between items-center w-full px-3 py-2 text-base font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-md"
                              >
                                  <span>GUIDES</span>
                                  <svg
                                      class="w-4 h-4 ml-1 transform transition-transform duration-200"
                                      :class="{ 'rotate-180': isGuidesOpen }"
                                      fill="none"
                                      stroke="currentColor"
                                      viewBox="0 0 24 24"
                                  >
                                      <path
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 9l-7 7-7-7"
                                      />
                                  </svg>
                              </button> -->
  
                <!-- Mobile Guides Dropdown -->
                <!-- <div v-if="isGuidesOpen" class="mt-2 pl-4">
                                  <Link
                                      v-for="guide in guides"
                                      :key="guide.path"
                                      :href="guide.path"
                                      class="block px-3 py-2 text-base font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800 rounded-md"
                                      @click="isMobileMenuOpen = false"
                                  >
                                      {{ guide.name }}
                                  </Link>
                              </div> -->
              </div>
            </div>
  
            <!-- Mobile Auth Buttons -->
            <div class="pt-4 pb-3 border-t border-slate-800">
              <div class="px-2 space-y-1">
                <Link
                  href="/register"
                  class="block px-3 py-2 rounded-md text-base font-medium text-indigo-400 hover:text-indigo-300 relative overflow-hidden group transition-all duration-300"
                  @click="isMobileMenuOpen = false"
                >
                  <!-- Background effect for mobile -->
                  <div
                    class="absolute inset-0 bg-indigo-500/10 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  ></div>
                  <span class="relative z-10">REGISTER</span>
                </Link>
                <Link
                  href="/login"
                  class="block px-3 py-2 rounded-md text-base font-medium text-slate-400 hover:text-slate-200 relative overflow-hidden group transition-all duration-300"
                  @click="isMobileMenuOpen = false"
                >
                  <!-- Background effect for mobile -->
                  <div
                    class="absolute inset-0 bg-slate-800/50 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  ></div>
                  <span class="relative z-10">LOGIN</span>
                </Link>
              </div>
            </div>
          </div>
        </transition>
      </nav>
  
      <!-- Bottom border -->
      <div
        class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-600/30 to-transparent"
      ></div>
    </header>
  </template>
  
  <script setup>
  import { ref, watch, onMounted, onBeforeUnmount } from "vue";
  import { Link, usePage } from "@inertiajs/vue3";
  import axios from 'axios';
  
  const isMobileMenuOpen = ref(false);
  const isGuidesOpen = ref(false);
  const isScrolled = ref(false);
  const page = usePage();
  
  // Scroll handler
  const handleScroll = () => {
    isScrolled.value = window.scrollY > 0;
  };
  
  // Lifecycle hooks for scroll listener
  onMounted(() => {
    window.addEventListener("scroll", handleScroll);
  });
  
  onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
  });
  
  // Add the watch here
  watch(isMobileMenuOpen, (value) => {
    document.body.classList.toggle("menu-open", value);
  });
  
  const navigationItems = [
    { name: "HOME", path: "/", component: "Home" },
    { name: "DOWNLOAD", path: "/download", component: "Download" },
    { name: "RANKINGS", path: "/rankings", component: "Rankings" },
    { name: "WIKIPEDIA", path: "/wiki", component: "Wikipedia" },
    { name: "NEWS", path: "/news", component: "News" },
  ];
  
  if (page.props.isLoggedIn) {
    navigationItems.push({ name: "DONATES", path: "/donates", component: "Donates" });
  }
  

  const logout = () => {
    axios.post('https://avaloriaflyff.online/me/logout').then((response) => {
      location.reload();
    });
  }
  // const guides = [
  //     { name: "Newbie Guide", path: "/guides/newbie" },
  //     { name: "Leveling Guide", path: "/guides/leveling" },
  //     { name: "Class Guide", path: "/guides/classes" },
  // ];
  const serverStatus = ref({
    isOnline: page.props.state,
  });
  </script>
  
  <style scoped>
  /* Optional: Add hover animation for nav items */
  .group:hover .transform {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  /* Optional: Add subtle text shadow to the logo */
  .text-transparent {
    text-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
  }
  
  /* Prevent body scroll when mobile menu is open */
  :deep(body.menu-open) {
    overflow: hidden;
  }
  
  .group:hover .translate-x-full {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  /* Optional: Add glow effect to register button on hover */
  .group:hover .border-indigo-500\/50 {
    box-shadow: 0 0 20px theme("colors.indigo.500" / 15%);
  }
  </style>
  