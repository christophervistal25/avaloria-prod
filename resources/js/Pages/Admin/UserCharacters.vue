<script setup>
import Layout from "@layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';

const props = defineProps(["account"]);
const openCharacters = ref(new Set());

const toggleCollapse = (id) => {
  if (openCharacters.value.has(id)) {
    openCharacters.value.delete(id);
  } else {
    openCharacters.value.add(id);
  }
};
</script>

<template>
  <layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Character Management</h1>
      <p class="mt-2 text-sm text-gray-600">Manage and view detailed information about characters.</p>
      </div>

      <!-- Characters List -->
      <div class="space-y-6">
      <div v-for="character in account?.characters" 
         :key="character.m_idPlayer"
         class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-blue-300 transition-colors">
        <!-- Character Header -->
        <div @click="toggleCollapse(character.m_idPlayer)"
           class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-all">
        <div class="flex items-center space-x-4">
          <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
          <span class="text-blue-600 font-semibold text-lg">{{ character.m_szName[0] }}</span>
          </div>
          <div>
          <h3 class="text-lg font-medium text-gray-900">{{ character.m_szName }}</h3>
          <div class="flex items-center space-x-2">
            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Level {{ character.m_nLevel }}</span>
            <span class="text-sm text-gray-500">ID: {{ character.m_idPlayer }}</span>
          </div>
          </div>
        </div>
        <svg class="w-5 h-5 text-blue-500 transition-transform duration-200"
           :class="{ 'rotate-180': openCharacters.has(character.m_idPlayer) }"
           xmlns="http://www.w3.org/2000/svg" 
           viewBox="0 0 20 20" 
           fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        </div>
        
        <!-- Character Details -->
        <div class="transition-all duration-300 overflow-hidden"
           :class="{ 'max-h-0 opacity-0': !openCharacters.has(character.m_idPlayer), 'max-h-[2000px] opacity-100': openCharacters.has(character.m_idPlayer) }">
        <div class="border-t border-gray-200">
          <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Basic Information -->
          <div class="bg-gray-50 p-6 rounded-lg space-y-4">
            <h4 class="font-medium text-gray-900 flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="font-bold">Basic Information</span>
            </h4>
            <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-sm text-gray-600">Account</span>
              <span class="text-sm font-medium text-gray-900">{{ character.account }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-sm text-gray-600">Job</span>
              <span class="text-sm font-medium text-gray-900">{{ character.m_nJob }}</span>
            </div>
            </div>
          </div>

          <!-- Stats -->
          <div class="bg-gray-50 p-6 rounded-lg space-y-4">
            <h4 class="font-medium text-gray-900 flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span class="font-bold">Character Stats</span>
            </h4>
            <div class="grid grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg text-center shadow-sm">
              <div class="text-lg font-semibold text-blue-600">{{ character.m_nSTR }}</div>
              <div class="text-xs font-medium text-gray-500">STR</div>
            </div>
            <div class="bg-white p-4 rounded-lg text-center shadow-sm">
              <div class="text-lg font-semibold text-green-600">{{ character.m_nDEX }}</div>
              <div class="text-xs font-medium text-gray-500">DEX</div>
            </div>
            <div class="bg-white p-4 rounded-lg text-center shadow-sm">
              <div class="text-lg font-semibold text-purple-600">{{ character.m_nINT }}</div>
              <div class="text-xs font-medium text-gray-500">INT</div>
            </div>
            </div>
          </div>

          <!-- Time Information -->
          <div class="bg-gray-50 p-6 rounded-lg space-y-4">
            <h4 class="font-medium text-gray-900 flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold">Time Information</span>
            </h4>
            <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-sm text-gray-600">Created</span>
              <span class="text-sm font-medium text-gray-900">{{ character.CreateTime }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-sm text-gray-600">Play Time</span>
              <span class="text-sm font-medium text-gray-900">{{ character.TotalPlayTime }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-sm text-gray-600">Guild Member</span>
              <span class="text-sm font-medium text-gray-900">{{ character.m_tGuildMember }}</span>
            </div>
            </div>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
  </layout>
</template>
