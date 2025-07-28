<template>
  <div class="fixed inset-0 bg-text-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-background-200 w-96 shadow-lg rounded-md bg-background-50">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-accent-100">
          <svg class="h-6 w-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-text-900 mt-4">{{ title }}</h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-text-500">{{ message }}</p>
        </div>
        <div class="flex justify-center space-x-3 px-4 py-3">
          <button
            @click="$emit('cancel')"
            class="px-4 py-2 bg-background-50 border border-background-300 rounded-md shadow-sm text-sm font-medium text-text-700 hover:bg-background-100 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="$emit('confirm')"
            class="px-4 py-2 bg-accent-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-accent-700 transition-colors"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    required: true
  }
})

defineEmits(['confirm', 'cancel'])
</script>
