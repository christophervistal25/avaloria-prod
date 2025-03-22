<script setup>
import { ref, onBeforeUnmount } from "vue";
import axios from "axios";

const props = defineProps({
  accounts: {
    type: Object,
    required: true,
  },
});

const selectedAccount = ref(null);
const voteStatus = ref("idle"); // idle, initiating, voting, completed, error, cooldown
const statusMessage = ref("");
const pollInterval = ref(null);
const remainingTime = ref(null);

const handleVote = async () => {
  if (!selectedAccount.value) {
    alert("Please select an account before voting!");
    return;
  }

  try {
    voteStatus.value = "initiating";
    statusMessage.value = "Preparing your vote...";

    const response = await axios.post("/vote/initiate", {
      account: selectedAccount.value,
    });

    if (response.data.status === "success") {
      voteStatus.value = "voting";
      statusMessage.value = "Redirecting to voting site...";

      // Open voting page
      window.open(response.data.vote_url, "_blank");

      // Start polling for vote status
      startPolling();

      statusMessage.value =
        "Please complete your vote on the opened page. This window will update automatically.";
    }
  } catch (error) {
    console.error(error);
    if (error.response && error.response.status === 429) {
      voteStatus.value = "cooldown";
      statusMessage.value = error.response.data.message;
      remainingTime.value = error.response.data.next_vote;
    } else {
      voteStatus.value = "error";
      statusMessage.value =
        "An error occurred while initiating your vote. Please try again.";
    }
  }
};

const startPolling = () => {
  // Poll every 5 seconds
  pollInterval.value = setInterval(async () => {
    try {
      const response = await axios.get("/vote/status");

      if (response.data.status === "completed") {
        voteStatus.value = "completed";
        statusMessage.value = response.data.message;
        stopPolling();
      } else if (response.data.status === "timeout") {
        voteStatus.value = "error";
        statusMessage.value = response.data.message;
        stopPolling();
      } else if (response.data.status === "not_initiated") {
        voteStatus.value = "error";
        statusMessage.value = "Vote session not found. Please try again.";
        stopPolling();
      }
    } catch (error) {
      console.error(error);
      voteStatus.value = "error";
      statusMessage.value = "Error checking vote status. Please refresh the page.";
      stopPolling();
    }
  }, 5000);
};

const stopPolling = () => {
  if (pollInterval.value) {
    clearInterval(pollInterval.value);
    pollInterval.value = null;
  }
};

onBeforeUnmount(() => {
  stopPolling();
});

const resetVote = () => {
  voteStatus.value = "idle";
  statusMessage.value = "";
};
</script>

<template>
   <!-- Account Selection (only show when not in voting/completed state) -->
   <div class="mb-6" v-if="voteStatus === 'idle'">
        <label class="block text-white mb-2">Select Account:</label>
        <div class="relative">
          <select
            v-model="selectedAccount"
            class="w-full p-3 bg-[#1a1a1f] border border-[#2a2a3a] rounded-lg text-white appearance-none cursor-pointer focus:outline-none focus:border-[#5a28b4] focus:ring-1 focus:ring-[#5a28b4] transition duration-300"
          >
            <option value="" disabled selected>Choose an account</option>
            <option
              v-for="a in accounts"
              :key="a.account"
              :value="a.account"
              class="py-2"
            >
              {{ a.account }}
            </option>
          </select>
          <div
            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none"
          >
            <svg
              class="w-5 h-5 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              />
            </svg>
          </div>
        </div>
      </div>

      <!-- Status Display -->
      <div v-if="voteStatus !== 'idle'" class="mb-8 text-center">
        <!-- Loading Spinner for initiating/voting status -->
        <div
          v-if="voteStatus === 'initiating' || voteStatus === 'voting'"
          class="flex justify-center mb-4"
        >
          <div
            class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#5a28b4]"
          ></div>
        </div>

        <!-- Success Icon -->
        <div v-if="voteStatus === 'completed'" class="flex justify-center mb-4">
          <div
            class="h-16 w-16 rounded-full bg-green-500 flex items-center justify-center"
          >
            <svg class="h-10 w-10 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
        </div>

        <!-- Error Icon -->
        <div
          v-if="voteStatus === 'error' || voteStatus === 'cooldown'"
          class="flex justify-center mb-4"
        >
          <div class="h-16 w-16 rounded-full bg-red-500 flex items-center justify-center">
            <svg class="h-10 w-10 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
        </div>

        <p class="text-lg font-medium text-white">{{ statusMessage }}</p>

        <button
          v-if="voteStatus === 'completed' || voteStatus === 'error'"
          @click="resetVote"
          class="mt-4 px-4 py-2 bg-[#3d1789] text-white rounded hover:bg-[#5a28b4]"
        >
          Back to Vote
        </button>
      </div>

      <!-- Voting Link (Only show in idle state) -->
      <div class="grid gap-4 mt-6" v-if="voteStatus === 'idle'">
        <div class="p-4 bg-[#1a1a1f] border border-[#2a2a3a] rounded-lg">
          <div class="flex items-center justify-between">
            <span class="text-white font-medium">GTOP100</span>
            <button
              @click="handleVote"
              :disabled="!selectedAccount"
              class="px-4 py-2 bg-[#3d1789] text-white rounded hover:bg-[#5a28b4] disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Vote Now
            </button>
          </div>
        </div>
      </div>
</template>
