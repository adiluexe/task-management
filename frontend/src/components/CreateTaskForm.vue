<template>
  <div class="bg-white rounded-lg border border-background-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-background-100">
      <div class="flex items-center">
        <div
          class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center mr-3 shadow-lg"
        >
          <Icon icon="lucide:plus" class="w-5 h-5 text-white" />
        </div>
        <h2 class="text-lg font-semibold text-text-900">Create New Task</h2>
      </div>
    </div>

    <div class="p-6">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Title Field -->
        <div>
          <label
            for="task-title"
            class="flex items-center text-base font-semibold text-text-700 mb-3"
          >
            <Icon icon="lucide:tag" class="w-5 h-5 mr-3 text-primary-500" />
            Task Title *
          </label>
          <input
            id="task-title"
            v-model="form.title"
            type="text"
            placeholder="What needs to be done?"
            required
            class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 hover:border-primary-300"
            :class="{ 'border-red-300 ring-red-200': errors.title }"
            @input="validateField('title')"
          />
          <transition name="error-fade">
            <p
              v-if="errors.title"
              class="text-red-600 text-sm mt-2 flex items-center"
            >
              <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
              {{ errors.title[0] }}
            </p>
          </transition>
        </div>

        <!-- Description Field -->
        <div>
          <label
            for="task-description"
            class="flex items-center text-base font-semibold text-text-700 mb-3"
          >
            <Icon
              icon="lucide:align-left"
              class="w-5 h-5 mr-3 text-primary-500"
            />
            Description
          </label>
          <div class="relative">
            <textarea
              id="task-description"
              v-model="form.description"
              rows="4"
              placeholder="Add some details about this task..."
              class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none bg-white/70 hover:border-primary-300"
              :class="{ 'border-red-300 ring-red-200': errors.description }"
              @input="validateField('description')"
            ></textarea>
            <div
              class="absolute bottom-3 right-3 text-xs text-text-400 bg-white/80 px-2 py-1 rounded-lg"
            >
              {{ form.description.length }}/500
            </div>
          </div>
          <transition name="error-fade">
            <p
              v-if="errors.description"
              class="text-red-600 text-sm mt-2 flex items-center"
            >
              <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
              {{ errors.description[0] }}
            </p>
          </transition>
        </div>

        <!-- Priority Field -->
        <div>
          <label
            class="flex items-center text-base font-semibold text-text-700 mb-4"
          >
            <Icon icon="lucide:flag" class="w-5 h-5 mr-3 text-primary-500" />
            Priority Level
          </label>
          <div class="grid grid-cols-3 gap-3">
            <div
              v-for="priority in priorities"
              :key="priority.value"
              @click="form.priority = priority.value"
              :class="[
                'relative cursor-pointer rounded-2xl border-2 p-4 transition-all duration-200 hover:scale-105',
                form.priority === priority.value
                  ? getPriorityStyles(priority.value).border + ' ring-4 ring-opacity-20'
                  : 'border-background-300 bg-white/50 hover:border-background-400',
              ]"
            >
              <div class="flex flex-col items-center space-y-2">
                <div
                  :class="[
                    'w-4 h-4 rounded-full',
                    getPriorityStyles(priority.value).dot,
                  ]"
                ></div>
                <span
                  :class="[
                    'text-sm font-medium text-center',
                    form.priority === priority.value
                      ? getPriorityStyles(priority.value).text
                      : 'text-text-600',
                  ]"
                >
                  {{ priority.label }}
                </span>
              </div>
              <div
                v-if="form.priority === priority.value"
                class="absolute -top-2 -right-2 bg-primary-500 text-white rounded-full p-1"
              >
                <Icon icon="lucide:check" class="w-3 h-3" />
              </div>
            </div>
          </div>
          <transition name="error-fade">
            <p
              v-if="errors.priority"
              class="text-red-600 text-sm mt-2 flex items-center"
            >
              <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
              {{ errors.priority[0] }}
            </p>
          </transition>
        </div>

        <!-- Due Date Field -->
        <div>
          <label
            for="task-due-date"
            class="flex items-center text-base font-semibold text-text-700 mb-3"
          >
            <Icon
              icon="lucide:calendar"
              class="w-5 h-5 mr-3 text-primary-500"
            />
            Due Date (Optional)
          </label>
          <div class="relative">
            <input
              id="task-due-date"
              v-model="form.due_date"
              type="date"
              class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 hover:border-primary-300"
              :min="today"
              @focus="focusedField = 'due_date'"
              @blur="focusedField = null"
            />
            <Icon
              icon="lucide:calendar"
              class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-text-400 pointer-events-none"
            />
          </div>
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
              <Icon
                v-if="loading"
                icon="lucide:loader-2"
                class="animate-spin w-6 h-6"
              />
              <Icon v-else icon="lucide:plus" class="w-6 h-6" />
            </transition>
            <span class="ml-3 text-lg">
              {{ loading ? "Creating Task..." : "Create Task" }}
            </span>
          </button>

          <button
            type="button"
            @click="resetForm"
            class="w-full px-6 py-3 border border-background-300 text-text-700 font-medium rounded-2xl hover:bg-background-50 focus:ring-2 focus:ring-background-200 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center hover:scale-[1.02]"
            :disabled="loading"
          >
            <Icon icon="lucide:rotate-ccw" class="w-5 h-5 mr-2" />
            Reset Form
          </button>
        </div>

        <!-- Success/Error Messages -->
        <transition name="message-fade">
          <div v-if="successMessage" class="success-message">
            <Icon icon="lucide:check-circle" class="w-5 h-5 mr-2" />
            {{ successMessage }}
          </div>
        </transition>

        <transition name="message-fade">
          <div v-if="errorMessage" class="error-message-box">
            <Icon icon="lucide:alert-triangle" class="w-5 h-5 mr-2" />
            {{ errorMessage }}
          </div>
        </transition>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Icon } from "@iconify/vue";
import { useTaskStore } from "../stores/task";
import gsap from "gsap";

const taskStore = useTaskStore();

// Form state
const form = ref({
  title: "",
  description: "",
  priority: "medium",
  due_date: "",
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref("");
const errorMessage = ref("");
const focusedField = ref(null);

// Priority options
const priorities = [
  {
    value: "low",
    label: "Low Priority",
    icon: "🟢",
    description: "Not urgent, can be done later",
  },
  {
    value: "medium",
    label: "Medium Priority",
    icon: "🟡",
    description: "Important, should be done soon",
  },
  {
    value: "high",
    label: "High Priority",
    icon: "🔴",
    description: "Urgent, needs immediate attention",
  },
];

// Helper methods for styling
const getPriorityStyles = (priority) => {
  const styles = {
    low: {
      dot: "bg-green-500",
      bg: "bg-green-50",
      border: "border-green-300",
      text: "text-green-700",
      ring: "ring-green-200",
    },
    medium: {
      dot: "bg-yellow-500",
      bg: "bg-yellow-50",
      border: "border-yellow-300",
      text: "text-yellow-700",
      ring: "ring-yellow-200",
    },
    high: {
      dot: "bg-red-500",
      bg: "bg-red-50",
      border: "border-red-300",
      text: "text-red-700",
      ring: "ring-red-200",
    },
  };
  return styles[priority] || styles.medium;
};

// Computed properties
const today = computed(() => {
  return new Date().toISOString().split("T")[0];
});

const isFormValid = computed(() => {
  return (
    form.value.title.trim().length > 0 &&
    form.value.title.trim().length <= 255 &&
    form.value.description.length <= 500
  );
});

const formProgress = computed(() => {
  let progress = 0;
  if (form.value.title.trim()) progress += 40;
  if (form.value.description.trim()) progress += 30;
  if (form.value.priority) progress += 30;
  return Math.min(progress, 100);
});

// Emit events
const emit = defineEmits(["task-created"]);

// Methods
const validateField = (field) => {
  errors.value[field] = [];

  switch (field) {
    case "title":
      if (!form.value.title.trim()) {
        errors.value[field] = ["Title is required"];
      } else if (form.value.title.length > 255) {
        errors.value[field] = ["Title must be less than 255 characters"];
      }
      break;
    case "description":
      if (form.value.description.length > 500) {
        errors.value[field] = ["Description must be less than 500 characters"];
      }
      break;
  }
};

const resetForm = () => {
  // Animate form reset
  gsap.to(".form-group", {
    scale: 0.95,
    duration: 0.2,
    yoyo: true,
    repeat: 1,
    ease: "power2.inOut",
  });

  setTimeout(() => {
    form.value = {
      title: "",
      description: "",
      priority: "medium",
      due_date: "",
    };
    errors.value = {};
    successMessage.value = "";
    errorMessage.value = "";
  }, 200);
};

const handleSubmit = async () => {
  loading.value = true;
  errors.value = {};
  errorMessage.value = "";

  try {
    // Validate all fields
    validateField("title");
    validateField("description");

    // Check if there are any errors
    const hasErrors = Object.values(errors.value).some(
      (fieldErrors) => fieldErrors.length > 0
    );
    if (hasErrors) {
      loading.value = false;
      return;
    }

    // Submit to store
    await taskStore.createTask(form.value);

    // Success feedback
    successMessage.value = "Task created successfully! 🎉";

    // Animate success
    gsap.fromTo(
      ".success-message",
      { scale: 0.5, opacity: 0 },
      { scale: 1, opacity: 1, duration: 0.5, ease: "power2.out" }
    );

    // Reset form after delay
    setTimeout(() => {
      resetForm();
      successMessage.value = "";
    }, 2000);

    // Emit event for parent component
    emit("task-created");
  } catch (error) {
    console.error("Failed to create task:", error);

    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value =
        error.response?.data?.message ||
        "Failed to create task. Please try again.";
    }

    // Animate error
    gsap.fromTo(
      ".error-message-box",
      { x: -10, opacity: 0 },
      { x: 0, opacity: 1, duration: 0.3, ease: "power2.out" }
    );
  } finally {
    loading.value = false;
  }
};

// Watch for form changes to clear messages
watch(
  form,
  () => {
    if (successMessage.value || errorMessage.value) {
      successMessage.value = "";
      errorMessage.value = "";
    }
  },
  { deep: true }
);

// Animation on mount
onMounted(() => {
  gsap.fromTo(
    ".create-task-form",
    { opacity: 0, y: 20 },
    { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }
  );

  gsap.fromTo(
    ".form-group",
    { opacity: 0, x: -20 },
    {
      opacity: 1,
      x: 0,
      duration: 0.4,
      stagger: 0.1,
      delay: 0.2,
      ease: "power2.out",
    }
  );
});
</script>

<style scoped>
/* Main Form Container */
.create-task-form {
  background: linear-gradient(
    135deg,
    rgba(255, 255, 255, 0.9) 0%,
    rgba(255, 255, 255, 0.8) 100%
  );
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

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid rgb(229 231 235);
  border-radius: 0.75rem;
  font-size: 0.875rem;
  color: rgb(17 24 39);
  background: rgba(255, 255, 255, 0.8);
  transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: rgb(34 197 94);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
  transform: translateY(-1px);
}

.form-input.error,
.form-textarea.error {
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

/* Priority and Status Selection Cards */
.form-group [class*="grid"] > div {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-group [class*="grid"] > div:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Enhanced Input Focus States */
input:focus,
textarea:focus {
  transform: translateY(-1px);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1),
    0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Date Input Styling */
input[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
}

/* Selection Ring Animation */
@keyframes ringPulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.7;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.ring-4 {
  animation: ringPulse 0.3s ease-out;
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
.error-fade-enter-active,
.error-fade-leave-active {
  transition: all 0.3s ease;
}

.error-fade-enter-from,
.error-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.message-fade-enter-active,
.message-fade-leave-active {
  transition: all 0.4s ease;
}

.message-fade-enter-from,
.message-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
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
