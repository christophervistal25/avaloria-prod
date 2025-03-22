<template>
    <section
        class="relative min-h-screen bg-gradient-to-b from-[#0A0A15] via-[#0F1123] to-[#0A0A15] overflow-hidden"
    >
        <!-- Background Effects -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Animated Gradients -->
            <div class="absolute inset-0">
                <div
                    class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-900/20 rounded-full blur-[120px] animate-float-slow"
                ></div>
                <div
                    class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-violet-900/20 rounded-full blur-[120px] animate-float-slow-reverse"
                ></div>
            </div>
            <!-- Noise Texture -->
            <div
                class="absolute inset-0 bg-[url('/textures/noise.png')] opacity-[0.02] mix-blend-overlay"
            ></div>
        </div>

        <div
            class="relative min-h-screen flex flex-col py-16 container mx-auto px-4"
        >
            <!-- Header Section -->
            <div
                class="text-center mb-16"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <div class="relative inline-block mb-6">
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-100 tracking-tight"
                    >
                        Choose Your
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400"
                        >
                            Destiny
                        </span>
                    </h1>
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-indigo-500/10 via-violet-500/10 to-purple-500/10 blur-3xl animate-pulse-slow"
                    ></div>
                </div>
                <p
                    class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto font-light"
                >
                    Master unique abilities, forge your path, and become a
                    legend in the realm of Avaloria.
                </p>
                <div
                    class="mt-8 h-px w-32 mx-auto bg-gradient-to-r from-transparent via-slate-700 to-transparent"
                ></div>
            </div>

            <!-- Class Selection Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Class Selection Sidebar -->
                <div class="lg:col-span-3" data-aos="fade-right">
                    <div
                        class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-xl p-6 sticky top-8"
                    >
                        <!-- Class Categories -->
                        <div class="space-y-8">
                            <!-- Novice Section -->
                            <div>
                                <h3
                                    class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4"
                                >
                                    Begin Your Journey
                                </h3>
                                <button
                                    class="w-full group"
                                    @click="selectClass('vagrant')"
                                >
                                    <div
                                        class="flex items-center space-x-4 p-3 rounded-lg transition-all duration-300"
                                        :class="
                                            selectedClass === 'vagrant'
                                                ? 'bg-indigo-500/10 border border-indigo-500/30'
                                                : 'hover:bg-slate-800/50'
                                        "
                                    >
                                        <div class="relative">
                                            <div
                                                class="absolute inset-0 bg-indigo-500/20 blur-md rounded-full transition-opacity duration-300"
                                                :class="
                                                    selectedClass === 'vagrant'
                                                        ? 'opacity-100'
                                                        : 'opacity-0'
                                                "
                                            ></div>
                                            <img
                                                alt="Vagrant"
                                                class="relative z-10 w-12 h-12 transition-all duration-300 transform"
                                                :class="
                                                    selectedClass === 'vagrant'
                                                        ? 'brightness-100 scale-110'
                                                        : 'brightness-75 group-hover:brightness-100'
                                                "
                                                src="https://avaloriaflyff.online/images/vagrantffae.png"
                                            />
                                        </div>
                                        <span
                                            class="font-medium text-lg transition-colors duration-300"
                                            :class="
                                                selectedClass === 'vagrant'
                                                    ? 'text-indigo-400'
                                                    : 'text-slate-400 group-hover:text-slate-300'
                                            "
                                        >
                                            Vagrant
                                        </span>
                                    </div>
                                </button>
                            </div>

                            <!-- Advanced Classes -->
                            <div>
                                <h3
                                    class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4"
                                >
                                    Advanced Paths
                                </h3>
                                <div class="space-y-2">
                                    <button
                                        v-for="classInfo in advancedClasses"
                                        :key="classInfo.id"
                                        class="w-full group"
                                        @click="selectClass(classInfo.id)"
                                    >
                                        <div
                                            class="flex items-center space-x-4 p-3 rounded-lg transition-all duration-300"
                                            :class="
                                                selectedClass === classInfo.id
                                                    ? 'bg-indigo-500/10 border border-indigo-500/30'
                                                    : 'hover:bg-slate-800/50'
                                            "
                                        >
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-0 bg-indigo-500/20 blur-md rounded-full transition-opacity duration-300"
                                                    :class="
                                                        selectedClass ===
                                                        classInfo.id
                                                            ? 'opacity-100'
                                                            : 'opacity-0'
                                                    "
                                                ></div>
                                                <img
                                                    :alt="classInfo.name"
                                                    class="relative z-10 w-12 h-12 transition-all duration-300 transform"
                                                    :class="
                                                        selectedClass ===
                                                        classInfo.id
                                                            ? 'brightness-100 scale-110'
                                                            : 'brightness-75 group-hover:brightness-100'
                                                    "
                                                    :src="`https://avaloriaflyff.online/images/${classInfo.id}.png`"
                                                />
                                            </div>
                                            <span
                                                class="font-medium text-lg transition-colors duration-300"
                                                :class="
                                                    selectedClass ===
                                                    classInfo.id
                                                        ? 'text-indigo-400'
                                                        : 'text-slate-400 group-hover:text-slate-300'
                                                "
                                            >
                                                {{ classInfo.name }}
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Class Details -->
                <div class="lg:col-span-9" data-aos="fade-left">
                    <div
                        class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-xl overflow-hidden"
                    >
                        <!-- Class Header -->
                        <div
                            class="relative px-8 py-6 border-b border-slate-800"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div
                                        class="absolute inset-0 bg-indigo-500/20 blur-md rounded-full"
                                    ></div>
                                    <img
                                        :alt="currentClass.name"
                                        :src="`https://avaloriaflyff.online/images/${selectedClass}.png`"
                                        class="relative z-10 w-16 h-16"
                                    />
                                </div>
                                <div>
                                    <h2
                                        class="text-3xl font-bold text-slate-100"
                                    >
                                        {{ currentClass.name }}
                                    </h2>
                                    <p class="text-indigo-400 font-medium">
                                        {{ currentClass.type }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Class Content -->
                        <div class="p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                <!-- Left Column: Character Preview -->
                                <div class="space-y-8">
                                    <!-- Character Image -->
                                    <div
                                        class="relative aspect-[4/5] bg-gradient-to-b from-slate-900 to-slate-800 rounded-lg overflow-hidden"
                                    >
                                        <div
                                            class="absolute inset-0 bg-gradient-to-b from-transparent via-indigo-500/5 to-indigo-500/10"
                                        ></div>
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-transparent to-indigo-500/10 animate-pulse-slow"
                                        ></div>
                                        <img
                                            :alt="currentClass.name"
                                            :src="`https://avaloriaflyff.online/images/${selectedClass}.webp`"
                                            class="absolute inset-0 w-full h-full object-contain z-10 transition-transform duration-700 animate-float-rotate"
                                        />
                                    </div>

                                    <!-- Class Description -->
                                    <div class="space-y-4">
                                        <h3
                                            class="text-2xl font-semibold text-slate-200"
                                        >
                                            Overview
                                        </h3>
                                        <p
                                            class="text-slate-400 leading-relaxed"
                                        >
                                            {{ currentClass.description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Column: Stats & Abilities -->
                                <div class="space-y-12">
                                    <!-- Arsenal Section -->
                                    <div>
                                        <h3
                                            class="text-2xl font-semibold text-slate-200 mb-6"
                                        >
                                            Arsenal
                                        </h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div
                                                v-for="weapon in currentClass.weapons"
                                                :key="weapon"
                                                class="group relative"
                                            >
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-violet-500/10 rounded-lg transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                                ></div>
                                                <div
                                                    class="relative p-4 bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-lg"
                                                >
                                                    <span
                                                        class="text-slate-300 font-medium"
                                                        >{{ weapon }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attributes Section -->
                                    <div>
                                        <h3
                                            class="text-2xl font-semibold text-slate-200 mb-6"
                                        >
                                            Attributes
                                        </h3>
                                        <div class="space-y-6">
                                            <div
                                                v-for="(
                                                    value, attr
                                                ) in currentClass.attributes"
                                                :key="attr"
                                                class="space-y-2"
                                            >
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="text-slate-400 uppercase text-sm tracking-wider"
                                                    >
                                                        {{ attr }}
                                                    </span>
                                                    <span
                                                        class="text-slate-300 font-medium"
                                                    >
                                                        {{
                                                            value <= 33
                                                                ? "Novice"
                                                                : value <= 66
                                                                  ? "Adept"
                                                                  : "Master"
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="relative h-2 bg-slate-800 rounded-full overflow-hidden"
                                                >
                                                    <!-- Background glow effect -->
                                                    <div
                                                        class="absolute inset-0 bg-white/5"
                                                    ></div>
                                                    <!-- Progress bar -->
                                                    <div
                                                        class="h-full bg-gradient-to-r from-white/80 to-white/40 rounded-full transition-all duration-700 ease-out"
                                                        :style="{
                                                            width: `${value}%`,
                                                        }"
                                                    >
                                                        <!-- Optional: Add subtle shine effect -->
                                                        <div
                                                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent"
                                                        ></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Class Specialties -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div
                                            v-for="(
                                                specialty, index
                                            ) in currentClass.specialties"
                                            :key="index"
                                            class="p-4 bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-lg"
                                        >
                                            <div
                                                class="text-indigo-400 font-medium mb-2"
                                            >
                                                {{ specialty.title }}
                                            </div>
                                            <div class="text-sm text-slate-400">
                                                {{ specialty.description }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import { ref, computed } from "vue";

// Enhanced class data with specialties
const classes = {
    vagrant: {
        name: "Vagrant",
        type: "Base Class",
        description:
            "Embark on your journey as a Vagrant, mastering the fundamentals of combat before choosing your destined path. These adaptable warriors possess the potential to excel in any role they choose to pursue.",
        weapons: ["Training Sword", "Wooden Staff"],
        attributes: {
            STR: 50,
            DEX: 50,
            INT: 50,
            STA: 50,
        },
        specialties: [
            {
                title: "Adaptability",
                description:
                    "Natural ability to learn and master various combat styles",
            },
            {
                title: "Potential",
                description:
                    "Unlimited potential to grow into any advanced class",
            },
        ],
    },
    mercenary: {
        name: "Mercenary",
        type: "Advanced Combat Class",
        description:
            "Masters of close-quarter combat, Mercenaries excel in devastating melee attacks and superior defensive capabilities. Their unwavering strength makes them formidable front-line warriors.",
        weapons: ["Greatsword", "Battle Axe"],
        attributes: {
            STR: 85,
            DEX: 60,
            INT: 30,
            STA: 75,
        },
        specialties: [
            {
                title: "Blade Mastery",
                description:
                    "Expert handling of heavy weapons and devastating strikes",
            },
            {
                title: "Combat Prowess",
                description:
                    "Superior defensive capabilities and battlefield control",
            },
        ],
    },
    acrobat: {
        name: "Acrobat",
        type: "Level 15 Class",
        description:
            "Agile fighters who specialize in ranged combat. Acrobats use their superior mobility to control the battlefield.",
        weapons: ["Bow", "Yoyo"],
        attributes: {
            str: 40,
            dex: 90,
            int: 40,
            sta: 50,
        },
    },
    magician: {
        name: "Magician",
        type: "Level 15 Class",
        description:
            "Masters of elemental magic who can unleash devastating spells. Magicians provide powerful area damage and utility.",
        weapons: ["Staff", "Wand"],
        attributes: {
            str: 20,
            dex: 40,
            int: 90,
            sta: 40,
        },
    },
    assist: {
        name: "Assist",
        type: "Level 15 Class",
        description:
            "Supportive characters who excel in healing and buffing. Assists are valuable team players who can also hold their own in combat.",
        weapons: ["Stick", "Wand"],
        attributes: {
            str: 30,
            dex: 50,
            int: 70,
            sta: 60,
        },
    },
};

const advancedClasses = [
    { id: "mercenary", name: "Mercenary" },
    { id: "acrobat", name: "Acrobat" },
    { id: "magician", name: "Magician" },
    { id: "assist", name: "Assist" },
];

const selectedClass = ref("vagrant");
const currentClass = computed(() => classes[selectedClass.value]);

const selectClass = (classId) => {
    selectedClass.value = classId;
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

.animate-float-slow-reverse {
    animation: float-slow-reverse 15s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions */
.transition-transform {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes float-rotate {
    0% {
        transform: translateY(0) rotate(0deg);
    }
    25% {
        transform: translateY(-10px) rotate(2deg);
    }
    75% {
        transform: translateY(10px) rotate(-2deg);
    }
    100% {
        transform: translateY(0) rotate(0deg);
    }
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-float-rotate {
    animation: float-rotate 6s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow 20s linear infinite;
}

/* Option 4: Complex rotation with parallax effect */
@keyframes rotate-parallax {
    0% {
        transform: perspective(1000px) rotateY(0deg);
    }
    100% {
        transform: perspective(1000px) rotateY(360deg);
    }
}

.animate-rotate-parallax {
    animation: rotate-parallax 20s infinite linear;
    transform-style: preserve-3d;
}

/* Option 5: Combination of float and rotate */
@keyframes float-rotate-complex {
    0% {
        transform: translateY(0) rotate(0deg) scale(1);
    }
    25% {
        transform: translateY(-15px) rotate(5deg) scale(1.05);
    }
    50% {
        transform: translateY(0) rotate(0deg) scale(1);
    }
    75% {
        transform: translateY(15px) rotate(-5deg) scale(1.05);
    }
    100% {
        transform: translateY(0) rotate(0deg) scale(1);
    }
}

.animate-float-rotate-complex {
    animation: float-rotate-complex 8s ease-in-out infinite;
}
</style>
