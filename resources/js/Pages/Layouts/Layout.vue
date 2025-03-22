<script setup>
import Navigation from "@/components/Navigation.vue";
import Footer from "@/components/Footer.vue";
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gradient-to-b from-[#0A0A15] via-[#0F1123] to-[#0A0A15] text-white">
        <Navigation />
        <div class="flex-grow">
            <!-- Main content -->
            <main>
                <div class="">
                    <slot></slot>
                </div>
            </main>
        </div>
        <Footer />
    </div>
</template>

<style scoped></style>