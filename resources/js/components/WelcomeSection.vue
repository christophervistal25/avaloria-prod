<template>
  <section class="relative min-h-screen bg-gradient-to-b from-[#0A0A15] via-[#0F1123] to-[#0A0A15] overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 pointer-events-none">
      <!-- Gradient Overlay -->
      <div class="absolute inset-0 bg-gradient-radial from-indigo-950/20 via-transparent to-transparent"></div>

      <!-- Animated Background Shapes -->
      <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-900/20 rounded-full blur-[100px] animate-float-slow"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-violet-900/20 rounded-full blur-[100px] animate-float-slow-reverse"></div>
      </div>

      <!-- Noise Texture -->
      <div class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.02] mix-blend-overlay"></div>
    </div>

    <div class="relative container mx-auto px-4 min-h-screen flex flex-col">
      <!-- Header Section -->
      <div
        class="pt-32 pb-16 text-center relative"
        data-aos="fade-up"
        data-aos-duration="1000"
      >
        <div class="relative inline-block">
          <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white tracking-tight">
            Experience
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-violet-400 to-purple-300">
              New Dimension
            </span>
          </h1>
          <div class="absolute -inset-x-6 -inset-y-4 bg-gradient-to-r from-indigo-500/10 via-violet-500/10 to-purple-500/10 blur-3xl animate-pulse-slow"></div>
        </div>

        <p class="mt-6 text-lg sm:text-xl text-slate-300/90 max-w-2xl mx-auto px-4 leading-relaxed font-light">
          Immerse yourself in a realm where ancient magic and modern
          warfare collide. Forge your destiny in a world of endless
          possibilities.
        </p>

        <div class="mt-12 h-px w-32 mx-auto bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
      </div>

      <!-- Features Section -->
      <div class="flex-1 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <!-- Feature Image -->
          <div
            class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-900"
            data-aos="fade-right"
          >
            <!-- Image Container -->
            <div
              class="absolute inset-0 transition-transform duration-700 transform"
              :style="{
                                transform: `scale(${activeFeature === index ? '1.05' : '1'})`,
                            }"
              v-for="(feature, index) in features"
              :key="feature.title"
            >
              <img
                v-show="activeFeature === index"
                :alt="feature.title"
                :src="feature.image"
                class="w-full h-full object-cover transition-opacity duration-700"
                :class="
                                    activeFeature === index
                                        ? 'opacity-100'
                                        : 'opacity-0'
                                "
              />
            </div>

            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent"></div>

            <!-- Feature Title Overlay -->
            <div class="absolute bottom-0 left-0 right-0 p-8">
              <h3 class="text-2xl font-bold text-white mb-2">
                {{ features[activeFeature].title }}
              </h3>
              <p class="text-slate-300 text-sm">
                {{ features[activeFeature].description }}
              </p>
            </div>
          </div>

          <!-- Feature Navigation -->
          <div
            class="space-y-4"
            data-aos="fade-left"
          >
            <button
              v-for="(feature, index) in features"
              :key="feature.title"
              class="w-full group transition-all duration-300 rounded-xl border border-transparent"
              :class="[
                                activeFeature === index
                                    ? 'bg-slate-800/80 backdrop-blur border-slate-700'
                                    : 'hover:bg-slate-800/40 hover:border-slate-800',
                            ]"
              @click="activeFeature = index"
            >
              <div class="relative p-6 flex items-center space-x-4">
                <!-- Icon -->
                <div class="relative flex-shrink-0">
                  <div
                    class="absolute inset-0 blur-xl transition-all duration-300"
                    :class="
                                            activeFeature === index
                                                ? 'bg-indigo-500/30'
                                                : 'bg-slate-500/20'
                                        "
                  ></div>
                  <img
                    :alt="feature.title"
                    :src="feature.icon"
                    class="relative z-10 w-12 h-12 transition-transform duration-300 group-hover:scale-110"
                  />
                </div>

                <!-- Content -->
                <div class="flex-1">
                  <h4
                    class="text-lg font-semibold transition-colors duration-300"
                    :class="[
                                            activeFeature === index
                                                ? 'text-indigo-400'
                                                : 'text-slate-300 group-hover:text-indigo-400',
                                        ]"
                  >
                    {{ feature.title }}
                  </h4>
                  <p class="mt-1 text-sm text-slate-400 line-clamp-2">
                    {{ feature.description }}
                  </p>
                </div>

                <!-- Active Indicator -->
                <div
                  class="w-1.5 h-12 rounded-full transition-all duration-300 mr-2"
                  :class="
                                        activeFeature === index
                                            ? 'bg-gradient-to-b from-indigo-400 to-violet-500'
                                            : 'bg-slate-800'
                                    "
                ></div>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const features = [
  {
    title: "Strategic Combat",
    description:
      "Experience dynamic combat with advanced tactical systems and real-time strategy elements.",
    icon: "https://avaloriaflyff.online/images/sword.png",
    image: "https://avaloriaflyff.online/bg4.jpeg",
  },
  {
    title: "Vast Open World",
    description:
      "Explore seamless landscapes filled with hidden mysteries and ancient secrets.",
    icon: "https://avaloriaflyff.online/images/wings.png",
    image: "https://avaloriaflyff.online/map.png",
  },
  {
    title: "Alliance System",
    description:
      "Form powerful alliances and engage in epic territory wars with massive multiplayer battles.",
    icon: "https://avaloriaflyff.online/images/guild.png",
    image: "https://avaloriaflyff.online/alliance.webp",
  },
  {
    title: "Character Evolution",
    description:
      "Customize and evolve your character with deep progression systems and unique skill trees.",
    icon: "https://avaloriaflyff.online/images/levelup.png",
    image: "https://avaloriaflyff.online/psy.png",
  },
];

const stats = [
  { value: "1M+", label: "Active Players" },
  { value: "150+", label: "Unique Areas" },
  { value: "500+", label: "Quests" },
  { value: "98%", label: "Player Satisfaction" },
];

const activeFeature = ref(0);

// Auto-rotate features
let intervalId;

onMounted(() => {
  intervalId = setInterval(() => {
    activeFeature.value = (activeFeature.value + 1) % features.length;
  }, 5000);
});

onUnmounted(() => {
  clearInterval(intervalId);
});
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

.animate-float-slow-reverse {
  animation: float-slow-reverse 15s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Gradient text enhancement */
.text-transparent {
  text-shadow: 0 0 30px rgba(99, 102, 241, 0.2);
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}
</style>
