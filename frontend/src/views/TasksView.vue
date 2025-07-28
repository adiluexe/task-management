<template>
  <div class="min-h-screen bg-gradient-to-br from-background-50 via-background-100 to-background-200">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-500 py-16 lg:py-20">
      <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.1) 2px, transparent 2px); background-size: 60px 60px;"></div>
      </div>
      <div class="relative max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="text-center">
          <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 opacity-0" ref="heroTitle">
            Manage Your Tasks
          </h1>
          <p class="text-2xl lg:text-3xl text-primary-100 mb-12 opacity-0" ref="heroSubtitle">
            Stay organized and boost your productivity
          </p>
          
          <!-- Quick Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-2xl mx-auto">
            <div class="stat-card" v-for="(stat, index) in taskStats" :key="index" ref="statCards">
              <div class="text-4xl font-bold text-white mb-2">{{ stat.value }}</div>
              <div class="text-primary-100 text-lg">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12 -mt-8">
      <!-- Modern Filter Panel -->
      <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/60 p-8 mb-12" ref="filterPanel">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-text-700 mb-3">Search Tasks</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-text-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search tasks..."
                class="block w-full pl-12 pr-4 py-4 border border-background-300 rounded-2xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/50"
              />
            </div>
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-text-700 mb-3">Status</label>
            <select
              v-model="filters.status"
              class="block w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/50"
            >
              <option value="">All Status</option>
              <option value="pending">🔄 Pending</option>
              <option value="completed">✅ Completed</option>
            </select>
          </div>

          <!-- Priority Filter -->
          <div>
            <label class="block text-sm font-medium text-text-700 mb-3">Priority</label>
            <select
              v-model="filters.priority"
              class="block w-full px-4 py-4 border border-background-300 rounded-2xl text-text-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 bg-white/50"
            >
              <option value="">All Priority</option>
              <option value="low">🟢 Low</option>
              <option value="medium">🟡 Medium</option>
              <option value="high">🔴 High</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 xl:grid-cols-5 gap-12">
        <!-- Tasks List - Enhanced -->
        <div class="xl:col-span-3">
          <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/60 overflow-hidden" ref="tasksContainer">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-background-200/50 bg-gradient-to-r from-background-50 to-background-100">
              <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-text-900 flex items-center">
                  <svg class="w-7 h-7 text-primary-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                  </svg>
                  Your Tasks
                  <span class="ml-3 px-4 py-2 bg-primary-100 text-primary-800 text-sm font-medium rounded-xl">
                    {{ filteredTasks.length }}
                  </span>
                </h2>
                
                <!-- View Toggle -->
                <div class="flex items-center space-x-3 bg-background-100 p-2 rounded-2xl">
                  <button
                    @click="viewMode = 'list'"
                    :class="[
                      'p-3 rounded-xl transition-all duration-200 flex items-center space-x-2',
                      viewMode === 'list' ? 'bg-primary-500 text-white shadow-lg' : 'text-text-400 hover:text-primary-500 hover:bg-white'
                    ]"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="hidden sm:block text-sm font-medium">List</span>
                  </button>
                  <button
                    @click="viewMode = 'grid'"
                    :class="[
                      'p-3 rounded-xl transition-all duration-200 flex items-center space-x-2',
                      viewMode === 'grid' ? 'bg-primary-500 text-white shadow-lg' : 'text-text-400 hover:text-primary-500 hover:bg-white'
                    ]"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="hidden sm:block text-sm font-medium">Grid</span>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Loading State -->
            <div v-if="taskStore.loading" class="flex justify-center py-24">
              <div class="relative">
                <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 w-16 h-16 border-4 border-transparent border-r-secondary-400 rounded-full animate-ping"></div>
              </div>
            </div>
            
            <!-- Empty State -->
            <div v-else-if="filteredTasks.length === 0" class="text-center py-24 px-8" ref="emptyState">
              <div class="relative inline-block mb-8">
                <svg class="mx-auto h-20 w-20 text-text-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <div class="absolute top-0 left-0 w-full h-full">
                  <div class="w-20 h-20 border-2 border-primary-200 rounded-full animate-pulse"></div>
                </div>
              </div>
              <h3 class="text-2xl font-bold text-text-900 mb-4">No tasks found</h3>
              <p class="text-text-500 mb-8 max-w-md mx-auto text-lg">Create your first task to get started with your productivity journey!</p>
              <button
                @click="scrollToCreateForm"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-primary-700 transform hover:scale-105 transition-all duration-200 shadow-xl hover:shadow-2xl"
              >
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Your First Task
              </button>
            </div>

            <!-- Tasks List/Grid -->
            <div v-else class="min-h-[400px]">
              <!-- List View -->
              <div v-if="viewMode === 'list'" class="divide-y divide-background-100/80">
                <VueDraggable
                  v-model="filteredTasks"
                  :animation="200"
                  ghostClass="ghost-task"
                  chosenClass="chosen-task"
                  dragClass="drag-task"
                  @end="onDragEnd"
                  class="task-list"
                >
                  <TaskCard
                    v-for="(task, index) in filteredTasks"
                    :key="task.id"
                    :task="task"
                    :index="index"
                    :view-mode="viewMode"
                    @toggle-status="toggleTaskStatus"
                    @edit="editTask"
                    @delete="deleteTask"
                    ref="taskCards"
                  />
                </VueDraggable>
              </div>

              <!-- Grid View -->
              <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-8">
                <VueDraggable
                  v-model="filteredTasks"
                  :animation="200"
                  ghostClass="ghost-task"
                  chosenClass="chosen-task"
                  dragClass="drag-task"
                  @end="onDragEnd"
                  class="contents"
                >
                  <TaskCard
                    v-for="(task, index) in filteredTasks"
                    :key="task.id"
                    :task="task"
                    :index="index"
                    :view-mode="viewMode"
                    @toggle-status="toggleTaskStatus"
                    @edit="editTask"
                    @delete="deleteTask"
                  />
                </VueDraggable>
              </div>
            </div>

            <!-- Enhanced Pagination -->
            <div v-if="taskStore.pagination.lastPage > 1" class="px-8 py-6 border-t border-background-200/50 bg-background-50/50">
              <div class="flex items-center justify-between">
                <div class="text-sm text-text-600">
                  Showing <span class="font-semibold text-text-900">{{ taskStore.pagination.from }}</span> to 
                  <span class="font-semibold text-text-900">{{ taskStore.pagination.to }}</span> of 
                  <span class="font-semibold text-text-900">{{ taskStore.pagination.total }}</span> tasks
                </div>
                <div class="flex items-center space-x-3">
                  <button
                    @click="previousPage"
                    :disabled="taskStore.pagination.currentPage === 1"
                    class="pagination-btn"
                    :class="{ 'opacity-50 cursor-not-allowed': taskStore.pagination.currentPage === 1 }"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                  </button>
                  <span class="px-4 py-3 text-sm font-semibold text-text-700 bg-background-100 rounded-xl">
                    Page {{ taskStore.pagination.currentPage }} of {{ taskStore.pagination.lastPage }}
                  </span>
                  <button
                    @click="nextPage"
                    :disabled="taskStore.pagination.currentPage === taskStore.pagination.lastPage"
                    class="pagination-btn"
                    :class="{ 'opacity-50 cursor-not-allowed': taskStore.pagination.currentPage === taskStore.pagination.lastPage }"
                  >
                    Next
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Enhanced Add New Task Sidebar -->
        <div class="xl:col-span-2">
          <div class="sticky top-28">
            <CreateTaskForm 
              @task-created="handleTaskCreated"
              ref="createFormRef"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Modals -->
    <TaskModal
      v-if="showEditModal"
      :task="selectedTask"
      @close="closeModal"
      @save="handleTaskSave"
    />

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
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useTaskStore } from '../stores/task'
import { VueDraggable } from 'vue-draggable-plus'
import TaskModal from '../components/TaskModal.vue'
import ConfirmDialog from '../components/ConfirmDialog.vue'
import TaskCard from '../components/TaskCard.vue'
import CreateTaskForm from '../components/CreateTaskForm.vue'
import gsap from 'gsap'

const taskStore = useTaskStore()

// Refs for animations
const heroTitle = ref(null)
const heroSubtitle = ref(null)
const statCards = ref([])
const filterPanel = ref(null)
const tasksContainer = ref(null)
const emptyState = ref(null)
const taskCards = ref([])
const createFormRef = ref(null)

// Modal states
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const selectedTask = ref(null)
const taskToDelete = ref(null)

// View and filter states
const viewMode = ref('list')
const filters = ref({
  status: '',
  priority: '',
  search: ''
})

// Computed properties
const filteredTasks = computed(() => {
  let tasks = [...taskStore.tasks]

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

const taskStats = computed(() => [
  {
    label: 'Total Tasks',
    value: taskStore.tasks.length
  },
  {
    label: 'Completed',
    value: taskStore.tasks.filter(task => task.status === 'completed').length
  },
  {
    label: 'Pending',
    value: taskStore.tasks.filter(task => task.status === 'pending').length
  }
])

// Animation functions
const animateHeroEntrance = () => {
  const tl = gsap.timeline()
  
  tl.fromTo(heroTitle.value, 
    { opacity: 0, y: 30 },
    { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
  )
  .fromTo(heroSubtitle.value,
    { opacity: 0, y: 20 },
    { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" },
    "-=0.4"
  )
  .fromTo(statCards.value,
    { opacity: 0, y: 20, scale: 0.9 },
    { 
      opacity: 1, 
      y: 0, 
      scale: 1, 
      duration: 0.5, 
      stagger: 0.1, 
      ease: "power2.out" 
    },
    "-=0.3"
  )
}

const animateContentEntrance = () => {
  const tl = gsap.timeline()
  
  if (filterPanel.value) {
    tl.fromTo(filterPanel.value,
      { opacity: 0, y: 40 },
      { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }
    )
  }
  
  if (tasksContainer.value) {
    tl.fromTo(tasksContainer.value,
      { opacity: 0, y: 30 },
      { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" },
      "-=0.4"
    )
  }
}

const animateTaskCards = () => {
  if (taskCards.value.length > 0) {
    gsap.fromTo(taskCards.value,
      { opacity: 0, x: -20 },
      { 
        opacity: 1, 
        x: 0, 
        duration: 0.4, 
        stagger: 0.1, 
        ease: "power2.out" 
      }
    )
  }
}

// Task operations
const toggleTaskStatus = async (task) => {
  try {
    // Optimistic UI update with animation
    const taskElement = document.querySelector(`[data-task-id="${task.id}"]`)
    if (taskElement) {
      gsap.to(taskElement, {
        scale: 0.95,
        duration: 0.1,
        yoyo: true,
        repeat: 1,
        ease: "power2.inOut"
      })
    }
    
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
    // Animate task removal
    const taskElement = document.querySelector(`[data-task-id="${taskToDelete.value.id}"]`)
    if (taskElement) {
      await gsap.to(taskElement, {
        opacity: 0,
        x: -100,
        scale: 0.8,
        duration: 0.3,
        ease: "power2.in"
      })
    }
    
    await taskStore.deleteTask(taskToDelete.value.id)
    showDeleteModal.value = false
    taskToDelete.value = null
  } catch (error) {
    console.error('Failed to delete task:', error)
  }
}

const closeModal = () => {
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

const handleTaskCreated = () => {
  // Animate new task appearance
  nextTick(() => {
    animateTaskCards()
  })
}

// Drag and drop
const onDragEnd = async (event) => {
  try {
    const { oldIndex, newIndex } = event
    if (oldIndex === newIndex) return
    
    // Update task order in backend
    const taskIds = filteredTasks.value.map(task => task.id)
    await taskStore.updateTaskOrder(taskIds)
    
    // Success feedback
    gsap.to(event.item, {
      scale: 1.05,
      duration: 0.2,
      yoyo: true,
      repeat: 1,
      ease: "power2.inOut"
    })
  } catch (error) {
    console.error('Failed to update task order:', error)
  }
}

// Pagination
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

// Utility functions
const scrollToCreateForm = () => {
  if (createFormRef.value) {
    createFormRef.value.$el.scrollIntoView({ 
      behavior: 'smooth',
      block: 'center' 
    })
    
    // Highlight the form
    gsap.fromTo(createFormRef.value.$el,
      { scale: 1 },
      { 
        scale: 1.02, 
        duration: 0.3, 
        yoyo: true, 
        repeat: 1, 
        ease: "power2.inOut" 
      }
    )
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

// Watchers
watch(filters, () => {
  taskStore.fetchTasks({ 
    ...filters.value,
    page: 1 
  })
}, { deep: true })

watch(filteredTasks, () => {
  nextTick(() => {
    animateTaskCards()
  })
})

// Lifecycle
onMounted(async () => {
  try {
    await taskStore.fetchTasks()
    
    // Stagger entrance animations
    setTimeout(() => animateHeroEntrance(), 100)
    setTimeout(() => animateContentEntrance(), 500)
    setTimeout(() => animateTaskCards(), 800)
    
  } catch (error) {
    console.error('Failed to fetch tasks:', error)
  }
})
</script>

<style scoped>
/* Stat Cards */
.stat-card {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(8px);
  border-radius: 1.5rem;
  padding: 2rem;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.3);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  min-height: 120px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.stat-card:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  background: rgba(255, 255, 255, 0.25);
}

/* Pagination Buttons */
.pagination-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  background-color: white;
  color: rgb(75 85 99);
  border: 1px solid rgb(209 213 219);
  border-radius: 1rem;
  font-weight: 600;
  transition: all 0.2s ease;
  font-size: 0.95rem;
}

.pagination-btn:hover:not(:disabled) {
  background-color: rgb(240 253 244);
  color: rgb(21 128 61);
  border-color: rgb(34 197 94);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

/* Drag and Drop Styles */
.ghost-task {
  opacity: 0.5;
  background: rgba(59, 130, 246, 0.1);
  transform: rotate(2deg);
}

.chosen-task {
  cursor: grabbing !important;
  transform: scale(1.02);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  z-index: 1000;
}

.drag-task {
  transform: rotate(1deg);
  opacity: 0.9;
}

.task-list {
  min-height: 200px;
}

/* Enhanced hover effects */
.filter-input:focus {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

/* Loading animation enhancement */
@keyframes float-up-down {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

.floating-element {
  animation: float-up-down 3s ease-in-out infinite;
}

/* Success/Error animations */
@keyframes bounce-in {
  0% {
    transform: scale(0.3);
    opacity: 0;
  }
  50% {
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.bounce-in {
  animation: bounce-in 0.5s ease;
}

/* Custom scrollbar */
.tasks-container {
  scrollbar-width: thin;
  scrollbar-color: rgba(34, 197, 94, 0.3) transparent;
}

.tasks-container::-webkit-scrollbar {
  width: 6px;
}

.tasks-container::-webkit-scrollbar-track {
  background: transparent;
}

.tasks-container::-webkit-scrollbar-thumb {
  background: rgba(34, 197, 94, 0.3);
  border-radius: 3px;
}

.tasks-container::-webkit-scrollbar-thumb:hover {
  background: rgba(34, 197, 94, 0.5);
}

/* Priority indicator animations */
@keyframes priority-pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.priority-high {
  animation: priority-pulse 2s ease-in-out infinite;
}
</style>
