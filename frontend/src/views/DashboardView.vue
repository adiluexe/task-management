<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
      <!-- Page header -->
      <div class="mb-10">
        <h1 class="text-3xl font-bold text-text-900 mb-1">Dashboard</h1>
        <p class="text-sm text-text-600">
          Welcome back! Here's an overview of your tasks.
        </p>
      </div>
      <!-- Stats overview -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
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
          class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-background-200 p-6 flex flex-col items-center hover:shadow-2xl transition-shadow duration-300 animate-dashboard-card"
        >
          <div
            :class="[
              'w-12 h-12 rounded-full flex items-center justify-center mb-3',
              stat.color === 'primary'
                ? 'bg-primary-500/90'
                : stat.color === 'accent'
                ? 'bg-accent-500/90'
                : 'bg-secondary-500/90',
            ]"
          >
            <svg
              v-if="stat.label === 'Total Tasks'"
              class="w-6 h-6 text-white"
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
              class="w-6 h-6 text-white"
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
              class="w-6 h-6 text-white"
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
              class="w-6 h-6 text-white"
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
          <div class="text-lg font-semibold text-text-900 mb-1">
            {{ stat.value }}
          </div>
          <div class="text-sm text-text-500">{{ stat.label }}</div>
          <div v-if="stat.label === 'Completion Rate'" class="w-full mt-3">
            <div
              class="w-full h-2 bg-background-200 rounded-full overflow-hidden"
            >
              <div
                class="h-2 rounded-full bg-primary-500 transition-all duration-500"
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
        class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-background-200 animate-dashboard-card"
      >
        <div class="px-6 py-7">
          <h3 class="text-lg leading-6 font-semibold text-text-900 mb-5">
            Recent Tasks
          </h3>
          <div v-if="taskStore.loading" class="flex justify-center py-8">
            <div
              class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
            ></div>
          </div>
          <div
            v-else-if="taskStore.tasks.length === 0"
            class="text-center py-8"
          >
            <p class="text-text-500">
              No tasks yet. Create your first task to get started!
            </p>
            <router-link
              to="/tasks"
              class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 transition-colors"
              >Create Task</router-link
            >
          </div>
          <div v-else class="space-y-3">
            <div
              v-for="task in recentTasks"
              :key="task.id"
              class="flex items-center justify-between p-3 bg-background-50 rounded-lg border border-background-200 hover:shadow-md transition-shadow duration-200 animate-dashboard-task"
            >
              <div class="flex items-center space-x-3">
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
                <div>
                  <p class="text-sm font-medium text-text-900">
                    {{ task.title }}
                  </p>
                  <p class="text-sm text-text-500">{{ task.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <span
                  :class="[
                    'px-2 py-1 text-xs font-medium rounded-full',
                    task.status === 'completed'
                      ? 'bg-accent-100 text-accent-800'
                      : 'bg-secondary-100 text-secondary-800',
                  ]"
                >
                  {{ task.status }}
                </span>
              </div>
            </div>
          </div>
          <div class="mt-7">
            <router-link
              to="/tasks"
              class="w-full flex justify-center items-center px-4 py-2 border border-background-300 shadow-sm text-sm font-medium rounded-md text-text-700 bg-white hover:bg-background-50 transition-colors"
              >View All Tasks</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
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
