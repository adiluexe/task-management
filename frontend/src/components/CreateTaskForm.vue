<template>
  <div class="bg-white rounded-lg border border-background-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-background-100">
      <div class="flex items-center">
        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
          <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </div>
        <h2 class="text-lg font-semibold text-text-900">Create New Task</h2>
      </div>
    </div>
    
    <div class="p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Title Field -->
        <div>
          <label for="task-title" class="block text-sm font-medium text-text-700 mb-2">
            Task Title *
          </label>
          <input
            id="task-title"
            v-model="form.title"
            type="text"
            placeholder="What needs to be done?"
            required
            class="w-full px-3 py-2.5 border border-background-200 rounded-lg text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
            :class="{ 'border-red-300 ring-red-200': errors.title }"
            @input="validateField('title')"
          />
          <transition name="error-fade">
            <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title[0] }}</p>
          </transition>
        </div>

        <!-- Description Field -->
        <div>
          <label for="task-description" class="block text-sm font-medium text-text-700 mb-2">
            Description
          </label>
          <div class="relative">
            <textarea
              id="task-description"
              v-model="form.description"
              rows="3"
              placeholder="Add some details about this task..."
              class="w-full px-3 py-2.5 border border-background-200 rounded-lg text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none"
              :class="{ 'border-red-300 ring-red-200': errors.description }"
              @input="validateField('description')"
            ></textarea>
            <div class="absolute bottom-2 right-2 text-xs text-text-400">
              {{ form.description.length }}/500
            </div>
          </div>
          <transition name="error-fade">
            <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description[0] }}</p>
          </transition>
        </div>

        <!-- Priority Field -->
        <div>
          <label class="block text-sm font-medium text-text-700 mb-2">
            Priority Level
          </label>
          <div class="grid grid-cols-3 gap-3">
            <div
              v-for="priority in priorities"
              :key="priority.value"
              @click="form.priority = priority.value"
              :class="[
                'p-3 border rounded-lg cursor-pointer transition-all duration-200 text-center',
                form.priority === priority.value
                  ? 'border-primary-500 bg-primary-50'
                  : 'border-background-200 hover:border-background-300'
              ]"
            >
              <div class="text-lg mb-1">{{ priority.icon }}</div>
              <div class="text-sm font-medium text-text-900">{{ priority.label }}</div>
            </div>
          </div>
          <transition name="error-fade">
            <p v-if="errors.priority" class="text-red-600 text-sm mt-1">{{ errors.priority[0] }}</p>
          </transition>
        </div>

        <!-- Due Date Field -->
        <div>
          <label for="task-due-date" class="block text-sm font-medium text-text-700 mb-2">
            Due Date (Optional)
          </label>
          <input
            id="task-due-date"
            v-model="form.due_date"
            type="date"
            class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70"
            :min="today"
            @focus="focusedField = 'due_date'"
            @blur="focusedField = null"
          />
        </div>

        <!-- Submit Button -->
        <div class="form-actions pt-6 border-t border-background-200">
          <button
            type="submit"
            :disabled="loading || !isFormValid"
            class="w-full mb-4 px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-primary-700 focus:ring-4 focus:ring-primary-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center"
            :class="{ 'animate-pulse': loading }"
          >
            <transition name="icon-fade" mode="out-in">
              <svg v-if="loading" class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </transition>
            <span class="ml-3 text-lg">
              {{ loading ? 'Creating Task...' : 'Create Task' }}
            </span>
          </button>
          
          <button
            type="button"
            @click="resetForm"
            class="w-full px-6 py-3 border border-background-300 text-text-700 font-medium rounded-2xl hover:bg-background-50 focus:ring-2 focus:ring-background-200 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
            :disabled="loading"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Reset Form
          </button>
        </div>

        <!-- Success/Error Messages -->
        <transition name="message-fade">
          <div v-if="successMessage" class="success-message">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ successMessage }}
          </div>
        </transition>
        
        <transition name="message-fade">
          <div v-if="errorMessage" class="error-message-box">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ errorMessage }}
          </div>
        </transition>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useTaskStore } from '../stores/task'
import gsap from 'gsap'

const taskStore = useTaskStore()

// Form state
const form = ref({
  title: '',
  description: '',
  priority: 'medium',
  due_date: ''
})

const errors = ref({})
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const focusedField = ref(null)

// Priority options
const priorities = [
  {
    value: 'low',
    label: 'Low Priority',
    icon: '🟢',
    description: 'Not urgent, can be done later'
  },
  {
    value: 'medium',
    label: 'Medium Priority', 
    icon: '🟡',
    description: 'Important, should be done soon'
  },
  {
    value: 'high',
    label: 'High Priority',
    icon: '🔴',
    description: 'Urgent, needs immediate attention'
  }
]

// Computed properties
const today = computed(() => {
  return new Date().toISOString().split('T')[0]
})

const isFormValid = computed(() => {
  return form.value.title.trim().length > 0 && 
         form.value.title.trim().length <= 255 &&
         form.value.description.length <= 500
})

const formProgress = computed(() => {
  let progress = 0
  if (form.value.title.trim()) progress += 40
  if (form.value.description.trim()) progress += 30
  if (form.value.priority) progress += 30
  return Math.min(progress, 100)
})

// Emit events
const emit = defineEmits(['task-created'])

// Methods
const validateField = (field) => {
  errors.value[field] = []
  
  switch (field) {
    case 'title':
      if (!form.value.title.trim()) {
        errors.value[field] = ['Title is required']
      } else if (form.value.title.length > 255) {
        errors.value[field] = ['Title must be less than 255 characters']
      }
      break
    case 'description':
      if (form.value.description.length > 500) {
        errors.value[field] = ['Description must be less than 500 characters']
      }
      break
  }
}

const resetForm = () => {
  // Animate form reset
  gsap.to('.form-group', {
    scale: 0.95,
    duration: 0.2,
    yoyo: true,
    repeat: 1,
    ease: "power2.inOut"
  })
  
  setTimeout(() => {
    form.value = {
      title: '',
      description: '',
      priority: 'medium',
      due_date: ''
    }
    errors.value = {}
    successMessage.value = ''
    errorMessage.value = ''
  }, 200)
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}
  errorMessage.value = ''
  
  try {
    // Validate all fields
    validateField('title')
    validateField('description')
    
    // Check if there are any errors
    const hasErrors = Object.values(errors.value).some(fieldErrors => fieldErrors.length > 0)
    if (hasErrors) {
      loading.value = false
      return
    }
    
    // Submit to store
    await taskStore.createTask(form.value)
    
    // Success feedback
    successMessage.value = 'Task created successfully! 🎉'
    
    // Animate success
    gsap.fromTo('.success-message', 
      { scale: 0.5, opacity: 0 },
      { scale: 1, opacity: 1, duration: 0.5, ease: "power2.out" }
    )
    
    // Reset form after delay
    setTimeout(() => {
      resetForm()
      successMessage.value = ''
    }, 2000)
    
    // Emit event for parent component
    emit('task-created')
    
  } catch (error) {
    console.error('Failed to create task:', error)
    
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to create task. Please try again.'
    }
    
    // Animate error
    gsap.fromTo('.error-message-box',
      { x: -10, opacity: 0 },
      { x: 0, opacity: 1, duration: 0.3, ease: "power2.out" }
    )
    
  } finally {
    loading.value = false
  }
}

// Watch for form changes to clear messages
watch(form, () => {
  if (successMessage.value || errorMessage.value) {
    successMessage.value = ''
    errorMessage.value = ''
  }
}, { deep: true })

// Animation on mount
onMounted(() => {
  gsap.fromTo('.create-task-form',
    { opacity: 0, y: 20 },
    { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }
  )
  
  gsap.fromTo('.form-group',
    { opacity: 0, x: -20 },
    { 
      opacity: 1, 
      x: 0, 
      duration: 0.4, 
      stagger: 0.1, 
      delay: 0.2,
      ease: "power2.out" 
    }
  )
})
</script>

<style scoped>
/* Main Form Container */
.create-task-form {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  overflow: hidden;
}

/* Form Header */
.form-header {
  padding: 1.5rem;
  background: linear-gradient(135deg, rgb(34 197 94) 0%, rgb(22 163 74) 100%);
  color: white;
}

.icon-wrapper {
  width: 2.5rem;
  height: 2.5rem;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
}

.progress-indicator {
  margin-top: 1rem;
  height: 4px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 2px;
  transition: width 0.3s ease;
}

/* Form Content */
.form-content {
  padding: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  font-weight: 600;
  color: rgb(55 65 81);
  margin-bottom: 0.5rem;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid rgb(229 231 235);
  border-radius: 0.75rem;
  font-size: 0.875rem;
  color: rgb(17 24 39);
  background: rgba(255, 255, 255, 0.8);
  transition: all 0.2s ease;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: rgb(34 197 94);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
  transform: translateY(-1px);
}

.form-input.error, .form-textarea.error {
  border-color: rgb(239 68 68);
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.character-count {
  position: absolute;
  bottom: 0.75rem;
  right: 1rem;
  font-size: 0.75rem;
  color: rgb(107 114 128);
  background: rgba(255, 255, 255, 0.9);
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
}

/* Priority Selector */
.priority-selector {
  display: grid;
  gap: 0.75rem;
}

.priority-option {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 2px solid rgb(229 231 235);
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.2s ease;
  background: rgba(255, 255, 255, 0.6);
}

.priority-option:hover {
  border-color: rgb(34 197 94);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.priority-option.active {
  border-color: rgb(34 197 94);
  background: rgba(34, 197, 94, 0.1);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.priority-icon {
  font-size: 1.5rem;
  margin-right: 0.75rem;
}

.priority-info {
  flex: 1;
}

.priority-name {
  font-weight: 600;
  color: rgb(17 24 39);
  margin-bottom: 0.25rem;
}

.priority-desc {
  font-size: 0.75rem;
  color: rgb(107 114 128);
}

/* Form Actions */
.form-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: 2rem;
}

.submit-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.875rem 1.5rem;
  background: linear-gradient(135deg, rgb(34 197 94) 0%, rgb(22 163 74) 100%);
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.reset-btn {
  padding: 0.875rem 1rem;
  background: rgba(107, 114, 128, 0.1);
  color: rgb(107 114 128);
  border: 2px solid rgb(229 231 235);
  border-radius: 0.75rem;
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
}

.reset-btn:hover:not(:disabled) {
  background: rgba(107, 114, 128, 0.2);
  border-color: rgb(107 114 128);
}

/* Messages */
.success-message {
  display: flex;
  align-items: center;
  padding: 1rem;
  background: rgba(34, 197, 94, 0.1);
  color: rgb(22 163 74);
  border-radius: 0.75rem;
  border: 1px solid rgba(34, 197, 94, 0.2);
  font-weight: 500;
}

.error-message {
  color: rgb(239 68 68);
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.error-message-box {
  display: flex;
  align-items: center;
  padding: 1rem;
  background: rgba(239, 68, 68, 0.1);
  color: rgb(220 38 38);
  border-radius: 0.75rem;
  border: 1px solid rgba(239, 68, 68, 0.2);
  font-weight: 500;
}

/* Transitions */
.error-fade-enter-active, .error-fade-leave-active {
  transition: all 0.3s ease;
}

.error-fade-enter-from, .error-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.message-fade-enter-active, .message-fade-leave-active {
  transition: all 0.4s ease;
}

.message-fade-enter-from, .message-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.icon-fade-enter-active, .icon-fade-leave-active {
  transition: all 0.2s ease;
}

.icon-fade-enter-from, .icon-fade-leave-to {
  opacity: 0;
  transform: scale(0.8);
}

/* Responsive */
@media (max-width: 768px) {
  .form-content {
    padding: 1.5rem;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .priority-option {
    padding: 0.75rem;
  }
}
</style>
