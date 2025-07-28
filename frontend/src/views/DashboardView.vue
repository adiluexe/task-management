<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Page header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-900">Dashboard</h1>
        <p class="mt-2 text-sm text-text-600">Welcome back! Here's an overview of your tasks.</p>
      </div>

      <!-- Stats overview -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-background-200">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-text-500 truncate">Total Tasks</dt>
                  <dd class="text-lg font-medium text-text-900">{{ taskStore.taskStats.total }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-background-200">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-accent-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-text-500 truncate">Completed</dt>
                  <dd class="text-lg font-medium text-text-900">{{ taskStore.taskStats.completed }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-background-200">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-text-500 truncate">Pending</dt>
                  <dd class="text-lg font-medium text-text-900">{{ taskStore.taskStats.pending }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-background-200">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-text-500 truncate">Completion Rate</dt>
                  <dd class="text-lg font-medium text-text-900">{{ completionRate }}%</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent tasks -->
      <div class="bg-white shadow-sm rounded-lg border border-background-200">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-text-900 mb-4">Recent Tasks</h3>
          
          <div v-if="taskStore.loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          </div>
          
          <div v-else-if="taskStore.tasks.length === 0" class="text-center py-8">
            <p class="text-text-500">No tasks yet. Create your first task to get started!</p>
            <router-link
              to="/tasks"
              class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 transition-colors"
            >
              Create Task
            </router-link>
          </div>
          
          <div v-else class="space-y-3">
            <div
              v-for="task in recentTasks"
              :key="task.id"
              class="flex items-center justify-between p-3 bg-background-50 rounded-lg border border-background-200"
            >
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div :class="[
                    'w-3 h-3 rounded-full',
                    task.priority === 'high' ? 'bg-red-500' :
                    task.priority === 'medium' ? 'bg-accent-500' : 'bg-primary-500'
                  ]"></div>
                </div>
                <div>
                  <p class="text-sm font-medium text-text-900">{{ task.title }}</p>
                  <p class="text-sm text-text-500">{{ task.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <span :class="[
                  'px-2 py-1 text-xs font-medium rounded-full',
                  task.status === 'completed' 
                    ? 'bg-accent-100 text-accent-800' 
                    : 'bg-secondary-100 text-secondary-800'
                ]">
                  {{ task.status }}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <router-link
              to="/tasks"
              class="w-full flex justify-center items-center px-4 py-2 border border-background-300 shadow-sm text-sm font-medium rounded-md text-text-700 bg-white hover:bg-background-50 transition-colors"
            >
              View All Tasks
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useTaskStore } from '../stores/task'

const taskStore = useTaskStore()

const completionRate = computed(() => {
  if (taskStore.taskStats.total === 0) return 0
  return Math.round((taskStore.taskStats.completed / taskStore.taskStats.total) * 100)
})

const recentTasks = computed(() => {
  return taskStore.tasks.slice(0, 5)
})

onMounted(async () => {
  try {
    await taskStore.fetchTasks()
  } catch (error) {
    console.error('Failed to fetch tasks:', error)
  }
})
</script>
