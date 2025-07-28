<template>
  <div class="fixed inset-0 bg-text-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-background-200 w-96 shadow-lg rounded-md bg-background-50">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-text-900">
            {{ task ? 'Edit Task' : 'Create New Task' }}
          </h3>
          <button
            @click="$emit('close')"
            class="text-text-400 hover:text-text-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label for="title" class="block text-sm font-medium text-text-700">
              Title
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              required
              class="mt-1 block w-full border-background-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
              :class="{ 'border-accent-300': errors.title }"
            />
            <p v-if="errors.title" class="mt-1 text-sm text-accent-600">{{ errors.title[0] }}</p>
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-text-700">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              class="mt-1 block w-full border-background-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
              :class="{ 'border-accent-300': errors.description }"
            ></textarea>
            <p v-if="errors.description" class="mt-1 text-sm text-accent-600">{{ errors.description[0] }}</p>
          </div>

          <div>
            <label for="priority" class="block text-sm font-medium text-text-700">
              Priority
            </label>
            <select
              id="priority"
              v-model="form.priority"
              class="mt-1 block w-full border-background-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
              :class="{ 'border-accent-300': errors.priority }"
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
            <p v-if="errors.priority" class="mt-1 text-sm text-accent-600">{{ errors.priority[0] }}</p>
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-text-700">
              Status
            </label>
            <select
              id="status"
              v-model="form.status"
              class="mt-1 block w-full border-background-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
              :class="{ 'border-accent-300': errors.status }"
            >
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
            </select>
            <p v-if="errors.status" class="mt-1 text-sm text-accent-600">{{ errors.status[0] }}</p>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-background-300 rounded-md shadow-sm text-sm font-medium text-text-700 bg-background-50 hover:bg-background-100 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <span v-if="loading">Saving...</span>
              <span v-else>{{ task ? 'Update' : 'Create' }}</span>
            </button>
          </div>

          <div v-if="errorMessage" class="rounded-md bg-accent-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-accent-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-accent-800">{{ errorMessage }}</h3>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({
  task: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'save'])

const loading = ref(false)
const errorMessage = ref('')
const errors = ref({})

const form = reactive({
  title: '',
  description: '',
  priority: 'medium',
  status: 'pending'
})

const handleSubmit = async () => {
  loading.value = true
  errorMessage.value = ''
  errors.value = {}

  try {
    emit('save', { ...form })
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      errorMessage.value = 'An error occurred. Please try again.'
    }
  } finally {
    loading.value = false
  }
}

// Initialize form with task data if editing
watch(() => props.task, (newTask) => {
  if (newTask) {
    form.title = newTask.title || ''
    form.description = newTask.description || ''
    form.priority = newTask.priority || 'medium'
    form.status = newTask.status || 'pending'
  } else {
    // Reset form for new task
    form.title = ''
    form.description = ''
    form.priority = 'medium'
    form.status = 'pending'
  }
}, { immediate: true })
</script>
