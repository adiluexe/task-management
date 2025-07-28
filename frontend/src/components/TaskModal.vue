<template>
  <!-- Modern Modal Backdrop with Enhanced Blur -->
  <div
    class="fixed inset-0 bg-gradient-to-br from-black/40 via-black/60 to-black/50 backdrop-blur-md z-50 flex items-center justify-center p-4 animate-fadeIn"
    @click.self="$emit('close')"
  >
    <!-- Modal Container with Enhanced Animation -->
    <div
      class="bg-white/98 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/70 w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all duration-500 ease-out animate-modalEnter hover:shadow-3xl"
      @click.stop
    >
      <!-- Header Section -->
      <div
        class="bg-gradient-to-r from-primary-50 to-primary-100 px-8 py-6 border-b border-primary-200/50"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div
              class="bg-gradient-to-br from-primary-500 to-primary-600 p-3 rounded-2xl shadow-lg"
            >
              <Icon icon="lucide:plus" class="w-6 h-6 text-white" />
            </div>
            <div>
              <h3 class="text-2xl font-bold text-text-900">
                {{ task ? "Edit Task" : "Create New Task" }}
              </h3>
              <p class="text-primary-600 text-sm">
                {{
                  task
                    ? "Update your task details"
                    : "Add a new task to your list"
                }}
              </p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="p-2 text-text-400 hover:text-text-600 hover:bg-white/50 rounded-xl transition-all duration-200 hover:scale-110"
          >
            <Icon icon="lucide:x" class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Form Content -->
      <div class="p-8 overflow-y-auto max-h-[calc(90vh-200px)]">
        <form @submit.prevent="handleSubmit" class="space-y-8">
          <!-- Title Field -->
          <div class="form-group">
            <label
              for="modal-title"
              class="flex items-center text-base font-semibold text-text-700 mb-3"
            >
              <Icon icon="lucide:tag" class="w-5 h-5 mr-3 text-primary-500" />
              Task Title *
            </label>
            <input
              id="modal-title"
              v-model="form.title"
              type="text"
              required
              placeholder="What needs to be done?"
              class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 hover:border-primary-300"
              :class="{ 'border-red-300 ring-red-200': errors.title }"
            />
            <transition name="error-fade">
              <p
                v-if="errors.title"
                class="mt-2 text-sm text-red-600 flex items-center"
              >
                <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
                {{ errors.title[0] }}
              </p>
            </transition>
          </div>

          <!-- Description Field -->
          <div class="form-group">
            <label
              for="modal-description"
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
                id="modal-description"
                v-model="form.description"
                rows="4"
                placeholder="Add some details about this task..."
                class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none bg-white/70 hover:border-primary-300"
                :class="{ 'border-red-300 ring-red-200': errors.description }"
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
                class="mt-2 text-sm text-red-600 flex items-center"
              >
                <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
                {{ errors.description[0] }}
              </p>
            </transition>
          </div>

          <!-- Priority Field -->
          <div class="form-group">
            <label
              class="flex items-center text-base font-semibold text-text-700 mb-4"
            >
              <Icon
                icon="lucide:flag"
                class="w-5 h-5 mr-3 text-primary-500"
              />
              Priority Level
            </label>
            <div class="grid grid-cols-3 gap-3">
              <div
                v-for="priority in ['low', 'medium', 'high']"
                :key="priority"
                @click="form.priority = priority"
                :class="[
                  'relative cursor-pointer rounded-2xl border-2 p-4 transition-all duration-200 hover:scale-105',
                  form.priority === priority
                    ? getPriorityStyles(priority).border + ' ring-4 ring-opacity-20'
                    : 'border-background-300 bg-white/50 hover:border-background-400',
                ]"
              >
                <div class="flex flex-col items-center space-y-2">
                  <div
                    :class="[
                      'w-4 h-4 rounded-full',
                      getPriorityStyles(priority).dot,
                    ]"
                  ></div>
                  <span
                    :class="[
                      'text-sm font-medium capitalize text-center',
                      form.priority === priority
                        ? getPriorityStyles(priority).text
                        : 'text-text-600',
                    ]"
                  >
                    {{ priority }} Priority
                  </span>
                </div>
                <div
                  v-if="form.priority === priority"
                  class="absolute -top-2 -right-2 bg-primary-500 text-white rounded-full p-1"
                >
                  <Icon icon="lucide:check" class="w-3 h-3" />
                </div>
              </div>
            </div>
            <transition name="error-fade">
              <p
                v-if="errors.priority"
                class="mt-2 text-sm text-red-600 flex items-center"
              >
                <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
                {{ errors.priority[0] }}
              </p>
            </transition>
          </div>

          <!-- Due Date Field -->
          <div class="form-group">
            <label
              for="modal-due-date"
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
                id="modal-due-date"
                v-model="form.due_date"
                type="date"
                class="w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/70 hover:border-primary-300"
                :class="{ 'border-red-300 ring-red-200': errors.due_date }"
              />
              <Icon
                icon="lucide:calendar"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-text-400 pointer-events-none"
              />
            </div>
            <transition name="error-fade">
              <p
                v-if="errors.due_date"
                class="mt-2 text-sm text-red-600 flex items-center"
              >
                <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
                {{ errors.due_date[0] }}
              </p>
            </transition>
          </div>

          <!-- Status Field (only show when editing) -->
          <div v-if="task" class="form-group">
            <label
              class="flex items-center text-base font-semibold text-text-700 mb-4"
            >
              <Icon
                icon="lucide:activity"
                class="w-5 h-5 mr-3 text-primary-500"
              />
              Task Status
            </label>
            <div class="grid grid-cols-3 gap-3">
              <div
                v-for="status in ['pending', 'in_progress', 'completed']"
                :key="status"
                @click="form.status = status"
                :class="[
                  'relative cursor-pointer rounded-2xl border-2 p-4 transition-all duration-200 hover:scale-105',
                  form.status === status
                    ? getStatusStyles(status).border + ' ring-4 ring-opacity-20'
                    : 'border-background-300 bg-white/50 hover:border-background-400',
                ]"
              >
                <div class="flex flex-col items-center space-y-2">
                  <Icon
                    :icon="getStatusIcon(status)"
                    :class="[
                      'w-5 h-5',
                      form.status === status
                        ? getStatusStyles(status).text
                        : 'text-text-500',
                    ]"
                  />
                  <span
                    :class="[
                      'text-sm font-medium text-center',
                      form.status === status
                        ? getStatusStyles(status).text
                        : 'text-text-600',
                    ]"
                  >
                    {{ getStatusLabel(status) }}
                  </span>
                </div>
                <div
                  v-if="form.status === status"
                  class="absolute -top-2 -right-2 bg-primary-500 text-white rounded-full p-1"
                >
                  <Icon icon="lucide:check" class="w-3 h-3" />
                </div>
              </div>
            </div>
            <transition name="error-fade">
              <p
                v-if="errors.status"
                class="mt-2 text-sm text-red-600 flex items-center"
              >
                <Icon icon="lucide:alert-circle" class="w-4 h-4 mr-1" />
                {{ errors.status[0] }}
              </p>
            </transition>
          </div>

          <!-- Error Message -->
          <transition name="error-fade">
            <div
              v-if="errorMessage"
              class="rounded-2xl bg-red-50 border border-red-200 p-4"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <Icon
                    icon="lucide:alert-triangle"
                    class="h-6 w-6 text-red-400"
                  />
                </div>
                <div class="ml-3">
                  <h3 class="text-base font-medium text-red-800">
                    {{ errorMessage }}
                  </h3>
                </div>
              </div>
            </div>
          </transition>
        </form>
      </div>

      <!-- Footer Actions -->
      <div
        class="bg-background-50/50 px-8 py-6 border-t border-background-200/50 flex justify-end space-x-4"
      >
        <button
          type="button"
          @click="$emit('close')"
          class="px-6 py-3 border border-background-300 text-text-700 font-medium rounded-2xl hover:bg-background-100 focus:ring-2 focus:ring-background-200 transition-all duration-200 hover:scale-[1.02]"
        >
          <Icon icon="lucide:x" class="w-4 h-4 mr-2 inline" />
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
            <Icon
              v-if="loading"
              icon="lucide:loader-2"
              class="animate-spin w-5 h-5 mr-2"
            />
            <Icon v-else icon="lucide:check" class="w-5 h-5 mr-2" />
          </transition>
          <span>{{
            loading ? "Saving Changes..." : task ? "Update Task" : "Create Task"
          }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import { Icon } from "@iconify/vue";

const props = defineProps({
  task: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(["close", "save"]);

const loading = ref(false);
const errorMessage = ref("");
const errors = ref({});

const form = reactive({
  title: "",
  description: "",
  priority: "medium",
  status: "pending",
  due_date: "",
});

const handleSubmit = async () => {
  loading.value = true;
  errorMessage.value = "";
  errors.value = {};

  try {
    emit("save", { ...form });
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else {
      errorMessage.value = "An error occurred. Please try again.";
    }
  } finally {
    loading.value = false;
  }
};

// Initialize form with task data if editing
watch(
  () => props.task,
  (newTask) => {
    if (newTask) {
      form.title = newTask.title || "";
      form.description = newTask.description || "";
      form.priority = newTask.priority || "medium";
      form.status = newTask.status || "pending";
      form.due_date = newTask.due_date || "";
    } else {
      // Reset form for new task
      form.title = "";
      form.description = "";
      form.priority = "medium";
      form.status = "pending";
      form.due_date = "";
    }
  },
  { immediate: true }
);

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

const getStatusStyles = (status) => {
  const styles = {
    pending: {
      bg: "bg-gray-50",
      border: "border-gray-300",
      text: "text-gray-700",
      ring: "ring-gray-200",
    },
    in_progress: {
      bg: "bg-blue-50",
      border: "border-blue-300",
      text: "text-blue-700",
      ring: "ring-blue-200",
    },
    completed: {
      bg: "bg-emerald-50",
      border: "border-emerald-300",
      text: "text-emerald-700",
      ring: "ring-emerald-200",
    },
  };
  return styles[status] || styles.pending;
};

const getStatusIcon = (status) => {
  const icons = {
    pending: "lucide:clock",
    in_progress: "lucide:play",
    completed: "lucide:check-circle",
  };
  return icons[status] || "lucide:clock";
};

const getStatusLabel = (status) => {
  const labels = {
    pending: "Pending",
    in_progress: "In Progress",
    completed: "Completed",
  };
  return labels[status] || "Pending";
};
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
    transform: scale(0.9) translateY(-30px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-modalEnter {
  animation: modalEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

.hover\:shadow-3xl:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.transform {
  animation: modalEnter 0.5s ease-out;
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
</style>
