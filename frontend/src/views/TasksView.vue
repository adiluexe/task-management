<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Page header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-text-900">Tasks</h1>
          <p class="mt-2 text-sm text-text-600">Manage your tasks and stay organized.</p>
        </div>
        <button
          @click="showCreateModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 transition-colors"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          New Task
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-background-50 p-4 rounded-lg shadow border border-background-200 mb-6">
        <div class="flex flex-wrap gap-4">
          <div>
            <label for="status-filter" class="block text-sm font-medium text-text-700">Status</label>
            <select
              id="status-filter"
              v-model="filters.status"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-background-300 text-text-900 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
            >
              <option value="">All</option>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
            </select>
          </div>
          <div>
            <label for="priority-filter" class="block text-sm font-medium text-text-700">Priority</label>
            <select
              id="priority-filter"
              v-model="filters.priority"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-background-300 text-text-900 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
            >
              <option value="">All</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
          <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-text-700">Search</label>
            <input
              id="search"
              v-model="filters.search"
              type="text"
              placeholder="Search tasks..."
              class="mt-1 block w-full border-background-300 text-text-900 placeholder-text-400 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
            />
          </div>
        </div>
      </div>

      <!-- Tasks list -->
      <div class="bg-background-50 shadow rounded-lg border border-background-200">
        <div v-if="taskStore.loading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>
        
        <div v-else-if="filteredTasks.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-text-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-text-900">No tasks</h3>
          <p class="mt-1 text-sm text-text-500">Get started by creating a new task.</p>
          <div class="mt-6">
            <button
              @click="showCreateModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              New Task
            </button>
          </div>
        </div>

        <div v-else class="divide-y divide-background-200">
          <div
            v-for="task in filteredTasks"
            :key="task.id"
            class="p-6 hover:bg-background-100 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4 flex-1">
                <button
                  @click="toggleTaskStatus(task)"
                  class="flex-shrink-0"
                >
                  <div :class="[
                    'w-5 h-5 rounded border-2 flex items-center justify-center transition-colors',
                    task.status === 'completed' 
                      ? 'bg-primary-500 border-primary-500' 
                      : 'border-background-300 hover:border-background-400'
                  ]">
                    <svg
                      v-if="task.status === 'completed'"
                      class="w-3 h-3 text-white"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </button>
                
                <div class="flex-1">
                  <div class="flex items-center space-x-2">
                    <h3 :class="[
                      'text-sm font-medium',
                      task.status === 'completed' ? 'line-through text-text-500' : 'text-text-900'
                    ]">
                      {{ task.title }}
                    </h3>
                    <span :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      task.priority === 'high' ? 'bg-accent-100 text-accent-800' :
                      task.priority === 'medium' ? 'bg-secondary-100 text-secondary-800' : 'bg-primary-100 text-primary-800'
                    ]">
                      {{ task.priority }}
                    </span>
                  </div>
                  <p :class="[
                    'mt-1 text-sm',
                    task.status === 'completed' ? 'line-through text-text-400' : 'text-text-600'
                  ]">
                    {{ task.description }}
                  </p>
                  <p class="mt-1 text-xs text-text-500">
                    Created {{ formatDate(task.created_at) }}
                  </p>
                </div>
              </div>

              <div class="flex items-center space-x-2">
                <button
                  @click="editTask(task)"
                  class="text-text-400 hover:text-text-600 transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  @click="deleteTask(task)"
                  class="text-accent-400 hover:text-accent-600 transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="taskStore.pagination.lastPage > 1" class="mt-6 flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="previousPage"
            :disabled="taskStore.pagination.currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-background-300 text-sm font-medium rounded-md text-text-700 bg-background-50 hover:bg-background-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Previous
          </button>
          <button
            @click="nextPage"
            :disabled="taskStore.pagination.currentPage === taskStore.pagination.lastPage"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-background-300 text-sm font-medium rounded-md text-text-700 bg-background-50 hover:bg-background-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-text-700">
              Showing
              <span class="font-medium">{{ taskStore.pagination.from }}</span>
              to
              <span class="font-medium">{{ taskStore.pagination.to }}</span>
              of
              <span class="font-medium">{{ taskStore.pagination.total }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button
                @click="previousPage"
                :disabled="taskStore.pagination.currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-background-300 bg-background-50 text-sm font-medium text-text-500 hover:bg-background-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                Previous
              </button>
              <button
                @click="nextPage"
                :disabled="taskStore.pagination.currentPage === taskStore.pagination.lastPage"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-background-300 bg-background-50 text-sm font-medium text-text-500 hover:bg-background-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                Next
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Task Modal -->
    <TaskModal
      v-if="showCreateModal || showEditModal"
      :task="selectedTask"
      @close="closeModal"
      @save="handleTaskSave"
    />

    <!-- Delete Confirmation Modal -->
    <ConfirmDialog
      v-if="showDeleteModal"
      title="Delete Task"
      message="Are you sure you want to delete this task? This action cannot be undone."
      @confirm="handleDeleteConfirm"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useTaskStore } from '../stores/task'
import TaskModal from '../components/TaskModal.vue'
import ConfirmDialog from '../components/ConfirmDialog.vue'

const taskStore = useTaskStore()

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const selectedTask = ref(null)
const taskToDelete = ref(null)

const filters = ref({
  status: '',
  priority: '',
  search: ''
})

const filteredTasks = computed(() => {
  let tasks = taskStore.tasks

  if (filters.value.status) {
    tasks = tasks.filter(task => task.status === filters.value.status)
  }

  if (filters.value.priority) {
    tasks = tasks.filter(task => task.priority === filters.value.priority)
  }

  if (filters.value.search) {
    const search = filters.value.search.toLowerCase()
    tasks = tasks.filter(task => 
      task.title.toLowerCase().includes(search) ||
      task.description.toLowerCase().includes(search)
    )
  }

  return tasks
})

const toggleTaskStatus = async (task) => {
  try {
    await taskStore.toggleTaskStatus(task.id)
  } catch (error) {
    console.error('Failed to toggle task status:', error)
  }
}

const editTask = (task) => {
  selectedTask.value = { ...task }
  showEditModal.value = true
}

const deleteTask = (task) => {
  taskToDelete.value = task
  showDeleteModal.value = true
}

const handleDeleteConfirm = async () => {
  try {
    await taskStore.deleteTask(taskToDelete.value.id)
    showDeleteModal.value = false
    taskToDelete.value = null
  } catch (error) {
    console.error('Failed to delete task:', error)
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  selectedTask.value = null
}

const handleTaskSave = async (taskData) => {
  try {
    if (selectedTask.value?.id) {
      await taskStore.updateTask(selectedTask.value.id, taskData)
    } else {
      await taskStore.createTask(taskData)
    }
    closeModal()
  } catch (error) {
    console.error('Failed to save task:', error)
  }
}

const previousPage = () => {
  if (taskStore.pagination.currentPage > 1) {
    taskStore.fetchTasks({ page: taskStore.pagination.currentPage - 1 })
  }
}

const nextPage = () => {
  if (taskStore.pagination.currentPage < taskStore.pagination.lastPage) {
    taskStore.fetchTasks({ page: taskStore.pagination.currentPage + 1 })
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

// Watch filters and refetch tasks
watch(filters, () => {
  taskStore.fetchTasks({ 
    ...filters.value,
    page: 1 
  })
}, { deep: true })

onMounted(async () => {
  try {
    await taskStore.fetchTasks()
  } catch (error) {
    console.error('Failed to fetch tasks:', error)
  }
})
</script>
