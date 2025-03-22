<script setup>
import { Link } from "@inertiajs/vue3";
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
import Layout from "@layouts/Layout.vue";
import { ref, onMounted, computed } from "vue";
import moment from "moment";

// Props should contain news array instead of downloads
const props = defineProps({
  news: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

// News types with professional labels
const newsType = {
  0: "Announcement",
  1: "Event",
  2: "Update",
  3: "Promotion",
  4: "Guide",
  5: "Maintenance"
};

// Active category filter
const activeFilter = ref('all');

// Format dates in a professional way
const formatDate = (dateString) => {
  return moment(dateString).format('MMM DD, YYYY');
};

// Filter news by category
const filteredNews = computed(() => {
  if (activeFilter.value === 'all') {
    return props.news;
  }
  return props.news.filter(item => item.type == parseInt(activeFilter.value));
});

// Carousel navigation
const scrollContainer = ref(null);
const scrollLeft = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({ left: -800, behavior: 'smooth' });
  }
};
const scrollRight = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({ left: 800, behavior: 'smooth' });
  }
};

// Optional: Auto-scroll functionality
const autoScrollEnabled = ref(false);
let autoScrollInterval = null;

const toggleAutoScroll = () => {
  autoScrollEnabled.value = !autoScrollEnabled.value;
  if (autoScrollEnabled.value) {
    autoScrollInterval = setInterval(() => {
      scrollRight();
    }, 5000);
  } else {
    clearInterval(autoScrollInterval);
  }
};

onMounted(() => {
  // Optional: Initialize any carousel libraries or functionality here
});
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-[#0A0A15] text-gray-100">
      <!-- Hero Section -->
      <section class="py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center space-x-2 bg-indigo-900/30 rounded-full px-3 py-1 mb-4">
              <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
              <span class="text-sm font-medium text-indigo-300">Community</span>
            </div>
            <h1 class="text-5xl lg:text-6xl font-bold mb-6 tracking-tight">
              Latest 
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400">
                News & Updates
              </span>
            </h1>
            <p class="text-xl text-gray-400 mb-8 leading-relaxed">
              Stay informed about the latest happenings in Avaloria Flyff with news on events, 
              updates, maintenance, and community activities.
            </p>
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

      <!-- News Filters -->
      <section class="py-6 border-y border-gray-800/50 bg-gray-900/20 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-wrap items-center justify-center gap-4">
            <button 
              @click="activeFilter = 'all'" 
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
              :class="activeFilter === 'all' ? 'bg-indigo-500 text-white' : 'bg-gray-800/50 text-gray-300 hover:bg-gray-800'"
            >All News</button>
            
            <button 
              v-for="(label, type) in newsType" 
              :key="type"
              @click="activeFilter = type"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
              :class="activeFilter == type ? 'bg-indigo-500 text-white' : 'bg-gray-800/50 text-gray-300 hover:bg-gray-800'"
            >{{ label }}</button>
          </div>
        </div>
      </section>

      <!-- News Carousel Section -->
      <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Carousel Navigation -->
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-white">{{ activeFilter === 'all' ? 'All News' : newsType[activeFilter] }}</h2>
            <div class="flex items-center space-x-4">
              <button 
                @click="toggleAutoScroll" 
                class="flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-medium transition-all"
                :class="autoScrollEnabled ? 'bg-indigo-500/20 text-indigo-300' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800'"
              >
                <span>Auto-scroll</span>
                <div class="w-3 h-3 rounded-full" :class="autoScrollEnabled ? 'bg-indigo-400' : 'bg-gray-600'"></div>
              </button>
              
              <div class="flex space-x-2">
                <button 
                  @click="scrollLeft" 
                  class="p-2 rounded-full bg-gray-800/50 text-gray-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition-colors"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <button 
                  @click="scrollRight" 
                  class="p-2 rounded-full bg-gray-800/50 text-gray-300 hover:bg-indigo-500/20 hover:text-indigo-300 transition-colors"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Carousel Container -->
          <div 
            ref="scrollContainer" 
            class="carousel-container flex overflow-x-auto snap-x snap-mandatory hide-scrollbar gap-6 pb-6"
          >
            <!-- News Cards -->
            <div 
              v-for="newsItem in filteredNews" 
              :key="newsItem.id" 
              class="carousel-item snap-start flex-shrink-0 w-[350px] group"
            >
              <div class="flex flex-col h-full rounded-xl bg-gray-800/30 border border-gray-700/30 hover:border-indigo-500/30 transition-all overflow-hidden">
                <!-- News Image -->
                <div class="relative h-48 w-full overflow-hidden">
                  <img 
                    :src="`https://avaloriaflyff.online${newsItem.image_link || '/images/news-default.jpg'}`" 
                    :alt="newsItem.title"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    @error="e => e.target.src = 'https://cdnb.artstation.com/p/assets/images/images/020/117/971/original/frostiae-2019-08-18-18-19-21.gif?1566431349'"
                  />
                  <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent h-24"></div>
                  <div 
                    class="absolute top-3 left-3 px-2 py-1 rounded-md text-xs font-medium"
                    :class="{
                      'bg-indigo-500/80 text-white': newsItem.type == 0,
                      'bg-emerald-500/80 text-white': newsItem.type == 1,
                      'bg-amber-500/80 text-white': newsItem.type == 2,
                      'bg-violet-500/80 text-white': newsItem.type == 3,
                      'bg-blue-500/80 text-white': newsItem.type == 4,
                      'bg-red-500/80 text-white': newsItem.type == 5,
                    }"
                  >
                    {{ newsType[newsItem.type] }}
                  </div>
                </div>
                
                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                  <div class="flex justify-between items-center mb-3">
                    <span class="text-xs text-gray-400">{{ formatDate(newsItem.created_at) }}</span>
                    <span class="text-xs text-gray-400">By {{ newsItem.author || 'Administrator' }}</span>
                  </div>
                  
                  <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-indigo-300 transition-colors">
                    {{ newsItem.title }}
                  </h3>
                  
                  <div class="text-gray-400 text-sm mb-4 line-clamp-3">
                    {{ newsItem.description ? (newsItem.description.replace(/<[^>]*>?/gm, '').substring(0, 120) + '...') : '' }}
                  </div>
                  
                  <div class="mt-auto">
                    <Link :href="`/news/${newsItem.id}`" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30 transition-all group">
                      <span>Read more</span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-if="filteredNews.length === 0" class="flex-1 flex items-center justify-center p-10 bg-gray-800/20 rounded-xl border border-gray-700/30">
              <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-300">No news found</h3>
                <p class="mt-1 text-gray-500">There are no news items in this category yet.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <!-- Featured News Section (Optional) -->
      <section v-if="props.news.some(n => n.featured)" class="py-12 bg-gray-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-8">
            <h2 class="text-2xl font-bold text-white">Featured News</h2>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Featured News Item -->
            <div v-for="item in props.news.filter(n => n.featured).slice(0, 2)" :key="item.id"
              class="group rounded-xl bg-gray-800/30 border border-gray-700/30 hover:border-indigo-500/30 transition-all overflow-hidden flex flex-col md:flex-row">
              <div class="w-full md:w-2/5 h-48 md:h-auto relative">
                <img 
                  :src="`https://avaloriaflyff.online${item.image_link || '/images/news-default.jpg'}`" 
                  :alt="item.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  @error="e => e.target.src = 'https://cdnb.artstation.com/p/assets/images/images/020/117/971/original/frostiae-2019-08-18-18-19-21.gif?1566431349'"
                />
              </div>
              <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                  <div 
                    class="px-2 py-1 rounded-md text-xs font-medium"
                    :class="{
                      'bg-indigo-500/20 text-indigo-300': item.type == 0,
                      'bg-emerald-500/20 text-emerald-300': item.type == 1,
                      'bg-amber-500/20 text-amber-300': item.type == 2,
                      'bg-violet-500/20 text-violet-300': item.type == 3,
                      'bg-blue-500/20 text-blue-300': item.type == 4,
                      'bg-red-500/20 text-red-300': item.type == 5,
                    }"
                  >
                    {{ newsType[item.type] }}
                  </div>
                  <span class="text-xs text-gray-400">{{ formatDate(item.created_at) }}</span>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                  {{ item.title }}
                </h3>
                
                <div class="text-gray-400 mb-6">
                  {{ item.description ? (item.description.replace(/<[^>]*>?/gm, '').substring(0, 150) + '...') : '' }}
                </div>
                
                <div class="mt-auto">
                  <Link :href="`/news/${item.id}`" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30 transition-all group">
                    <span class="font-medium">Read full article</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                  </Link>
                </div>
              </div>
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
  0%, 100% {
    opacity: 0.1;
  }
  50% {
    opacity: 0.3;
  }
}

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

/* Hide scrollbar but allow scrolling */
.hide-scrollbar {
  scrollbar-width: none;  /* Firefox */
  -ms-overflow-style: none;  /* IE and Edge */
  scroll-snap-type: x mandatory;
  scroll-behavior: smooth;
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;  /* Chrome, Safari and Opera */
}

.carousel-item {
  scroll-snap-align: start;
}

/* Gradient fade on carousel edges */
.carousel-container::before,
.carousel-container::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 60px;
  pointer-events: none;
  z-index: 10;
}

.carousel-container::before {
  left: 0;
  background: linear-gradient(to right, rgba(10, 10, 21, 1), rgba(10, 10, 21, 0));
}

.carousel-container::after {
  right: 0;
  background: linear-gradient(to left, rgba(10, 10, 21, 1), rgba(10, 10, 21, 0));
}

/* Line clamp for multi-line text truncation */
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