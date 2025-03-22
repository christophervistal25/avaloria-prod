<script setup>
import { defineComponent, ref, watchEffect, computed } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import DonationRanking from "@components/Admin/DonationRanking.vue";
import { Head, router } from "@inertiajs/vue3";



</script>

<template>
  <layout>
    <template #header>
      <div class="mb-8 border-b border-gray-200 pb-5">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Donate Rankings</h1>
            <p class="text-sm text-gray-600 mt-1">View player donation rankings and contribution history</p>
          </div>
        </div>
      </div>
    </template>

    <DonationRanking />
    
  </layout>
</template>
