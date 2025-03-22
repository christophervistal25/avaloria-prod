<script setup>
import { ref, computed, onMounted } from 'vue'
import Layout from '@/Pages/Layouts/Layout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  wikipedia: {
    type: Object,
    required: true
  }
});

// Generate a table of contents from the description content
const tableOfContents = ref([]);

onMounted(() => {
  setTimeout(() => {
    const article = document.querySelector('.article-content');
    if (article) {
      const headings = article.querySelectorAll('h2, h3');
      tableOfContents.value = Array.from(headings).map((heading, index) => {
        const id = `heading-${index}`;
        heading.id = id;
        return {
          id,
          text: heading.textContent,
          level: heading.tagName === "H2" ? 2 : 3
        };
      });
    }
  }, 100);
});

// Determine if we have any related items
const hasItems = computed(() => {
  return props.wikipedia.items && props.wikipedia.items.length > 0;
});

// Format date in a nicer way
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-[#0A0A15] text-gray-100">
      <!-- Hero Banner with Article Image -->
      <section class="relative">
        <!-- Banner Image -->
        <div class="relative w-full h-[300px] md:h-[400px]">
          <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A15] via-[#0A0A15]/90 to-[#0A0A15]/70"></div>
          <img 
            v-if="wikipedia.image" 
            :src="`/storage/${wikipedia.image}`" 
            :alt="wikipedia.title"
            class="w-full h-full object-cover opacity-30"
            @error="e => e.target.src = 'https://via.placeholder.com/1200x600?text=Avaloria+Wiki'"
          />
          <div v-else class="w-full h-full bg-gradient-to-br from-indigo-900/20 to-purple-900/20"></div>
          
          <!-- Decorative elements for visual interest -->
          <div class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.05] mix-blend-overlay pointer-events-none"></div>
          <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-violet-900/10 rounded-full blur-[120px]"></div>
          </div>
        </div>

        <!-- Title Overlay -->
        <div class="absolute top-1/2 left-0 right-0 transform -translate-y-1/2 text-center px-4">
          <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center space-x-2 bg-indigo-900/30 rounded-full px-3 py-1 mb-4">
              <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
              <span class="text-sm font-medium text-indigo-300">Encyclopedia</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">{{ wikipedia.title }}</h1>
            <nav class="flex items-center justify-center space-x-4 text-sm text-gray-400">
              <Link 
                href="/wiki"
                class="flex items-center hover:text-indigo-300 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Wiki
              </Link>
              <span>•</span>
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Updated {{ formatDate(wikipedia.updated_at) }}
              </span>
            </nav>
          </div>
        </div>
      </section>

      <!-- Main Content Section -->
      <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar for Table of Contents -->
            <div class="lg:col-span-3">
              <div class="sticky top-8">
                <div v-if="tableOfContents.length > 0" class="bg-gray-800/30 rounded-xl border border-gray-700/30 p-6 mb-6">
                  <h3 class="text-lg font-bold mb-4 text-white">Table of Contents</h3>
                  <nav class="space-y-2">
                    <a 
                      v-for="heading in tableOfContents" 
                      :key="heading.id"
                      :href="`#${heading.id}`"
                      class="block text-sm py-1 border-l-2 pl-3 transition-all hover:text-indigo-300"
                      :class="[
                        heading.level === 2 ? 
                          'border-indigo-500 text-white' : 
                          'border-indigo-500/40 text-gray-400 pl-5'
                      ]"
                    >
                      {{ heading.text }}
                    </a>
                  </nav>
                </div>

                <!-- Quick Info Box -->
                <div class="bg-gray-800/30 rounded-xl border border-gray-700/30 p-6">
                  <h3 class="text-lg font-bold mb-4 text-white">Quick Actions</h3>
                  <div class="space-y-3">
                    <Link href="/wiki" class="flex items-center text-sm text-indigo-300 hover:text-indigo-200 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                      Browse All Categories
                    </Link>
                    <button 
                      onclick="window.print()" 
                      class="flex items-center text-sm text-indigo-300 hover:text-indigo-200 transition-colors"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                      </svg>
                      Print Article
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Main Article Content -->
            <div class="lg:col-span-9">
              <article class="bg-gray-800/30 rounded-2xl border border-gray-700/30 overflow-hidden shadow-xl shadow-indigo-900/10">
                <!-- Main Article Content -->
                <div class="p-8 lg:p-10">
                  <div class="article-content prose prose-invert prose-lg max-w-none" v-html="wikipedia.description"></div>

                  <!-- Related Items Section -->
                  <div v-if="hasItems" id="related-items" class="mt-12 pt-8 border-t border-gray-700/30">
                    <!-- Item Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                      <div 
                        v-for="(item, index) in wikipedia.items" 
                        :key="index"
                        class="bg-gray-800/50 rounded-xl border border-gray-700/30 overflow-hidden hover:border-indigo-500/30 transition-all duration-300"
                      >
                        <div class="p-6">
                          <!-- Title with optional icon -->
                          <div class="flex items-start mb-4">
                            <div class="mr-4 p-2 rounded-lg bg-indigo-900/30 text-indigo-300">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ item.title }}</h3>
                          </div>
                          
                          <div class="prose prose-invert prose-sm max-w-none mt-2" v-html="item.description"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-800/50 border-t border-gray-700/30 p-6">
                  <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="text-sm text-gray-400 mb-4 sm:mb-0">
                      Last updated: {{ formatDate(wikipedia.updated_at) }}
                    </div>
                    <div class="flex space-x-4">
                      <Link 
                        href="/wiki"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600/20 rounded-lg text-indigo-300 hover:bg-indigo-600/30 transition-colors text-sm font-medium"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Wiki
                      </Link>
                    </div>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<style>
/* Enhanced article content styling */
.article-content {
  color: #e2e8f0;
  line-height: 1.8;
}

.article-content h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-top: 2.5rem;
  margin-bottom: 1.25rem;
  color: white;
  position: relative;
  padding-bottom: 0.75rem;
  scroll-margin-top: 100px;
}

.article-content h2::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(to right, #6366f1, rgba(99, 102, 241, 0.3));
  border-radius: 3px;
}

.article-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-top: 2rem;
  margin-bottom: 1rem;
  color: #d1d5db;
  scroll-margin-top: 100px;
}

.article-content p {
  margin-bottom: 1.5rem;
}

.article-content strong {
  color: white;
  font-weight: 600;
}

.article-content em {
  color: #93c5fd;
  font-style: italic;
}

.article-content a {
  color: #93c5fd;
  text-decoration: none;
  border-bottom: 2px solid rgba(147, 197, 253, 0.3);
  transition: border-color 0.2s;
}

.article-content a:hover {
  border-color: #93c5fd;
}

.article-content ul, .article-content ol {
  margin: 1.5rem 0;
  padding-left: 1.5rem;
}

.article-content li {
  margin-bottom: 0.5rem;
}

.article-content img {
  border-radius: 0.75rem;
  margin: 2rem 0;
  border: 2px solid rgba(99, 102, 241, 0.2);
}

.article-content blockquote {
  border-left: 3px solid #6366f1;
  padding-left: 1.5rem;
  font-style: italic;
  margin: 2rem 0;
  color: #a5b4fc;
  background-color: rgba(99, 102, 241, 0.05);
  padding: 1.5rem;
  border-radius: 0.5rem;
}

.article-content blockquote p:last-child {
  margin-bottom: 0;
}

/* Game-themed separator */
.article-content hr {
  border: 0;
  height: 1px;
  background: linear-gradient(to right, rgba(99, 102, 241, 0), rgba(99, 102, 241, 0.5), rgba(99, 102, 241, 0));
  margin: 2.5rem 0;
}

/* Tables for game data */
.article-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 2rem 0;
  background-color: rgba(30, 41, 59, 0.5);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(99, 102, 241, 0.2);
}

.article-content th {
  background-color: rgba(99, 102, 241, 0.1);
  padding: 0.75rem 1rem;
  text-align: left;
  font-weight: 600;
  color: white;
  border-bottom: 1px solid rgba(99, 102, 241, 0.2);
}

.article-content td {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid rgba(99, 102, 241, 0.1);
}

.article-content tr:last-child td {
  border-bottom: none;
}

/* Code blocks for game commands */
.article-content pre {
  background-color: rgba(30, 41, 59, 0.8);
  border-radius: 0.5rem;
  padding: 1rem;
  overflow-x: auto;
  border: 1px solid rgba(99, 102, 241, 0.2);
  margin: 1.5rem 0;
}

.article-content code {
  font-family: monospace;
  color: #a5b4fc;
}

/* Print styles */
@media print {
  .article-content {
    color: #000;
  }
  
  .article-content h2, .article-content h3 {
    color: #000;
  }
  
  .article-content a {
    color: #3b82f6;
    text-decoration: underline;
    border-bottom: none;
  }
  
  .article-content blockquote {
    border-left: 3px solid #3b82f6;
    color: #4b5563;
    background-color: #f3f4f6;
  }
}
</style>