<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from 'axios';
const isSidebarOpen = ref(true);
const isProfileMenuOpen = ref(false);
const currentPath = computed(() => usePage().url);


const logout = () => {
    axios.post('https://avaloriaflyff.online/me/logout').then((response) => {
      location.reload();
    });
  }

// Enhanced navigation with better icons and organization
const navigation = [
  {
    name: "Dashboard",
    href: "/administrator-panel/dashboard",
    icon: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
    badge: {
      count: 5,
      color: "bg-blue-100 text-blue-800",
    },
  },
  {
    name: "Game Management",
    icon: "M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z",
    children: [
      {
        name: "Wikipedia",
        href: "/administrator-panel/wikipedia",
        badge: {
          count: "New",
          color: "bg-green-100 text-green-800",
        },
      },
      { name: "Vote Logs", href: "/administrator-panel/vote-logs" },
    ],
  },
  {
    name: "User Management",
    icon: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
    children: [
      { name: "Characters", href: "/administrator-panel/characters" },
      { name: "Accounts", href: "/administrator-panel/accounts" },
    ],
  },
  {
    name: "Donations",
    icon: "M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z",
    children: [
      {
        name: "PayPal Setup",
        href: "/administrator-panel/paypal-donate-setup",
      },
      { name: "GCash Setup", href: "/administrator-panel/gcash-donate-setup" },
      { name: "Donate Rankings", href: "/administrator-panel/donate-rankings" },
      { name: "Donate Vouchers", href: "/administrator-panel/donate-vouchers" },
    ],
  },
  {
    name: "Rewards",
    icon: "M20 7h-7L10 4H4a1 1 0 00-1 1v10a1 1 0 001 1h4l3 3h7a1 1 0 001-1V8a1 1 0 00-1-1zm-1 9h-6.172l-3 3H4V5h5.172l3 3H20v8z",
    children: [
      { name: "Redeem Codes", href: "/administrator-panel/redeem-codes" },
      { name: "Redeem History", href: "/administrator-panel/redeem-history" },
    ],
  },
  {
    name: "Content",
    icon: "M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z",
    children: [
      { name: "News", href: "/administrator-panel/news" },
      { name: "Downloads", href: "/administrator-panel/downloads" },
    ],
  },
];

const expandedItems = ref({});

const toggleDropdown = () => {
  isProfileMenuOpen.value = !isProfileMenuOpen.value;
};

const toggleDropdownSidebar = (itemName) => {
  expandedItems.value[itemName] = !expandedItems.value[itemName];
};

// Generate breadcrumbs
const breadcrumbs = computed(() => {
  const path = currentPath.value.split("/").filter(Boolean);
  return path.map((item, index) => ({
    name: item.charAt(0).toUpperCase() + item.slice(1),
    href: "/" + path.slice(0, index + 1).join("/"),
    current: index === path.length - 1,
  }));
});
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <!-- Logo Section -->
      <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
          <a href="/" class="flex items-center space-x-2">
            <img
              src="https://avaloriaflyff.online/images/logo.png"
              alt="Admin"
              class="h-8 w-8"
            />
            <span
              class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent"
            >
              ADMIN PANEL
            </span>
          </a>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="mt-4 flex flex-col h-[calc(100vh-12rem)]">
        <div class="flex-1 px-4 space-y-1 overflow-y-auto">
          <div v-for="item in navigation" :key="item.name" class="space-y-1">
            <!-- Menu Item -->
            <div
              v-if="item.children"
              @click="toggleDropdownSidebar(item.name)"
              :class="[
                'group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg cursor-pointer transition-all duration-200',
                expandedItems[item.name]
                  ? 'bg-blue-50 text-blue-700'
                  : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600',
              ]"
            >
              <div class="flex items-center">
                <svg
                  class="mr-3 h-5 w-5 flex-shrink-0"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="item.icon"
                  />
                </svg>
                {{ item.name }}
              </div>
              <svg
                class="h-5 w-5 transition-transform duration-200"
                :class="{ 'rotate-180': expandedItems[item.name] }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </div>

            <!-- Single Menu Item -->
            <Link
              v-else
              :href="item.href"
              :class="[
                'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200',
                currentPath === item.href
                  ? 'bg-blue-50 text-blue-700'
                  : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600',
              ]"
            >
              <svg
                class="mr-3 h-5 w-5 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="item.icon"
                />
              </svg>
              {{ item.name }}
            </Link>

            <!-- Dropdown Items -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="item.children && expandedItems[item.name]"
                class="pl-12 space-y-1 mt-1"
              >
                <Link
                  v-for="child in item.children"
                  :key="child.name"
                  :href="child.href"
                  :class="[
                    'block px-4 py-2 text-sm rounded-lg transition-colors duration-200',
                    currentPath === child.href
                      ? 'text-blue-700 bg-blue-50'
                      : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50',
                  ]"
                >
                  {{ child.name }}
                </Link>
              </div>
            </transition>
          </div>
        </div>
      </nav>
    </div>

    <!-- Main Content -->
    <div
      :class="[
        'lg:pl-64 flex flex-col min-h-screen',
        isSidebarOpen ? 'lg:pl-64' : 'lg:pl-0',
      ]"
    >
      <!-- Top Navigation -->
      <header
        class="h-16 bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30"
      >
        <div class="flex items-center justify-between h-full px-6">
          <!-- Left Side -->
          <div class="flex items-center space-x-4">
            <button
              @click="isSidebarOpen = !isSidebarOpen"
              class="lg:hidden text-gray-600 hover:text-gray-900 transition-colors duration-200"
            >
              <svg
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
            </button>

            <!-- Breadcrumbs -->
            <nav class="hidden md:flex" aria-label="Breadcrumb">
              <ol class="flex items-center space-x-2">
                <li
                  v-for="(crumb, index) in breadcrumbs"
                  :key="crumb.href"
                  class="flex items-center"
                >
                  <div v-if="index > 0" class="mx-2 text-gray-400">/</div>
                  <Link
                    :href="crumb.href"
                    :class="[
                      'text-sm font-medium',
                      crumb.current
                        ? 'text-blue-600'
                        : 'text-gray-500 hover:text-gray-700',
                    ]"
                  >
                    {{ crumb.name }}
                  </Link>
                </li>
              </ol>
            </nav>
          </div>

          <!-- Right Side -->
          <div class="flex items-center space-x-4">
            <!-- Profile Dropdown -->
            <div class="relative" @click="toggleDropdown()">
              <button
                class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 transition-colors duration-200"
              >
                <!-- <img class="h-8 w-8 rounded-lg object-cover" 
                                     src="/images/admin-avatar.jpg" 
                                     alt="Admin" /> -->
                <span class="font-medium">Admin</span>
                <svg
                  class="h-5 w-5 transition-transform duration-200"
                  :class="{ 'rotate-180': isProfileMenuOpen }"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <!-- Profile Menu -->
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div
                  v-show="isProfileMenuOpen"
                  class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-lg border border-gray-200 shadow-lg"
                >
                  <Link
                    href="/admin/profile"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600"
                  >
                    Profile Settings
                  </Link>
                  <div class="border-t border-gray-200 my-2"></div>
                  <button
                    @click="logout"
                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
                  >
                    Sign Out
                </button>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-x-hidden bg-gray-100">
        <!-- Page Header -->
        <div class="py-2 px-6">
          <slot name="header"></slot>
        </div>

        <!-- Content -->
        <div class="px-6 pb-6">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
  transition: background-color 0.2s;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Transitions */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.fade-enter-active {
  animation: fadeIn 0.2s ease-out;
}

/* Add new professional styles */
.menu-item-active {
  @apply bg-blue-50 text-blue-700 border-l-4 border-blue-500;
}

.menu-item-hover {
  @apply hover:bg-gray-50 hover:text-blue-600 transition-all duration-200;
}

.dropdown-item {
  @apply px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200;
}

.badge {
  @apply px-2 py-0.5 text-xs font-medium rounded-full;
}

/* Dark mode enhancements */
.dark {
  @apply bg-gray-900;
  color-scheme: dark;
}

.dark .menu-item-active {
  @apply bg-gray-800 text-blue-400 border-blue-400;
}

.dark .menu-item-hover {
  @apply hover:bg-gray-800 hover:text-blue-400;
}
</style>

