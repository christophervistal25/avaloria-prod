<template>
    <section
        class="py-24 relative bg-gradient-to-b from-[#0A0A15] via-[#0F1123] to-[#0A0A15]"
    >
        <!-- Background Effects -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Animated Gradients -->
            <div class="absolute inset-0">
                <div
                    class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px] animate-float-slow"
                ></div>
                <div
                    class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-violet-900/10 rounded-full blur-[120px] animate-float-slow-reverse"
                ></div>
            </div>
            <div
                class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.02] mix-blend-overlay"
            ></div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Column - Text & Features -->
                <div class="space-y-8" data-aos="fade-right">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <div class="h-1 w-12 bg-indigo-500 rounded-full"></div>
                            <span class="text-indigo-400 uppercase text-sm tracking-wider font-semibold">Community Updates</span>
                        </div>
                        <h2
                            class="text-4xl sm:text-5xl font-bold text-slate-100 leading-tight"
                        >
                            Latest
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400"
                            >
                                News
                            </span>
                        </h2>
                        <p class="text-lg text-slate-400 leading-relaxed">
                            Stay updated with the latest announcements, updates and events from Avaloria. Our team regularly posts important information about game updates, events and server maintenance.
                        </p>
                    </div>

                    <!-- News List -->
                    <div class="space-y-6">
                        <div
                            v-for="newsItem in displayedNews"
                            :key="newsItem.id"
                            class="group flex flex-col gap-4 p-5 rounded-lg transition-all duration-300 hover:bg-slate-800/50 border border-slate-700/30 hover:border-indigo-500/30"
                        >
                            <!-- News Card Header -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="px-2 py-1 rounded-md text-xs font-medium" 
                                         :class="{
                                            'bg-indigo-500/20 text-indigo-300': newsItem.type == 0,
                                            'bg-emerald-500/20 text-emerald-300': newsItem.type == 1,
                                            'bg-amber-500/20 text-amber-300': newsItem.type == 2,
                                            'bg-violet-500/20 text-violet-300': newsItem.type == 3,
                                            'bg-blue-500/20 text-blue-300': newsItem.type == 4,
                                            'bg-red-500/20 text-red-300': newsItem.type == 5,
                                         }">
                                        {{ newsType[newsItem.type] }}
                                    </div>
                                </div>
                                <span class="text-xs text-slate-500">{{ formatDate(newsItem.created_at) }}</span>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-slate-200 group-hover:text-white transition-colors">
                                {{ newsItem.title }}
                            </h3>
                            
                            <!-- Image - Only if available -->
                            <div v-if="newsItem.image_link" class="w-full h-40 overflow-hidden rounded-lg">
                                <img 
                                    :src="`https://avaloriaflyff.online${newsItem.image_link}`" 
                                    :alt="newsItem.title"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    @error="e => e.target.src = 'https://cdnb.artstation.com/p/assets/images/images/020/117/971/original/frostiae-2019-08-18-18-19-21.gif?1566431349'"
                                />
                            </div>
                            
                            <!-- Content -->
                            <div class="prose prose-invert prose-sm max-w-none text-slate-300">
                                <div v-html="formatNewsContent(newsItem.description)"></div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-slate-400">By {{ newsItem.author || 'Administrator' }}</span>
                                <Link :href="`/news/${newsItem.id}`" class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                                    Read More →
                                </Link>
                            </div>
                        </div>
                        
                        <!-- View More Button -->
                        <div class="pt-4 flex justify-center">
                            <Link href="/news" class="inline-flex items-center px-6 py-3 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30 transition-all border border-indigo-500/30 hover:border-indigo-500/50 group">
                                <span class="font-medium">View More News</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Character Image -->
                <div class="relative aspect-[4/5]" data-aos="fade-left">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-violet-500/10 blur-3xl rounded-full animate-pulse-slow"
                    ></div>
                    <img
                        alt="Game Character"
                        src="https://avaloriaflyff.online/images/download-hero.png"
                        class="relative z-10 w-full h-full object-contain drop-shadow-[0_0_45px_rgba(99,102,241,0.3)] animate-float-slow"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, defineProps, computed } from "vue";
import moment from "moment";

const props = defineProps({
    news: Array,   
});

// News types with clear, professional labels
const newsType = ref([
    "Announcement",
    "Event",
    "Update",
    "Promotion",
    "Guide",
    "Maintenance",
]);

// Limit displayed news to prevent excessive scrolling
const displayedNews = computed(() => {
    return props.news.filter(item => item.display).slice(0, 2);
});

// Format dates professionally
const formatDate = (dateString) => {
    return moment(dateString).format('MMM DD, YYYY');
};

// Format news content, truncate professionally
const formatNewsContent = (content) => {
    if (!content) return '';
    
    // Get clean text without HTML, then truncate
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = content;
    const textContent = tempDiv.textContent || tempDiv.innerText || '';
    
    if (textContent.length <= 120) return content;
    return textContent.substring(0, 120).trim() + '...';
};
</script>

<style scoped>
@keyframes float-slow {
    0%,
    100% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(-10px, -10px);
    }
}

@keyframes float-slow-reverse {
    0%,
    100% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(10px, 10px);
    }
}

.animate-float-slow {
    animation: float-slow 15s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Optional: Add glow effect to the download button on hover */
.download-button:hover {
    box-shadow: 0 0 30px theme("colors.indigo.500" / 20%);
}
</style>