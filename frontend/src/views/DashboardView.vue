<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-full px-6 sm:px-8 lg:px-12 xl:px-16 py-12">
      <!-- Page header -->
      <div class="dashboard-header text-left max-w-7xl">
        <h1 class="text-4xl font-semibold text-text-900 mb-4">Dashboard</h1>
        <p class="text-text-600 text-xl">
          Welcome back! Here's an overview of your tasks.
        </p>
      </div>

      <!-- Stats overview -->
      <div class="dashboard-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-full mx-auto">
        <div v-for="(stat, idx) in [
          {
            label: 'Total Tasks',
            value: Math.round(animatedStats.total),
            color: 'primary',
          },
          {
            label: 'Completed',
            value: Math.round(animatedStats.completed),
            color: 'accent',
          },
          {
            label: 'Pending',
            value: Math.round(animatedStats.pending),
            color: 'secondary',
          },
          {
            label: 'Completion Rate',
            value: Math.round(animatedStats.completionRate) + '%',
            color: 'primary',
          },
        ]" :key="stat.label"
          class="bg-white rounded-2xl border border-background-200/60 p-10 hover:border-primary-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-dashboard-card group cursor-pointer">
          <div class="flex items-center justify-between mb-6">
            <div :class="[
              'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110',
              stat.color === 'primary'
                ? 'bg-primary-50 text-primary-600 group-hover:bg-primary-100'
                : stat.color === 'accent'
                  ? 'bg-accent-50 text-accent-600 group-hover:bg-accent-100'
                  : 'bg-secondary-50 text-secondary-600 group-hover:bg-secondary-100',
            ]">
              <svg v-if="stat.label === 'Total Tasks'" class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <svg v-else-if="stat.label === 'Completed'" class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else-if="stat.label === 'Pending'" class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <div class="text-3xl font-black text-text-900 group-hover:text-primary-600 transition-colors duration-300">
              {{ stat.value }}
            </div>
          </div>
          <div class="text-base text-text-600 font-medium group-hover:text-text-700 transition-colors duration-300">
            {{ stat.label }}
          </div>
          <div v-if="stat.label === 'Completion Rate'" class="w-full mt-3">
            <div class="w-full h-1.5 bg-background-100 rounded-full overflow-hidden">
              <div class="h-1.5 rounded-full bg-primary-500 transition-all duration-500" :style="{
                width: Math.round(animatedStats.completionRate) + '%',
              }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent tasks -->
      <div class="max-w-full mx-auto">
        <div
          class="bg-white rounded-2xl border border-background-200/60 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-dashboard-card">
          <div class="px-10 py-8 border-b border-background-100">
            <h3 class="text-2xl font-semibold text-text-900">Recent Tasks</h3>
            <p class="text-text-600 mt-2">Your latest task activity</p>
          </div>
          <div class="p-10">
            <div v-if="taskStore.loading" class="flex justify-center py-16">
              <div class="animate-spin rounded-full h-10 w-10 border-2 border-background-200 border-t-primary-600">
              </div>
            </div>
            <div v-else-if="taskStore.tasks.length === 0" class="text-center py-20">
              <div
                class="w-20 h-20 mx-auto mb-8 rounded-2xl bg-background-100 flex items-center justify-center group hover:bg-primary-50 transition-colors duration-300">
                <svg class="w-10 h-10 text-text-400 group-hover:text-primary-500 transition-colors duration-300"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <p class="text-text-600 mb-8 text-xl">
                No tasks yet. Create your first task to get started!
              </p>
              <router-link to="/tasks"
                class="inline-flex items-center px-8 py-4 text-base font-semibold text-primary-600 bg-primary-50 rounded-2xl hover:bg-primary-100 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">Create
                Task</router-link>
            </div>
            <div v-else class="space-y-4 flex flex-col gap-2">
              <div v-for="task in recentTasks" :key="task.id" @click="openTaskModal(task)"
                class="group relative overflow-hidden bg-white border border-background-100/80 rounded-2xl hover:border-primary-200/60 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 animate-dashboard-task cursor-pointer"
                style="backdrop-filter: blur(10px)">
                <!-- Priority indicator with improved styling -->
                <div class="absolute top-0 left-0 w-1 h-full transition-all duration-300" :class="[
                  task.priority === 'high'
                    ? 'bg-gradient-to-b from-red-500 to-red-600 group-hover:from-red-400 group-hover:to-red-500'
                    : task.priority === 'medium'
                      ? 'bg-gradient-to-b from-accent-500 to-accent-600 group-hover:from-accent-400 group-hover:to-accent-500'
                      : 'bg-gradient-to-b from-primary-500 to-primary-600 group-hover:from-primary-400 group-hover:to-primary-500',
                ]"></div>

                <!-- Card content with proper alignment -->
                <div class="p-6 pl-8">
                  <!-- Header section with title and priority badge -->
                  <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4 flex-1 min-w-0 gap-2">
                      <!-- Priority dot indicator -->
                      <div class="flex-shrink-0">
                        <div class="relative">
                          <div :class="[
                            'w-3 h-3 rounded-full transition-all duration-300 group-hover:scale-125',
                            task.priority === 'high'
                              ? 'bg-red-500 group-hover:bg-red-600 shadow-red-200'
                              : task.priority === 'medium'
                                ? 'bg-accent-500 group-hover:bg-accent-600 shadow-accent-200'
                                : 'bg-primary-500 group-hover:bg-primary-600 shadow-primary-200',
                          ]"></div>
                          <div :class="[
                            'absolute inset-0 rounded-full opacity-0 group-hover:opacity-30 transition-all duration-300 animate-ping',
                            task.priority === 'high'
                              ? 'bg-red-400'
                              : task.priority === 'medium'
                                ? 'bg-accent-400'
                                : 'bg-primary-400',
                          ]"></div>
                        </div>
                      </div>

                      <!-- Task title -->
                      <h4
                        class="text-lg font-semibold text-text-900 truncate group-hover:text-primary-700 transition-colors duration-300 leading-tight flex-1">
                        {{ task.title }}
                      </h4>
                    </div>

                    <!-- Priority badge aligned to the right -->
                    <div class="flex items-center space-x-2 ml-4">
                      <!-- Quick action buttons -->
                      <div
                        class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <!-- Mark as complete button -->
                        <button v-if="task.status !== 'completed'" @click.stop="markAsComplete(task)"
                          class="p-2 text-text-400 hover:text-accent-600 hover:bg-accent-50 rounded-lg transition-all duration-200 tooltip"
                          title="Mark as complete">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </button>

                        <!-- Edit button -->
                        <button @click.stop="openTaskModal(task)"
                          class="p-2 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 tooltip"
                          title="Edit task">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </button>
                      </div>

                      <span :class="[
                        'px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-300 whitespace-nowrap',
                        task.priority === 'high'
                          ? 'bg-red-50 text-red-700 group-hover:bg-red-100 border border-red-100'
                          : task.priority === 'medium'
                            ? 'bg-accent-50 text-accent-700 group-hover:bg-accent-100 border border-accent-100'
                            : 'bg-primary-50 text-primary-700 group-hover:bg-primary-100 border border-primary-100',
                      ]">
                        {{
                          task.priority.charAt(0).toUpperCase() +
                          task.priority.slice(1)
                        }}
                      </span>

                      <!-- Action hint -->
                      <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex-shrink-0">
                        <svg class="w-4 h-4 text-text-400 group-hover:text-primary-500 transition-colors duration-300"
                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <!-- Description section -->
                  <div class="mb-4 ml-7">
                    <p
                      class="text-sm text-text-600 line-clamp-2 group-hover:text-text-700 transition-colors duration-300 leading-relaxed">
                      {{ task.description }}
                    </p>
                  </div>

                  <!-- Footer section with status badge -->
                  <div class="flex items-center justify-between ml-7">
                    <div class="flex items-center">
                      <!-- Status badge with improved styling -->
                      <span :class="[
                        'inline-flex gap-1 items-center px-3 py-1.5 text-xs font-semibold rounded-full transition-all duration-300 shadow-sm',
                        task.status === 'completed'
                          ? 'bg-gradient-to-r from-accent-50 to-accent-100 text-accent-800 group-hover:from-accent-100 group-hover:to-accent-200 border border-accent-200'
                          : task.status === 'in_progress'
                            ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 group-hover:from-blue-100 group-hover:to-blue-200 border border-blue-200'
                            : 'bg-gradient-to-r from-background-50 to-background-100 text-text-700 group-hover:from-background-100 group-hover:to-background-200 border border-background-200',
                      ]">
                        <div :class="[
                          'w-1.5 h-1.5 rounded-full',
                          task.status === 'completed'
                            ? 'bg-accent-500'
                            : task.status === 'in_progress'
                              ? 'bg-blue-500'
                              : 'bg-text-400',
                        ]"></div>
                        {{
                          task.status.charAt(0).toUpperCase() +
                          task.status.slice(1).replace("_", " ")
                        }}
                      </span>
                    </div>

                    <!-- Additional metadata could go here -->
                    <div
                      class="text-xs text-text-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                      Click to view details
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-10 pt-8 border-t border-background-100">
              <router-link to="/tasks"
                class="w-full flex justify-center items-center px-8 py-4 text-base font-semibold text-text-700 bg-background-50 border border-background-200 rounded-2xl hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">View
                All Tasks</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Task Modal -->
    <TaskModal v-if="showTaskModal" :task="selectedTask" @close="closeTaskModal" @save="handleTaskSave" />
  </div>
</template>
<script setup>
import { computed, onMounted, ref, nextTick } from "vue";
import { useTaskStore } from "../stores/task";
import TaskModal from "../components/TaskModal.vue";
import gsap from "gsap";

const taskStore = useTaskStore();
const showTaskModal = ref(false);
const selectedTask = ref(null);

const animatedStats = ref({
  total: 0,
  completed: 0,
  pending: 0,
  completionRate: 0,
});

const completionRate = computed(() => {
  if (taskStore.taskStats.total === 0) return 0;
  return Math.round(
    (taskStore.taskStats.completed / taskStore.taskStats.total) * 100
  );
});

const recentTasks = computed(() => {
  return taskStore.tasks.slice(0, 5);
});

const animateStats = () => {
  gsap.to(animatedStats.value, {
    duration: 1.2,
    total: taskStore.taskStats.total,
    completed: taskStore.taskStats.completed,
    pending: taskStore.taskStats.pending,
    completionRate: completionRate.value,
    ease: "power2.out",
    onUpdate: () => {
      animatedStats.value = { ...animatedStats.value };
    },
  });
};

// Modal functionality
const openTaskModal = (task) => {
  selectedTask.value = task;
  showTaskModal.value = true;
};

const closeTaskModal = () => {
  showTaskModal.value = false;
  selectedTask.value = null;
};

const handleTaskSave = async (taskData) => {
  try {
    if (selectedTask.value) {
      // Update existing task
      await taskStore.updateTask(selectedTask.value.id, taskData);
    } else {
      // Create new task (though this won't be used from dashboard)
      await taskStore.createTask(taskData);
    }
    closeTaskModal();
    // Refresh stats animation
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to save task:", error);
    // You could add a toast notification here
  }
};

const markAsComplete = async (task) => {
  try {
    await taskStore.updateTask(task.id, {
      ...task,
      status: "completed",
    });
    // Refresh stats animation
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to mark task as complete:", error);
  }
};

onMounted(async () => {
  try {
    await taskStore.fetchTasks();
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to fetch tasks:", error);
  }
});
</script>

<style scoped>
.dashboard-header,
.dashboard-stats {
  margin-bottom: 1rem;
}

.animate-dashboard-card {
  animation: dashboard-card-fadein 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dashboard-card-fadein {
  0% {
    opacity: 0;
    transform: translateY(40px) scale(0.96);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.animate-dashboard-task {
  animation: dashboard-task-fadein 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dashboard-task-fadein {
  0% {
    opacity: 0;
    transform: translateY(25px) scale(0.98);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Elegant hover animations */
.group:hover {
  transform: translateY(-2px);
}

.group:active {
  transform: translateY(0);
}

/* Line clamp utility for description text */
.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
}

/* Enhanced card shadows and effects */
.group:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08),
    0 0 0 1px rgba(59, 130, 246, 0.05);
}

/* Subtle glassmorphism effect */
.group::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg,
      transparent,
      rgba(255, 255, 255, 0.4),
      transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.group:hover::before {
  opacity: 1;
}

/* Tooltip styles for action buttons */
.tooltip {
  position: relative;
}

.tooltip:hover::after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  white-space: nowrap;
  z-index: 10;
  opacity: 1;
  pointer-events: none;
}

.tooltip:hover::before {
  content: "";
  position: absolute;
  bottom: 94%;
  left: 50%;
  transform: translateX(-50%);
  border: 4px solid transparent;
  border-top-color: rgba(0, 0, 0, 0.8);
  z-index: 10;
}
</style>
