<script setup>
import { ref, watchEffect, watch  } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import moment from 'moment';
import debounce from "lodash/debounce";

const props = defineProps(["characters", "currentPage", "search", "nameSearch"]);
const searchCharacter = ref(props.nameSearch || ''); 

// Enhanced time formatter
const formatPlayTime = (time) => {
  const hours = Math.floor(time / 3600);
  const minutes = Math.floor((time % 3600) / 60);
  const seconds = Math.floor(time % 60);
  return {
    hours,
    minutes,
    seconds,
    formatted: `${hours}h ${minutes}m ${seconds}s`
  };
};

// Debounced search function for character name
const performSearch = debounce(() => {
  const search = searchCharacter.value;
  router.visit(`/administrator-panel/characters?page=1${search ? `&search=${search}` : ''}`, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
}, 500);

// Watch for changes to search inputs
watch(searchCharacter, performSearch);


const renderPage = (page) => {
  router.visit(page.url, {
    preserveScroll: true,
    preserveState: true,
  });
};

const getJobName = (jobId) => {
  const jobClasses = {
    0: 'Vagrant',
    // First Jobs
    1: 'Mercenary',
    2: 'Acrobat',
    3: 'Assist',
    4: 'Magician',
    // Second Jobs
    6: 'Blade',
    7: 'Knight',
    8: 'Jester',
    9: 'Ranger',
    10: 'Ringmaster',
    11: 'Billposter',
    12: 'Psykeeper',
    13: 'Elementor',
    // Master Jobs
    16: 'Master Blade',
    17: 'Master Knight',
    18: 'Master Jester',
    19: 'Master Ranger',
    20: 'Master Ringmaster',
    21: 'Master Billposter',
    22: 'Master Psykeeper',
    23: 'Master Elementor',
    // Hero Jobs
    24: 'Hero Blade',
    25: 'Hero Knight',
    26: 'Hero Jester',
    27: 'Hero Ranger',
    28: 'Hero Ringmaster',
    29: 'Hero Billposter',
    30: 'Hero Psykeeper',
    31: 'Hero Elementor',
    // Third Jobs (Awakening System)
    32: 'Templar (Knight Hero)',
    33: 'Slayer (Blade Hero)',
    34: 'Harlequin (Jester Hero)',
    35: 'Crackshooter (Ranger Hero)',
    36: 'Seraph (Ringmaster Hero)',
    37: 'Force Master (Billposter Hero)',
    38: 'Mentalist (Psykeeper Hero)',
    39: 'Arcanist (Elementor Hero)'
  };

  return jobClasses[jobId] || `Unknown (${jobId})`;
};

watchEffect(() => {
  if (props.nameSearch) {
    searchCharacter.value = props.nameSearch;
  }
});
</script>

<template>
  <layout>
    <h1 class="text-2xl font-semibold text-gray-900">Characters</h1>
    <p class=" text-sm text-gray-600">View all characters from the game!r</p>
    <div class="mb-2">
    </div>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="">
        <div class="mt-4 mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
      <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div>
          <label for="character-search" class="block text-sm font-medium text-gray-700 mb-1">Character Name</label>
          <div class="relative rounded-md shadow-sm">
            <input
              type="text"
              id="character-search"
              v-model="searchCharacter"
              class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md bg-white/50 backdrop-blur-sm transition-all duration-200 ease-in-out hover:bg-white hover:shadow-md focus:bg-white focus:shadow-lg p-2 border"
              placeholder="Search by character name..."
            />
            <div v-if="searchCharacter" class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <button @click="searchCharacter = ''" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      
    </div>
        <!-- Enhanced Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Character
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Level & Job
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Play Time
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Created
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="character in characters.data" 
                    :key="character.m_idPlayer" 
                    class="hover:bg-gray-50/50 transition-all duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 
                                  flex items-center justify-center shadow-sm">
                          <span class="text-sm font-semibold text-white">
                            {{ character.m_szName.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900">{{ character.m_szName }}</div>
                        <div class="text-xs text-gray-500">ID: #{{ character.m_idPlayer }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col">
                      <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                 bg-blue-100 text-blue-800 w-fit">
                        Level {{ character.m_nLevel }}
                      </span>
                      <span class="mt-1 text-xs text-gray-500">{{ getJobName(character.m_nJob) }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center text-sm text-gray-900">
                      <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ formatPlayTime(character.TotalPlayTime).formatted }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">
                      {{ moment(character.CreateTime).format('MMM D, YYYY') }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Enhanced Pagination -->
          <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium 
                                  rounded-md text-gray-700 bg-white hover:bg-gray-50">
                  Previous
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm 
                                  font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                  Next
                </a>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ characters.from }}</span>
                    to
                    <span class="font-medium">{{ characters.to }}</span>
                    of
                    <span class="font-medium">{{ characters.total }}</span>
                    results
                  </p>
                </div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <template v-for="page in characters.links" :key="page">
                    <a
                      @click="renderPage(page)"
                      :class="[
                        page.label === currentPage
                          ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer'
                      ]"
                      v-html="page.label"
                    ></a>
                  </template>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<style scoped>
.bg-grid-white\/10 {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(255 255 255 / 0.1)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}

.cursor-pointer {
  cursor: pointer;
}
</style>