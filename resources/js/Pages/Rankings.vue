<script setup>
import { Link } from "@inertiajs/vue3";
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
import Layout from "@layouts/Layout.vue";
import { ref, computed } from "vue";

const props = defineProps({
  characters: Object,
});

// Get top 3 players for podium
const topThreePlayers = computed(() => {
  return props.characters.data.slice(0, 3);
});

// Get remaining players (4th position onwards)
const remainingPlayers = computed(() => {
  return props.characters.data.slice(3);
});

// Function to get appropriate medal color class
const getMedalClass = (position) => {
  switch (position) {
    case 0:
      return "bg-gradient-to-r from-yellow-300 to-amber-500";
    case 1:
      return "bg-gradient-to-r from-gray-300 to-gray-400";
    case 2:
      return "bg-gradient-to-r from-amber-600 to-yellow-700";
    default:
      return "bg-indigo-600";
  }
};

// Function to get appropriate podium position class
const getPodiumClass = (position) => {
  switch (position) {
    case 0:
      return "order-2 lg:scale-110 z-20";
    case 1:
      return "order-1 self-end z-10";
    case 2:
      return "order-3 self-end z-10";
    default:
      return "";
  }
};

const getClassName = (jobId) => {
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

// Function to get appropriate podium height class
const getPodiumHeight = (position) => {
  switch (position) {
    case 0:
      return "h-[340px]";
    case 1:
      return "h-[300px]";
    case 2:
      return "h-[300px]";
    default:
      return "h-[300px]";
  }
};

// Format playtime from minutes to hours and minutes
const formatPlaytime = (minutes) => {
  if (!minutes) return "0h 0m";
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
};
</script>

<template>
  <Layout>
    <div
      class="min-h-screen bg-[#0A0A15] font-[Inter] text-gray-100"
      id="content"
    >
      <!-- Hero Section -->
      <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center">
            <div
              class="inline-flex items-center space-x-2 bg-indigo-900/30 rounded-full px-3 py-1 mb-4"
            >
              <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
              <span class="text-sm font-medium text-indigo-300"
                >Server Rankings</span
              >
            </div>
            <h1 class="text-5xl lg:text-6xl font-bold mb-6 tracking-tight">
              Player
              <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400"
              >
                Leaderboard
              </span>
            </h1>
            <p class="text-xl text-gray-400 mb-8 max-w-3xl mx-auto">
              Explore the top players of Avaloria Flyff, ranked by level and
              playtime. Challenge yourself to climb the rankings and become a
              legend!
            </p>
          </div>
        </div>

        <!-- Background Effects -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute inset-0 overflow-hidden">
            <div
              class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px]"
            ></div>
            <div
              class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-violet-900/10 rounded-full blur-[120px]"
            ></div>
          </div>
          <div
            class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.02] mix-blend-overlay"
          ></div>
        </div>
      </section>

      <!-- Top 3 Podium Section -->
      <section class="py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <h2 class="text-3xl font-bold text-center mb-16">
            Top Ranked Players
          </h2>

          <!-- Podium Display for Top 3 -->
          <div
            class="flex flex-col lg:flex-row items-end justify-center gap-8 mb-20"
          >
            <div
              v-for="(player, index) in topThreePlayers"
              :key="player.id"
              :class="['relative group', getPodiumClass(index)]"
              class="flex flex-col items-center w-full lg:w-1/3 transition-all"
            >
              <!-- Character Card -->
              <div
                :class="[
                  'rounded-xl overflow-hidden relative',
                  getPodiumHeight(index),
                ]"
                class="w-full bg-gray-800/30 border border-gray-700/30 group-hover:border-indigo-500/30 transition-all duration-300 shadow-lg"
              >
                <!-- Rank Badge -->
                <div
                  class="absolute top-4 left-4 z-20 flex items-center justify-center"
                >
                  <div
                    :class="[getMedalClass(index)]"
                    class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg text-white font-bold text-lg"
                  >
                    {{ index + 1 }}
                  </div>
                </div>

                <!-- Character Image -->
                <div class="w-full h-2/5 relative">
                  <!-- Gradient overlay -->
                  <div
                    class="absolute inset-0 bg-gradient-to-t from-gray-800/90 via-gray-800/50 to-transparent z-10"
                  ></div>

                  <!-- Character image (placeholder) -->
                  <img
                    :src="`https://wcdn-universe.flyff.com/site/landing/images/donaris.png`"
                    :alt="player.m_szName"
                    v-if="index === 0"
                    class="w-full h-full object-contain opacity-70"
                  />

                  <img
                    :src="`https://wcdn-universe.flyff.com/site/landing/images/amos.png`"
                    :alt="player.m_szName"
                    v-if="index === 1"
                    class="w-full h-full object-contain opacity-70"
                  />

                  <img
                    :src="`https://wcdn-universe.flyff.com/site/landing/images/cardmaster.png`"
                    :alt="player.m_szName"
                    v-if="index === 2"
                    class="w-full h-full object-contain opacity-70"
                  />
                </div>

                <!-- Character Info -->
                <div class="p-5 relative">
                  <!-- Glowing effect for #1 -->
                  <div
                    v-if="index === 0"
                    class="absolute inset-0 bg-yellow-500/10 blur-lg rounded-full animate-pulse-slow"
                  ></div>

                  <div class="relative">
                    <div class="flex justify-between items-start mb-4">
                      <h3 class="text-xl font-bold text-white">
                        {{ player.m_szName }}
                      </h3>
                      <div
                        class="px-2 py-1 text-xs font-semibold rounded bg-indigo-900/50 text-indigo-300"
                      >
                        Level {{ player.m_nLevel }}
                      </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                      <div>
                        <div class="text-gray-400">Class</div>
                        <div class="font-medium text-white">
                          {{ getClassName(player.m_nJob) }}
                        </div>
                      </div>
                      <div>
                        <div class="text-gray-400">Guild</div>
                        <div class="font-medium text-white">
                          {{ player.m_idGuild || "None" }}
                        </div>
                      </div>
                      <div>
                        <div class="text-gray-400">Playtime</div>
                        <div class="font-medium text-white">
                          {{ formatPlaytime(player.TotalPlayTime) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Podium Base -->
              <div
                :class="[getMedalClass(index)]"
                class="h-2 w-4/5 rounded-b-lg shadow-lg mt-1"
              ></div>
              <div
                :class="[
                  getMedalClass(index),
                  {
                    'h-24': index === 0,
                    'h-16': index === 1,
                    'h-12': index === 2,
                  },
                ]"
                class="w-3/5 rounded-b-lg shadow-lg opacity-20 mt-1"
              ></div>

              <!-- Player Rank Text -->
              <div class="mt-4 text-center">
                <div
                  class="text-lg font-semibold"
                  :class="
                    index === 0
                      ? 'text-yellow-400'
                      : index === 1
                      ? 'text-gray-300'
                      : 'text-amber-600'
                  "
                >
                  {{
                    index === 0
                      ? "Rank #1"
                      : index === 1
                      ? "Rank #2"
                      : "Rank #3"
                  }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Full Rankings Table -->
      <section class="pb-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-2xl font-bold mb-8">All Player Rankings</h2>

          <div class="overflow-x-auto">
            <table
              class="min-w-full bg-gray-800/30 rounded-xl overflow-hidden border border-gray-700/30"
            >
              <thead>
                <tr class="border-b border-gray-700/30">
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Rank
                  </th>
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Character
                  </th>
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Level
                  </th>
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Class
                  </th>
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Guild
                  </th>
                  <th
                    class="py-4 px-6 text-left text-sm font-medium text-gray-400"
                  >
                    Playtime
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(player, index) in remainingPlayers"
                  :key="player.id"
                  class="border-b border-gray-700/10 hover:bg-indigo-900/10 transition-colors"
                >
                  <td class="py-4 px-6">
                    <div class="flex items-center">
                      <div
                        class="bg-indigo-600/20 text-indigo-300 w-8 h-8 rounded-full flex items-center justify-center font-semibold"
                      >
                        {{ index + 4 }}
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 font-medium text-white">
                    {{ player.m_szName }}
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex items-center">
                      <span class="text-yellow-400 mr-1">Lv.</span>
                      <span>{{ player.m_nLevel }}</span>
                    </div>
                  </td>
                  <td class="py-4 px-6">{{ getClassName(player.m_nJob )}}</td>
                  <td class="py-4 px-6">{{ player.m_idGuild || "None" }}</td>
                  <td class="py-4 px-6">
                    {{ formatPlaytime(player.TotalPlayTime) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div
            class="mt-8 flex justify-center"
            v-if="props.characters.links && props.characters.links.length > 3"
          >
            <div class="flex space-x-2">
              <Link
                v-for="(link, key) in props.characters.links"
                :key="key"
                :href="link.url"
                class="px-4 py-2 rounded-lg transition-all"
                :class="[
                  link.active
                    ? 'bg-indigo-600 text-white'
                    : 'bg-gray-800/50 text-gray-300 hover:bg-gray-800',
                  { 'opacity-50 cursor-not-allowed': !link.url },
                ]"
                v-html="link.label"
              ></Link>
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
  0%,
  100% {
    opacity: 0.1;
  }
  50% {
    opacity: 0.3;
  }
}

@keyframes float-slow {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.animate-float {
  animation: float-slow 5s ease-in-out infinite;
}

/* Hide horizontal scrollbar but allow scrolling */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(99, 102, 241, 0.3) rgba(30, 41, 59, 0.5);
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: rgba(30, 41, 59, 0.5);
  border-radius: 100vh;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.3);
  border-radius: 100vh;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.5);
}

#content {
  font-family: "Roboto", "Open Sans", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>