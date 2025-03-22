<script setup>
import { Link } from "@inertiajs/vue3";
import Layout from "@layouts/Layout.vue";
import { ref, onMounted } from "vue";
import moment from "moment";

// Props for a single news item
const props = defineProps({
  newsItem: Object,
  relatedNews: {
    type: Array,
    default: () => [],
  },
});

// News types with professional labels
const newsType = {
  0: "Announcement",
  1: "Event",
  2: "Update",
  3: "Promotion",
  4: "Guide",
  5: "Maintenance",
};

// Format dates in a professional way
const formatDate = (dateString) => {
  return moment(dateString).format("MMMM DD, YYYY");
};

// Social sharing URLs
const shareLinks = {
  facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
    window.location.href
  )}`,
  twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(
    window.location.href
  )}&text=${encodeURIComponent(
    props.newsItem?.title || "Avaloria Flyff News"
  )}`,
  discord: `https://discord.com/channels/@me`,
};

// Table of contents generation
const toc = ref([]);

onMounted(() => {
  setTimeout(() => {
    const headings = document.querySelectorAll(
      ".news-content h2, .news-content h3"
    );
    toc.value = Array.from(headings).map((heading, index) => {
      const id = `heading-${index}`;
      heading.id = id;
      return {
        id,
        text: heading.textContent,
        level: heading.tagName === "H2" ? 2 : 3,
      };
    });
  }, 100);
});
</script>

<template>
  <Layout>
    <div class="min-h-screen bg-[#0A0A15] text-gray-100">
      <!-- Hero Banner with News Image -->
      <section class="relative">
        <!-- Banner Image -->
        <div class="relative w-full h-[300px] md:h-[400px]">
          <div
            class="absolute inset-0 bg-gradient-to-t from-[#0A0A15] via-[#0A0A15]/90 to-[#0A0A15]/70"
          ></div>
          <img
            :src="`https://avaloriaflyff.online${
              newsItem.image_link || '/images/news-default.jpg'
            }`"
            :alt="newsItem.title"
            class="w-full h-full object-cover opacity-30"
            @error="
              (e) =>
                (e.target.src =
                  'https://cdnb.artstation.com/p/assets/images/images/020/117/971/original/frostiae-2019-08-18-18-19-21.gif?1566431349')
            "
          />
          <!-- Decorative elements for visual interest -->
          <div
            class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.05] mix-blend-overlay pointer-events-none"
          ></div>
          <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div
              class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px]"
            ></div>
            <div
              class="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-violet-900/10 rounded-full blur-[120px]"
            ></div>
          </div>
        </div>

        <!-- Title Overlay -->
        <div
          class="absolute top-1/2 left-0 right-0 transform -translate-y-1/2 text-center px-4"
        >
          <div class="max-w-4xl mx-auto">
            <div
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-4"
              :class="{
                'bg-indigo-500/20 text-indigo-300': newsItem.type === 0,
                'bg-emerald-500/20 text-emerald-300': newsItem.type === 1,
                'bg-amber-500/20 text-amber-300': newsItem.type === 2,
                'bg-violet-500/20 text-violet-300': newsItem.type === 3,
                'bg-blue-500/20 text-blue-300': newsItem.type === 4,
                'bg-red-500/20 text-red-300': newsItem.type === 5,
              }"
            >
              {{ newsType[newsItem.type] }}
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
              {{ newsItem.title }}
            </h1>
            <div
              class="flex items-center justify-center space-x-4 text-sm text-gray-400"
            >
              <span class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-1"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                {{ formatDate(newsItem.created_at) }}
              </span>
              <span class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-1"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                {{ newsItem.author || "Administrator" }}
              </span>
            </div>
          </div>
        </div>

        <!-- Scroll indicator -->
        <div
          class="absolute bottom-4 left-1/2 transform -translate-x-1/2 animate-bounce"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 text-indigo-400"
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
      </section>

      <!-- Main Content Section -->
      <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Article Content -->
          <article
            class="bg-gray-800/30 rounded-2xl border border-gray-700/30 overflow-hidden shadow-xl shadow-indigo-900/10"
          >
            <!-- Featured Image (Larger & More Impactful) -->
            <div
              v-if="newsItem.image_link"
              class="w-full h-[350px] sm:h-[400px] lg:h-[450px] relative"
            >
              <!-- Image with overlay gradient for text readability -->
              <div
                class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/70 to-transparent opacity-60 z-10"
              ></div>
              <img
                :src="`https://avaloriaflyff.online${newsItem.image_link}`"
                :alt="newsItem.title"
                class="w-full h-full object-cover"
                @error="
                  (e) =>
                    (e.target.src =
                      'https://cdnb.artstation.com/p/assets/images/images/020/117/971/original/frostiae-2019-08-18-18-19-21.gif?1566431349')
                "
              />

              <!-- News metadata on image -->
              <div class="absolute bottom-0 left-0 w-full p-6 z-20">
                <div class="flex items-center space-x-3 mb-3">
                  <div
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="{
                      'bg-indigo-500/80 text-white': newsItem.type === 0,
                      'bg-emerald-500/80 text-white': newsItem.type === 1,
                      'bg-amber-500/80 text-white': newsItem.type === 2,
                      'bg-violet-500/80 text-white': newsItem.type === 3,
                      'bg-blue-500/80 text-white': newsItem.type === 4,
                      'bg-red-500/80 text-white': newsItem.type === 5,
                    }"
                  >
                    {{ newsType[newsItem.type] }}
                  </div>
                  <span class="text-sm text-gray-300">{{
                    formatDate(newsItem.created_at)
                  }}</span>
                </div>
              </div>
            </div>

            <!-- Article Body -->
            <div class="p-8 lg:p-10">
              <!-- Title (Only show here if no image) -->
              <div v-if="!newsItem.image_link" class="mb-6">
                <div class="flex items-center space-x-3 mb-3">
                  <div
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="{
                      'bg-indigo-500/20 text-indigo-300': newsItem.type === 0,
                      'bg-emerald-500/20 text-emerald-300': newsItem.type === 1,
                      'bg-amber-500/20 text-amber-300': newsItem.type === 2,
                      'bg-violet-500/20 text-violet-300': newsItem.type === 3,
                      'bg-blue-500/20 text-blue-300': newsItem.type === 4,
                      'bg-red-500/20 text-red-300': newsItem.type === 5,
                    }"
                  >
                    {{ newsType[newsItem.type] }}
                  </div>
                  <span class="text-sm text-gray-400">{{
                    formatDate(newsItem.created_at)
                  }}</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">
                  {{ newsItem.title }}
                </h1>
              </div>

              <!-- Author info -->
              <div
                class="flex items-center mb-8 border-b border-gray-700/50 pb-6"
              >
                <div
                  class="w-12 h-12 rounded-full bg-indigo-600/30 flex items-center justify-center text-indigo-300"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-white">
                    {{ newsItem.author || "Administrator" }}
                  </div>
                  <div class="text-xs text-gray-400">Avaloria Staff</div>
                </div>
              </div>

              <!-- Stylized Game-themed content separator -->
              <div class="flex items-center justify-center my-6">
                <div class="h-px bg-indigo-500/20 w-full"></div>
                <div class="px-4">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-indigo-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                    />
                  </svg>
                </div>
                <div class="h-px bg-indigo-500/20 w-full"></div>
              </div>

              <!-- Enhanced Content with styling -->
              <div class="game-news-content">
                <div
                  class="prose prose-invert prose-lg max-w-none news-content"
                  v-html="newsItem.description"
                ></div>
              </div>

              <!-- Tags if available -->
              <div
                v-if="newsItem.tags && newsItem.tags.length"
                class="mt-10 pt-6 border-t border-gray-700/50"
              >
                <h3 class="text-lg font-semibold text-white mb-3">
                  Related Topics
                </h3>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="tag in newsItem.tags"
                    :key="tag"
                    class="px-3 py-1.5 bg-indigo-600/10 text-indigo-300 text-sm rounded-full border border-indigo-500/20 hover:bg-indigo-600/20 transition-colors cursor-pointer"
                  >
                    #{{ tag }}
                  </span>
                </div>
              </div>
            </div>
          </article>

          <!-- Navigation Section -->
          <div class="mt-8">
            <Link
              href="/news"
              class="inline-flex items-center p-3 rounded-xl bg-gray-800/30 border border-gray-700/30 hover:bg-gray-800/50 transition-all group"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
              </svg>
              <span>Back to News List</span>
            </Link>
          </div>
        </div>
      </section>
    </div>
  </Layout>
</template>

<style>
//* Enhanced game-themed news content styling */
.game-news-content {
  position: relative;
  z-index: 1;
}

.game-news-content::before {
  content: "";
  position: absolute;
  top: 50px;
  left: -40px;
  width: 80px;
  height: 80px;
  background: radial-gradient(
    circle,
    rgba(99, 102, 241, 0.15) 0%,
    rgba(99, 102, 241, 0) 70%
  );
  border-radius: 50%;
  z-index: -1;
}

.game-news-content::after {
  content: "";
  position: absolute;
  bottom: 100px;
  right: -40px;
  width: 120px;
  height: 120px;
  background: radial-gradient(
    circle,
    rgba(139, 92, 246, 0.15) 0%,
    rgba(139, 92, 246, 0) 70%
  );
  border-radius: 50%;
  z-index: -1;
}

/* Enhanced content typography */
.news-content {
  color: #e2e8f0;
  line-height: 1.8;
  font-size: 1.1rem;
}

.news-content h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-top: 2.5rem;
  margin-bottom: 1.25rem;
  color: white;
  position: relative;
  padding-bottom: 0.75rem;
}

.news-content h2::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(to right, #6366f1, rgba(99, 102, 241, 0.3));
  border-radius: 3px;
}

.news-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-top: 2rem;
  margin-bottom: 1rem;
  color: #d1d5db;
}

.news-content p {
  margin-bottom: 1.5rem;
}

.news-content strong {
  color: white;
  font-weight: 600;
}

.news-content em {
  color: #93c5fd;
  font-style: italic;
}

.news-content a {
  color: #93c5fd;
  text-decoration: none;
  border-bottom: 2px solid rgba(147, 197, 253, 0.3);
  transition: border-color 0.2s;
}

.news-content a:hover {
  border-color: #93c5fd;
}

.news-content ul,
.news-content ol {
  margin: 1.5rem 0;
  padding-left: 1.5rem;
}

.news-content li {
  margin-bottom: 0.5rem;
}

.news-content img {
  border-radius: 0.75rem;
  margin: 2rem 0;
  border: 2px solid rgba(99, 102, 241, 0.2);
}

.news-content blockquote {
  border-left: 3px solid #6366f1;
  padding-left: 1.5rem;
  font-style: italic;
  margin: 2rem 0;
  color: #a5b4fc;
  background-color: rgba(99, 102, 241, 0.05);
  padding: 1.5rem;
  border-radius: 0.5rem;
}

.news-content blockquote p:last-child {
  margin-bottom: 0;
}

/* Game-themed separator */
.news-content hr {
  border: 0;
  height: 1px;
  background: linear-gradient(
    to right,
    rgba(99, 102, 241, 0),
    rgba(99, 102, 241, 0.5),
    rgba(99, 102, 241, 0)
  );
  margin: 2.5rem 0;
}

/* Tables for game data */
.news-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 2rem 0;
  background-color: rgba(30, 41, 59, 0.5);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(99, 102, 241, 0.2);
}

.news-content th {
  background-color: rgba(99, 102, 241, 0.1);
  padding: 0.75rem 1rem;
  text-align: left;
  font-weight: 600;
  color: white;
  border-bottom: 1px solid rgba(99, 102, 241, 0.2);
}

.news-content td {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid rgba(99, 102, 241, 0.1);
}

.news-content tr:last-child td {
  border-bottom: none;
}

/* Code blocks for game commands */
.news-content pre {
  background-color: rgba(30, 41, 59, 0.8);
  border-radius: 0.5rem;
  padding: 1rem;
  overflow-x: auto;
  border: 1px solid rgba(99, 102, 241, 0.2);
  margin: 1.5rem 0;
}

.news-content code {
  font-family: monospace;
  color: #a5b4fc;
}
</style>
