<template>
  <!-- Modern Modal Backdrop with Blur -->
  <div 
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click.self="$emit('close')"
  >
    <!-- Modal Container with Animation -->
    <div 
      class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all duration-300 ease-out"
      @click.stop
    >
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-primary-50 to-primary-100 px-8 py-6 border-b border-primary-200/50">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="bg-primary-500 p-3 rounded-2xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </div>
            <div>
              <h3 class="text-2xl font-bold text-text-900">
                {{ task ? 'Edit Task' : 'Create New Task' }}
              </h3>
              <p class="text-primary-600 text-sm">
                {{ task ? 'Update your task details' : 'Add a new task to your list' }}
              </p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="p-2 text-text-400 hover:text-text-600 hover:bg-white/50 rounded-xl transition-all duration-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Form Content -->
      <div class="p-8 overflow-y-auto max-h-[calc(90vh-200px)]">
        <form @submit.prevent="handleSubmit" class="space-y-8">
          <!-- Title Field -->
          <div class="form-group">
            <label for="modal-title" class="flex items-center text-base font-semibold text-text-700 mb-3">
              <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              Task Title *
            </label>
            <input
              id="modal-title"
              v-model="form.title"
              type="text"
              required
              placeholder="What needs to be done?"
              class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70"
              :class="{ 'border-red-300 ring-red-200': errors.title }"
            />
            <transition name="error-fade">
              <p v-if="errors.title" class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ errors.title[0] }}
              </p>
            </transition>
          </div>

          <!-- Description Field -->
          <div class="form-group">
            <label for="modal-description" class="flex items-center text-base font-semibold text-text-700 mb-3">
              <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
              </svg>
              Description
            </label>
            <div class="relative">
              <textarea
                id="modal-description"
                v-model="form.description"
                rows="4"
                placeholder="Add some details about this task..."
                class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none bg-white/70"
                :class="{ 'border-red-300 ring-red-200': errors.description }"
              ></textarea>
              <div class="absolute bottom-3 right-3 text-xs text-text-400 bg-white/80 px-2 py-1 rounded-lg">
                {{ form.description.length }}/500
              </div>
            </div>
            <transition name="error-fade">
              <p v-if="errors.description" class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ errors.description[0] }}
              </p>
            </transition>
          </div>

          <!-- Priority and Status Row -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Priority Field -->
            <div class="form-group">
              <label for="modal-priority" class="flex items-center text-base font-semibold text-text-700 mb-3">
                <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Priority Level
              </label>
              <div class="relative">
                <select
                  id="modal-priority"
                  v-model="form.priority"
                  class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 appearance-none cursor-pointer"
                  :class="{ 'border-red-300 ring-red-200': errors.priority }"
                >
                  <option value="low">🟢 Low Priority</option>
                  <option value="medium">🟡 Medium Priority</option>
                  <option value="high">🔴 High Priority</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <svg class="w-5 h-5 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
              <transition name="error-fade">
                <p v-if="errors.priority" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ errors.priority[0] }}
                </p>
              </transition>
            </div>

            <!-- Status Field -->
            <div class="form-group">
              <label for="modal-status" class="flex items-center text-base font-semibold text-text-700 mb-3">
                <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Task Status
              </label>
              <div class="relative">
                <select
                  id="modal-status"
                  v-model="form.status"
                  class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 appearance-none cursor-pointer"
                  :class="{ 'border-red-300 ring-red-200': errors.status }"
                >
                  <option value="pending">🔄 Pending</option>
                  <option value="completed">✅ Completed</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <svg class="w-5 h-5 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
              <transition name="error-fade">
                <p v-if="errors.status" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ errors.status[0] }}
                </p>
              </transition>
            </div>
          </div>

          <!-- Error Message -->
          <transition name="error-fade">
            <div v-if="errorMessage" class="rounded-2xl bg-red-50 border border-red-200 p-4">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-base font-medium text-red-800">{{ errorMessage }}</h3>
                </div>
              </div>
            </div>
          </transition>
        </form>
      </div>

      <!-- Footer Actions -->
      <div class="bg-background-50/50 px-8 py-6 border-t border-background-200/50 flex justify-end space-x-4">
        <button
          type="button"
          @click="$emit('close')"
          class="px-6 py-3 border border-background-300 text-text-700 font-medium rounded-2xl hover:bg-background-100 focus:ring-2 focus:ring-background-200 transition-all duration-200"
        >
          Cancel
        </button>
        <button
          type="submit"
          @click="handleSubmit"
          :disabled="loading"
          class="px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-primary-700 focus:ring-4 focus:ring-primary-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center"
          :class="{ 'animate-pulse': loading }"
        >
          <transition name="icon-fade" mode="out-in">
            <svg v-if="loading" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </transition>
          <span>{{ loading ? 'Saving Changes...' : (task ? 'Update Task' : 'Create Task') }}</span>
        </button>
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

<style scoped>
/* Animation Transitions */
.error-fade-enter-active,
.error-fade-leave-active {
  transition: all 0.3s ease;
}

.error-fade-enter-from,
.error-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.icon-fade-enter-active,
.icon-fade-leave-active {
  transition: all 0.2s ease;
}

.icon-fade-enter-from,
.icon-fade-leave-to {
  opacity: 0;
  transform: scale(0.8);
}

/* Custom scrollbar for modal content */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: rgba(34, 197, 94, 0.4);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(34, 197, 94, 0.6);
}

/* Focus ring enhancement */
.focus\:ring-2:focus {
  box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
}

/* Backdrop filter support */
@supports (backdrop-filter: blur(12px)) {
  .backdrop-blur-xl {
    backdrop-filter: blur(12px);
  }
  
  .backdrop-blur-sm {
    backdrop-filter: blur(4px);
  }
}

/* Modal entrance animation */
@keyframes modalEnter {
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
  animation: modalEnter 0.3s ease-out;
}
</style>
