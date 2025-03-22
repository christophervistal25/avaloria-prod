<template>
  <div class="container mx-auto py-8 px-4 max-w-7xl">
    <div
        class="pb-16 text-center relative"
        data-aos="fade-up"
        data-aos-duration="1000"
      >
        <div class="relative inline-block">
          <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white tracking-tight">
            Event 
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-violet-400 to-purple-300">
              Schedule
            </span>
          </h1>
          <div class="absolute -inset-x-6 -inset-y-4 bg-gradient-to-r from-indigo-500/10 via-violet-500/10 to-purple-500/10 blur-3xl animate-pulse-slow"></div>
        </div>

        <p class="mt-6 text-lg sm:text-xl text-slate-300/90 max-w-2xl mx-auto px-4 leading-relaxed font-light">
          Stay informed with our comprehensive event schedule. Track upcoming events, 
          PK windows, and special activities to make the most of your adventure.
        </p>

        <div class="mt-12 h-px w-32 mx-auto bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
      </div>

    <!-- PK Settings Card -->
    <div class="mb-8">
      <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg overflow-hidden">
        <div class="relative p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 via-pink-600/5 to-purple-600/5"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-xl font-semibold text-slate-100 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                PK Mode Settings
              </h3>
              <div
                class="px-3 py-1 rounded-full text-sm font-medium"
                :class="pkSettings.isActive
                  ? 'bg-green-900/50 text-green-300 border border-green-500/30'
                  : 'bg-red-900/50 text-red-300 border border-red-500/30'"
              >
                {{ pkSettings.isActive ? 'ACTIVE' : 'INACTIVE' }}
              </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
              <div class="bg-gray-900/50 rounded-lg p-4 hover:bg-gray-900/70 transition-colors">
                <div class="text-sm text-slate-400">Cycle Time</div>
                <div class="text-lg font-semibold text-slate-200">{{ pkSettings.settings.timeout }}</div>
              </div>
              <div class="bg-gray-900/50 rounded-lg p-4 hover:bg-gray-900/70 transition-colors">
                <div class="text-sm text-slate-400">Duration</div>
                <div class="text-lg font-semibold text-slate-200">{{ pkSettings.settings.duration }}</div>
              </div>
              <div class="bg-gray-900/50 rounded-lg p-4 hover:bg-gray-900/70 transition-colors">
                <div class="text-sm text-slate-400">EXP Rate</div>
                <div class="text-lg font-semibold text-emerald-400">{{ pkSettings.settings.expMultiplier }}</div>
              </div>
              <div class="bg-gray-900/50 rounded-lg p-4 hover:bg-gray-900/70 transition-colors">
                <div class="text-sm text-slate-400">Drop Rate</div>
                <div class="text-lg font-semibold text-amber-400">{{ pkSettings.settings.dropMultiplier }}</div>
              </div>
            </div>

            <!-- PK Timer -->
            <div class="bg-gray-900/30 rounded-lg p-4">
              <div class="flex justify-between items-center">
                <span class="text-slate-400">Next PK Window:</span>
                <div
                  class="text-xl font-mono font-bold text-transparent bg-clip-text"
                  :class="pkSettings.isActive
                    ? 'bg-gradient-to-r from-green-400 to-emerald-400'
                    : 'bg-gradient-to-r from-indigo-400 to-purple-400'"
                >
                  {{ pkSettings.isActive ? `Ends in ${getCountdown(pkSettings.next)}` : getCountdown(pkSettings.next) }}
                </div>
              </div>
              <div class="mt-2 text-sm text-slate-500 text-center">
                {{ pkSettings.isActive ? 'PK Mode is currently active' : 'Time until next PK window' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center">
      <p class="text-slate-400">Loading schedule...</p>
    </div>

    <div v-else>
      <!-- Event Type Filter -->
      <div class="flex justify-center gap-4 mb-8 flex-wrap">
          <button
              v-for="type in ['all', 'siege', 'tdm', 'ffa', 'secretroom']"
              :key="type"
              @click="activeFilter = type"
              :class="[
                  'px-6 py-2 rounded-full transition-all duration-300',
                  activeFilter === type
                      ? getActiveButtonClass(type)
                      : 'bg-gray-800/50 text-slate-300 hover:bg-gray-700/50 backdrop-blur-sm'
              ]"
          >
              {{ getEventTypeName(type) }}
          </button>
      </div>

      <!-- Events Grid -->
      <div class="space-y-8">
        <!-- Next Events Section -->
        <div>
          <h3 class="text-2xl font-semibold text-slate-100 mb-6">Next Events</h3>
          <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <div
              v-for="event in nextEvents"
              :key="event.next"
              class="relative group bg-gray-800/50 backdrop-blur-sm rounded-lg overflow-hidden transition-all duration-300 hover:-translate-y-1"
            >
              <!-- Ambient Glow -->
              <div
                class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                :class="getEventTypeGradient(event.eventType)"
              ></div>

              <!-- Border Gradient -->
              <div
                class="absolute inset-0 p-[1px] rounded-lg"
                :class="getEventTypeBorder(event.eventType)"
              >
                <div class="h-full w-full bg-gray-900/90 rounded-lg"></div>
              </div>

              <!-- Content -->
              <div class="relative p-6 space-y-4">
                <!-- Header -->
                <div class="flex justify-between items-center">
                  <h3 class="text-xl font-semibold text-slate-100">
                    {{ event.type }}
                  </h3>
                  <span
                    class="text-sm px-3 py-1 rounded-full border"
                    :class="getEventTypeLabel(event.eventType)"
                  >
                    {{ event.day }}
                  </span>
                </div>

                <!-- Time -->
                <div class="flex justify-between items-center">
                  <span class="text-slate-400">Start Time:</span>
                  <span class="font-medium text-slate-200">{{ event.time }}</span>
                </div>

                <!-- Requirements -->
                <div class="text-sm space-y-1.5 bg-gray-800/30 rounded-lg p-3">
                  <div class="flex justify-between">
                    <span class="text-slate-400">Level:</span>
                    <span class="text-slate-300">{{ event.requirements.level }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-400">Entry Fee:</span>
                    <span class="text-slate-300">{{ event.requirements.fee }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-400">Capacity:</span>
                    <span class="text-slate-300">{{ event.requirements.players }}</span>
                  </div>
                  <div v-if="event.requirements.lives" class="flex justify-between">
                    <span class="text-slate-400">Lives:</span>
                    <span class="text-slate-300">{{ event.requirements.lives }}</span>
                  </div>
                  <div v-if="event.requirements.duration" class="flex justify-between">
                    <span class="text-slate-400">Duration:</span>
                    <span class="text-slate-300">{{ event.requirements.duration }}</span>
                  </div>
                </div>

                <!-- Countdown -->
                <div class="text-center mt-6">
                  <div
                    class="text-2xl font-mono font-bold text-transparent bg-clip-text"
                    :class="getEventTypeText(event.eventType)"
                  >
                    {{ getCountdown(event.next) }}
                  </div>
                  <p class="text-sm text-slate-500 mt-2">Until next event</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Show More Section -->
        <div v-if="hasMoreEvents" class="text-center hidden">
          <button
            @click="showAllEvents = !showAllEvents"
            class="group relative px-8 py-4 bg-gray-800/50 rounded-lg overflow-hidden hover:bg-gray-700/50 transition-all duration-300"
          >
            <span class="text-slate-200 font-semibold">
              {{ showAllEvents ? 'Show Less' : `Show More (${remainingEvents.length} events)` }}
            </span>
          </button>
        </div>

        <!-- All Events Section -->
        <div v-if="showAllEvents" class="mt-8">
          <h3 class="text-2xl font-semibold text-slate-100 mb-6">All Upcoming Events</h3>
          <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="event in nextEvents"
                :key="event.next"
                  class="relative group bg-gray-800/50 backdrop-blur-sm rounded-lg overflow-hidden transition-all duration-300 hover:-translate-y-1"
            >
              <!-- Ambient Glow -->
              <div
                class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                :class="getEventTypeGradient(event.eventType)"
              ></div>

              <!-- Border Gradient -->
              <div
                class="absolute inset-0 p-[1px] rounded-lg"
                :class="getEventTypeBorder(event.eventType)"
              >
                <div class="h-full w-full bg-gray-900/90 rounded-lg"></div>
              </div>

              <!-- Content -->
              <div class="relative p-6 space-y-4">
                <!-- Header -->
                <div class="flex justify-between items-center">
                  <h3 class="text-xl font-semibold text-slate-100">
                    {{ event.type }}
                  </h3>
                  <span
                    class="text-sm px-3 py-1 rounded-full border"
                    :class="getEventTypeLabel(event.eventType)"
                  >
                    {{ event.day }}
                  </span>
                </div>

                <!-- Time -->
                <div class="flex justify-between items-center">
                  <span class="text-slate-400">Start Time:</span>
                  <span class="font-medium text-slate-200">{{ event.time }}</span>
                </div>

                <!-- Requirements -->
                <div class="text-sm space-y-1.5 bg-gray-800/30 rounded-lg p-3">
                  <div class="flex justify-between">
                    <span class="text-slate-400">Level:</span>
                    <span class="text-slate-300">{{ event.requirements.level }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-400">Entry Fee:</span>
                    <span class="text-slate-300">{{ event.requirements.fee }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-400">Capacity:</span>
                    <span class="text-slate-300">{{ event.requirements.players }}</span>
                  </div>
                  <div v-if="event.requirements.lives" class="flex justify-between">
                    <span class="text-slate-400">Lives:</span>
                    <span class="text-slate-300">{{ event.requirements.lives }}</span>
                  </div>
                  <div v-if="event.requirements.duration" class="flex justify-between">
                    <span class="text-slate-400">Duration:</span>
                    <span class="text-slate-300">{{ event.requirements.duration }}</span>
                  </div>
                </div>

                <!-- Countdown -->
                <div class="text-center mt-6">
                  <div
                    class="text-2xl font-mono font-bold text-transparent bg-clip-text"
                    :class="getEventTypeText(event.eventType)"
                  >
                    {{ getCountdown(event.next) }}
                  </div>
                  <p class="text-sm text-slate-500 mt-2">Until next event</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const events = ref([]);
    const pkSettings = ref({
      settings: {
        timeout: '',
        duration: '',
        expMultiplier: '',
        dropMultiplier: ''
      }
    });
    const loading = ref(true);
    const activeFilter = ref('all');
    const showAllEvents = ref(false);
    let timer = null;

    // Get next event for each type
    const nextEvents = computed(() => {
      if (activeFilter.value === 'all') {
        const eventTypes = ['siege', 'tdm', 'ffa', 'secretroom']; // Added 'secretroom'
        return eventTypes.map(type => {
          return events.value
            .filter(event => event.eventType === type)
            .sort((a, b) => new Date(a.next) - new Date(b.next))[0];
        }).filter(Boolean);
      } else {
        return [events.value
          .filter(event => event.eventType === activeFilter.value)
          .sort((a, b) => new Date(a.next) - new Date(b.next))[0]]
          .filter(Boolean);
      }
    });

    // Get remaining events
    const remainingEvents = computed(() => {
      if (activeFilter.value === 'all') {
        return events.value
          .filter(event => !nextEvents.value.find(e => e.next === event.next))
          .sort((a, b) => new Date(a.next) - new Date(b.next));
      } else {
        return events.value
          .filter(event =>
            event.eventType === activeFilter.value &&
            event.next !== nextEvents.value[0]?.next
          )
          .sort((a, b) => new Date(a.next) - new Date(b.next));
      }
    });

    // Check if there are more events to show
    const hasMoreEvents = computed(() => {
      return remainingEvents.value.length > 0;
    });

    const fetchEvents = async () => {
      try {
        const response = await axios.get('/flyff-events');
        events.value = response.data.events;
        pkSettings.value = response.data.pkSettings;
      } catch (error) {
        console.error('Error fetching events:', error);
      } finally {
        loading.value = false;
      }
    };

    const getCountdown = (nextTime) => {
      const now = new Date();
      const target = new Date(nextTime);
      const diff = target - now;

      if (diff <= 0) {
        return 'In Progress';
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      return `${days}d ${hours}h ${minutes}m ${seconds}s`;
    };

    const getEventTypeName = (type) => ({
      'all': 'All Events',
      'siege': 'Guild Siege',
      'tdm': 'Team Death Match',
      'ffa': 'Free For All',
    'secretroom': 'Secret Room'
    }[type] || type);

    const getEventTypeGradient = (type) => ({
        'siege': 'bg-gradient-to-br from-indigo-600/10 via-violet-600/10 to-purple-600/10',
        'tdm': 'bg-gradient-to-br from-red-600/10 via-orange-600/10 to-yellow-600/10',
        'ffa': 'bg-gradient-to-br from-emerald-600/10 via-teal-600/10 to-cyan-600/10',
        'secretroom': 'bg-gradient-to-br from-rose-600/10 via-pink-600/10 to-fuchsia-600/10'
    }[type] || '');


    const getEventTypeBorder = (type) => ({
        'siege': 'bg-gradient-to-br from-indigo-500/50 via-violet-500/30 to-purple-500/50',
        'tdm': 'bg-gradient-to-br from-red-500/50 via-orange-500/30 to-yellow-500/50',
        'ffa': 'bg-gradient-to-br from-emerald-500/50 via-teal-500/30 to-cyan-500/50',
        'secretroom': 'bg-gradient-to-br from-rose-500/50 via-pink-500/30 to-fuchsia-500/50'
    }[type] || '');

    const getEventTypeText = (type) => ({
        'siege': 'bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400',
        'tdm': 'bg-gradient-to-r from-red-400 via-orange-400 to-yellow-400',
        'ffa': 'bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400',
        'secretroom': 'bg-gradient-to-r from-rose-400 via-pink-400 to-fuchsia-400'
    }[type] || '');

    const getActiveButtonClass = (type) => ({
      'siege': 'bg-indigo-600 text-white',
      'tdm': 'bg-red-600 text-white',
      'ffa': 'bg-emerald-600 text-white',
      'secretroom': 'bg-rose-600 text-white',
      'all': 'bg-violet-600 text-white'
    }[type] || 'bg-gray-600 text-white');

    const getPKStatus = computed(() => {
      if (!pkSettings.value.next) return '';
      const now = new Date();
      const nextTime = new Date(pkSettings.value.next);

      if (pkSettings.value.isActive) {
        return `Active for ${getCountdown(pkSettings.value.next)}`;
      }
      return `Next in ${getCountdown(pkSettings.value.next)}`;
    });

    onMounted(() => {
      fetchEvents();
      timer = setInterval(() => {
        events.value = [...events.value];
      }, 1000);
    });

    const getEventTypeLabel = (type) => ({
        'siege': 'bg-indigo-900/50 text-indigo-300 border-indigo-500/30',
        'tdm': 'bg-red-900/50 text-red-300 border-red-500/30',
        'ffa': 'bg-emerald-900/50 text-emerald-300 border-emerald-500/30',
        'secretroom': 'bg-rose-900/50 text-rose-300 border-rose-500/30'
    }[type] || '');

    onUnmounted(() => {
      if (timer) clearInterval(timer);
    });

    return {
      events,
      pkSettings,
      loading,
      activeFilter,
      showAllEvents,
      nextEvents,
      remainingEvents,
      hasMoreEvents,
      getPKStatus,
      getCountdown,
      getEventTypeName,
      getEventTypeGradient,
      getEventTypeBorder,
      getEventTypeLabel,
      getEventTypeText,
      getActiveButtonClass
    };
  }
}
</script>

<style scoped>
.animate-pulse-slow {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes glow {
  0%, 100% {
    opacity: 0.5;
  }
  50% {
    opacity: 0.8;
  }
}

.animate-glow {
  animation: glow 4s ease-in-out infinite;
}

@keyframes gradient-shift {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.bg-gradient-text {
  background-size: 200% auto;
  animation: gradient-shift 4s ease infinite;
}

/* Card hover animation */
.event-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Show more button animation */
.show-more-button {
  transition: all 0.3s ease;
}

.show-more-button:hover {
  transform: translateY(-2px);
}

/* Smooth height transition for expandable content */
.expandable-content {
  transition: max-height 0.3s ease-in-out;
  overflow: hidden;
}
</style>
