<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-12 py-12">
      <!-- Page header -->
      <div class="mb-12 text-left">
        <h1 class="text-3xl font-semibold text-text-900 mb-3">Dashboard</h1>
        <p class="text-text-600 text-lg">
          Welcome back! Here's an overview of your tasks.
        </p>
      </div>
      
      <!-- Stats overview -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div
          v-for="(stat, idx) in [
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
          ]"
          :key="stat.label"
          class="bg-white rounded-xl border border-background-200/60 p-8 hover:border-background-300/80 hover:shadow-lg transition-all duration-200 animate-dashboard-card"
        >
          <div class="flex items-center justify-between mb-4">
            <div
              :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center',
                stat.color === 'primary'
                  ? 'bg-primary-50 text-primary-600'
                  : stat.color === 'accent'
                  ? 'bg-accent-50 text-accent-600'
                  : 'bg-secondary-50 text-secondary-600',
              ]"
            >
              <svg
                v-if="stat.label === 'Total Tasks'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                />
              </svg>
              <svg
                v-else-if="stat.label === 'Completed'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <svg
                v-else-if="stat.label === 'Pending'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <svg
                v-else
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <div class="text-2xl font-bold text-text-900">
              {{ stat.value }}
            </div>
          </div>
          <div class="text-sm text-text-600 font-medium">{{ stat.label }}</div>
          <div v-if="stat.label === 'Completion Rate'" class="w-full mt-3">
            <div
              class="w-full h-1.5 bg-background-100 rounded-full overflow-hidden"
            >
              <div
                class="h-1.5 rounded-full bg-primary-500 transition-all duration-500"
                :style="{
                  width: Math.round(animatedStats.completionRate) + '%',
                }"
              ></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Recent tasks -->
      <div
        class="bg-white rounded-xl border border-background-200/60 shadow-sm animate-dashboard-card"
      >
        <div class="px-8 py-6 border-b border-background-100">
          <h3 class="text-xl font-semibold text-text-900">
            Recent Tasks
          </h3>
        </div>
        <div class="p-8">
        <div v-if="taskStore.loading" class="flex justify-center py-16">
          <div
            class="animate-spin rounded-full h-10 w-10 border-2 border-background-200 border-t-primary-600"
          ></div>
        </div>
        <div v-else-if="taskStore.tasks.length === 0" class="text-center py-16">
          <div class="w-16 h-16 mx-auto mb-6 rounded-xl bg-background-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <p class="text-text-600 mb-6 text-lg">
            No tasks yet. Create your first task to get started!
          </p>
          <router-link
            to="/tasks"
            class="inline-flex items-center px-6 py-3 text-sm font-medium text-primary-600 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors"
            >Create Task</router-link
          >
        </div>
        <div v-else class="space-y-4">
          <div
            v-for="task in recentTasks"
            :key="task.id"
            class="flex items-center justify-between p-5 rounded-xl border border-background-100 hover:border-background-200 hover:shadow-sm transition-all duration-200 animate-dashboard-task"
          >
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <div
                  :class="[
                    'w-3 h-3 rounded-full',
                    task.priority === 'high'
                      ? 'bg-red-500'
                      : task.priority === 'medium'
                      ? 'bg-accent-500'
                      : 'bg-primary-500',
                  ]"
                ></div>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-text-900 truncate">
                  {{ task.title }}
                </p>
                <p class="text-sm text-text-600 truncate">{{ task.description }}</p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <span
                :class="[
                  'px-2 py-1 text-xs font-medium rounded-md',
                  task.status === 'completed'
                    ? 'bg-accent-50 text-accent-600'
                    : 'bg-background-100 text-text-600',
                ]"
              >
                {{ task.status }}
              </span>
            </div>
          </div>
        </div>
        <div class="mt-8 pt-6 border-t border-background-100">
          <router-link
            to="/tasks"
            class="w-full flex justify-center items-center px-6 py-3 text-sm font-medium text-text-700 bg-background-50 border border-background-200 rounded-xl hover:bg-background-100 hover:border-background-300 transition-all duration-200"
            >View All Tasks</router-link
          >
        </div>
        </div>
      </div>
    </div>
  </div>
</template><script setup>
import { computed, onMounted, ref, nextTick } from "vue";
import { useTaskStore } from "../stores/task";
import gsap from "gsap";

const taskStore = useTaskStore();
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
.animate-dashboard-card {
  animation: dashboard-card-fadein 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes dashboard-card-fadein {
  0% {
    opacity: 0;
    transform: translateY(30px) scale(0.98);
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
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
