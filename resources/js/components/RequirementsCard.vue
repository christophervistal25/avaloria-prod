<template>
    <div
        class="bg-black/40 backdrop-blur-sm border border-purple-500/20 rounded-lg p-6"
    >
        <h3 class="text-xl font-bold text-white mb-4">{{ title }}</h3>
        <div class="space-y-3">
            <div
                v-for="(value, key) in requirements"
                :key="key"
                class="flex justify-between items-center"
            >
                <span class="text-gray-400">{{ key }}</span>
                <span class="text-purple-400">{{ value }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    requirements: {
        type: Object,
        required: true,
    },
});
</script>
