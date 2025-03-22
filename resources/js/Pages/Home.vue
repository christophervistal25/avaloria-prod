<script setup>
import { Link } from "@inertiajs/vue3";
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
import WelcomeSection from "@/components/WelcomeSection.vue";
import ClassSelector from "@/components/ClassSelector.vue";
import AdventureCallToAction from "@/components/AdventureCallToAction.vue";
import HeroSection from "@/components/HeroSection.vue";
import FlyffEventTimer from "@/components/FlyffEventTimer.vue";

const props = defineProps(["news"]);
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-b from-[#0A0A15] via-[#0F1123] to-[#0A0A15]"
    >
        <Navigation />
        <HeroSection />
        <FlyffEventTimer/>
        <WelcomeSection />
        <ClassSelector />
        <AdventureCallToAction :news="news" />
        <Footer />
    </div>
</template>

