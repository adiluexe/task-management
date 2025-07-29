<template>
  <!-- Modern Confirmation Dialog -->
  <div
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click.self="$emit('cancel')"
  >
    <!-- Dialog Container -->
    <div
      class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 w-full max-w-md transform transition-all duration-300 ease-out"
      @click.stop
    >
      <!-- Header with Icon -->
      <div class="text-center pt-8 pb-4 px-8">
        <div
          class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6"
        >
          <svg
            class="h-8 w-8 text-red-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
            />
          </svg>
        </div>

        <h3 class="text-2xl font-bold text-text-900 mb-3">{{ title }}</h3>
        <p class="text-base text-text-600 leading-relaxed">{{ message }}</p>
      </div>

      <!-- Action Buttons -->
      <div
        class="bg-background-50/50 px-8 py-6 border-t border-background-200/50 flex justify-end gap-2 rounded-3xl"
      >
        <button
          @click="$emit('cancel')"
          class="px-6 py-3 border border-background-300 text-text-700 font-medium rounded-2xl hover:bg-background-100 focus:ring-2 focus:ring-background-200 transition-all duration-200"
        >
          Cancel
        </button>
        <button
          @click="$emit('confirm')"
          class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-2xl hover:from-red-600 hover:to-red-700 focus:ring-4 focus:ring-red-200 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center"
        >
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
          Delete Task
        </button>
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
  message: {
    type: String,
    required: true,
  },
});

defineEmits(["confirm", "cancel"]);
</script>

<style scoped>
/* Backdrop filter support */
@supports (backdrop-filter: blur(12px)) {
  .backdrop-blur-xl {
    backdrop-filter: blur(12px);
  }

  .backdrop-blur-sm {
    backdrop-filter: blur(4px);
  }
}

/* Dialog entrance animation */
@keyframes dialogEnter {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.transform {
  animation: dialogEnter 0.3s ease-out;
}

/* Warning icon pulse animation */
@keyframes warningPulse {
  0%,
  100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

.bg-red-100 {
  animation: warningPulse 2s ease-in-out infinite;
}

/* Focus ring enhancement */
.focus\:ring-2:focus,
.focus\:ring-4:focus {
  outline: none;
}

.focus\:ring-2:focus {
  box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.5);
}

.focus\:ring-4:focus {
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.3);
}
</style>
