<script setup>
import { ref, computed } from 'vue';
import Layout from '@/Pages/Layouts/Layout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  categories: {
    type: Array,
    required: true
  }
});

// Search functionality
const searchQuery = ref('');
const filteredCategories = computed(() => {
  if (!searchQuery.value) return props.categories;
  const query = searchQuery.value.toLowerCase();
  return props.categories.filter(category => 
    category.title.toLowerCase().includes(query) ||
    removeHtmlTags(category.description).toLowerCase().includes(query)
  );
});

// Category groups for better organization
const featuredCategories = computed(() => 
  filteredCategories.value.filter(cat => cat.is_featured)
);

const regularCategories = computed(() => 
  filteredCategories.value.filter(cat => !cat.is_featured)
);

// Helper functions
const removeHtmlTags = (str) => {
  return str ? str.replace(/<[^>]*>/g, '') : '';
};

const truncate = (text, length = 150) => {
  if (!text) return '';
  const cleaned = removeHtmlTags(text);
  return cleaned.length > length ? cleaned.substring(0, length) + '...' : cleaned;
};


</script>

<template>
  <Layout>
    <div class="min-h-screen bg-[#0A0A15] text-gray-100">
      <!-- Hero Section -->
      <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center space-x-2 bg-indigo-900/30 rounded-full px-3 py-1 mb-4">
              <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
              <span class="text-sm font-medium text-indigo-300">Knowledge Base</span>
            </div>
            <h1 class="text-5xl lg:text-6xl font-bold mb-6 tracking-tight">
              Avaloria
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400">
                Wikipedia
              </span>
            </h1>
            <p class="text-xl text-gray-400 mb-8 leading-relaxed">
              Discover comprehensive guides, item databases, monster information, and game mechanics 
              to help you on your adventures in Avaloria.
            </p>
            
            <!-- Search Bar -->
            <div class="mt-8 max-w-2xl mx-auto">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search the wiki..."
                  class="w-full py-4 px-6 pl-12 bg-gray-800/50 rounded-xl border border-gray-700/30 focus:border-indigo-500/50 outline-none transition-colors text-gray-100"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Background Effects -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px] animate-float-slow"></div>
            <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-violet-900/10 rounded-full blur-[120px] animate-float-slow-reverse"></div>
          </div>
          <div class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.02] mix-blend-overlay"></div>
        </div>
      </section>

      <!-- Featured Categories (if any) -->
      <section v-if="featuredCategories.length" class="py-12 bg-gray-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center mb-8">
            <div class="w-2 h-8 bg-indigo-500 rounded-full mr-4"></div>
            <h2 class="text-2xl font-bold text-white">Featured Guides</h2>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div 
              v-for="category in featuredCategories" 
              :key="category.id" 
              class="group relative overflow-hidden rounded-xl h-[280px] border border-indigo-500/20 shadow-lg shadow-indigo-500/5"
            >
              <!-- Background Image -->
              <img 
                :src="`/storage/${category.image || 'wiki/default-featured.jpg'}`" 
                :alt="category.title"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              
              <!-- Gradient Overlay -->
              <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/80 to-indigo-900/40"></div>
              
              <!-- Content -->
              <div class="absolute inset-0 p-6 flex flex-col justify-end">
                <div class="space-y-1 mb-4">
                  <div class="flex items-center">
                    <div class="px-2 py-1 rounded-md text-xs font-medium bg-indigo-500/80 text-white">
                      Featured
                    </div>
                    <span class="ml-2 text-xs text-indigo-300">
                      {{ category.items?.length || 0 }} articles
                    </span>
                  </div>
                  <h3 class="text-2xl font-bold text-white mt-2">{{ category.title }}</h3>
                  <p class="text-indigo-100 text-sm line-clamp-2">{{ truncate(category.description, 100) }}</p>
                </div>
                
                <Link 
                  :href="`/wiki/${category.id}`"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-500/50 rounded-lg font-medium text-sm text-white transition-colors duration-200"
                >
                  Explore Guide
                  <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Categories -->
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center mb-8">
            <div class="w-2 h-8 bg-indigo-500 rounded-full mr-4"></div>
            <h2 class="text-2xl font-bold text-white">Browse Encyclopedia</h2>
          </div>
          
          <!-- Categories Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="category in regularCategories" 
              :key="category.id"
              class="group bg-gray-800/30 border border-gray-700/30 hover:border-indigo-500/30 rounded-xl overflow-hidden transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-indigo-500/5"
            >
              <div class="relative h-48">
                <img 
                  v-if="category.image" 
                  :src="`/storage/${category.image}`" 
                  :alt="category.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                <div v-else class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                  <!-- Default icon based on category type -->
                  <div class="h-16 w-16 rounded-full bg-indigo-900/50 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    </svg>
                  </div>
                </div>
                
                <!-- Category Type Badge -->
                <div class="absolute top-3 left-3">
                  <div class="px-2 py-1 rounded-md text-xs font-medium bg-indigo-900/70 border border-indigo-500/30 text-indigo-300">
                    {{ category.items?.length || 0 }} entries
                  </div>
                </div>
              </div>
              
              <div class="p-5">
                <h3 class="text-xl font-bold mb-2 text-white group-hover:text-indigo-400 transition-colors">
                  {{ category.title }}
                </h3>
                <p class="text-gray-400 text-sm mb-5 line-clamp-3">
                  {{ truncate(category.description, 120) }}
                </p>
                
                <Link 
                  :href="`/wiki/${category.id}`"
                  class="inline-flex items-center justify-between w-full px-4 py-2 bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30 rounded-lg font-medium text-sm transition-colors duration-200 group"
                >
                  <span>Browse Collection</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredCategories.length === 0" class="text-center py-16 bg-gray-800/20 rounded-xl border border-gray-700/30 mt-8">
            <div class="max-w-md mx-auto">
              <div class="h-20 w-20 mx-auto rounded-full bg-indigo-900/30 flex items-center justify-center text-indigo-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h3 class="mt-4 text-xl font-medium text-white">No entries found</h3>
              <p class="mt-2 text-gray-400">
                {{ searchQuery ? 'No categories match your search. Try different keywords.' : 'Wiki entries will appear here once they are published.' }}
              </p>
              <button 
                v-if="searchQuery"
                @click="searchQuery = ''" 
                class="mt-4 px-4 py-2 bg-indigo-600/30 text-indigo-300 hover:bg-indigo-600/50 rounded-lg text-sm font-medium transition-colors"
              >
                Clear search
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<style scoped>
@keyframes float-slow {
  0%, 100% {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(-10px, -10px);
  }
}

@keyframes float-slow-reverse {
  0%, 100% {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(10px, 10px);
  }
}

.animate-float-slow {
  animation: float-slow 15s ease-in-out infinite;
}

.animate-float-slow-reverse {
  animation: float-slow-reverse 15s ease-in-out infinite;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>