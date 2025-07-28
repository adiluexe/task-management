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
      <div class="dashboard-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-full mx-auto">
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
          class="bg-white rounded-2xl border border-background-200/60 p-10 hover:border-primary-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-dashboard-card group cursor-pointer"
        >
          <div class="flex items-center justify-between mb-6">
            <div
              :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110',
                stat.color === 'primary'
                  ? 'bg-primary-50 text-primary-600 group-hover:bg-primary-100'
                  : stat.color === 'accent'
                  ? 'bg-accent-50 text-accent-600 group-hover:bg-accent-100'
                  : 'bg-secondary-50 text-secondary-600 group-hover:bg-secondary-100',
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
            <div class="text-3xl font-black text-text-900 group-hover:text-primary-600 transition-colors duration-300">
              {{ stat.value }}
            </div>
          </div>
          <div class="text-base text-text-600 font-medium group-hover:text-text-700 transition-colors duration-300">{{ stat.label }}</div>
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
      <div class="max-w-full mx-auto">
        <div
          class="bg-white rounded-2xl border border-background-200/60 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-dashboard-card"
        >
          <div class="px-10 py-8 border-b border-background-100">
            <h3 class="text-2xl font-semibold text-text-900">
              Recent Tasks
            </h3>
            <p class="text-text-600 mt-2">Your latest task activity</p>
          </div>
          <div class="p-10">
        <div v-if="taskStore.loading" class="flex justify-center py-16">
          <div
            class="animate-spin rounded-full h-10 w-10 border-2 border-background-200 border-t-primary-600"
          ></div>
        </div>
        <div v-else-if="taskStore.tasks.length === 0" class="text-center py-20">
          <div class="w-20 h-20 mx-auto mb-8 rounded-2xl bg-background-100 flex items-center justify-center group hover:bg-primary-50 transition-colors duration-300">
            <svg class="w-10 h-10 text-text-400 group-hover:text-primary-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <p class="text-text-600 mb-8 text-xl">
            No tasks yet. Create your first task to get started!
          </p>
          <router-link
            to="/tasks"
            class="inline-flex items-center px-8 py-4 text-base font-semibold text-primary-600 bg-primary-50 rounded-2xl hover:bg-primary-100 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300"
            >Create Task</router-link
          >
        </div>
        <div v-else class="space-y-6">
          <div
            v-for="task in recentTasks"
            :key="task.id"
            class="flex items-center justify-between p-6 rounded-2xl border border-background-100 hover:border-primary-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 animate-dashboard-task group cursor-pointer"
          >
            <div class="flex items-center space-x-6">
              <div class="flex-shrink-0">
                <div
                  :class="[
                    'w-4 h-4 rounded-full transition-all duration-300 group-hover:scale-125',
                    task.priority === 'high'
                      ? 'bg-red-500 group-hover:bg-red-600'
                      : task.priority === 'medium'
                      ? 'bg-accent-500 group-hover:bg-accent-600'
                      : 'bg-primary-500 group-hover:bg-primary-600',
                  ]"
                ></div>
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-base font-semibold text-text-900 truncate group-hover:text-primary-600 transition-colors duration-300">
                  {{ task.title }}
                </p>
                <p class="text-sm text-text-600 truncate mt-1 group-hover:text-text-700 transition-colors duration-300">{{ task.description }}</p>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <span
                :class="[
                  'px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-300',
                  task.status === 'completed'
                    ? 'bg-accent-50 text-accent-700 group-hover:bg-accent-100'
                    : 'bg-background-100 text-text-700 group-hover:bg-background-200',
                ]"
              >
                {{ task.status.charAt(0).toUpperCase() + task.status.slice(1) }}
              </span>
            </div>
          </div>
        </div>
        <div class="mt-10 pt-8 border-t border-background-100">
          <router-link
            to="/tasks"
            class="w-full flex justify-center items-center px-8 py-4 text-base font-semibold text-text-700 bg-background-50 border border-background-200 rounded-2xl hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300"
            >View All Tasks</router-link
          >
        </div>
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

.dashboard-header, .dashboard-stats {
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
</style>
