<template>
  <div class="min-h-screen bg-background-50">
    <!-- Enhanced Header Section -->
    <div
      class="bg-gradient-to-br from-white via-primary-50/20 to-accent-50/10 border-b border-background-100/40"
    >
      <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div
          class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6"
        >
          <div class="text-center lg:text-left">
            <h1
              ref="heroTitle"
              class="text-4xl lg:text-5xl font-semibold text-text-900 mb-3 tracking-tight"
            >
              Tasks
            </h1>
            <p ref="heroSubtitle" class="text-lg text-text-600 max-w-2xl">
              Manage and organize your tasks efficiently with our modern
              interface
            </p>
          </div>

          <!-- Enhanced Quick Stats -->
          <div class="flex flex-wrap justify-center lg:justify-end gap-4">
            <div
              v-for="(stat, index) in taskStats"
              :key="index"
              ref="statCards"
              class="stat-card group"
            >
              <div
                class="text-2xl font-bold text-text-900 mb-1 group-hover:text-primary-600 transition-colors duration-200"
              >
                {{ stat.value }}
              </div>
              <div
                class="text-xs font-medium text-text-600 group-hover:text-text-700 transition-colors duration-200"
              >
                {{ stat.label }}
              </div>
            </div>

            <!-- Quick Add Task Button -->
            <button
              @click="openCreateModal"
              class="flex items-center gap-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg transform shadow-sm"
            >
              <Icon icon="lucide:plus" class="w-4 h-4" />
              <span class="hidden sm:inline">Add Task</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
      <!-- Enhanced Filter Panel -->
      <div
        ref="filterPanel"
        class="filter-group bg-white/90 backdrop-blur-lg rounded-2xl border border-background-200/50 p-6 mb-6 shadow-sm hover:shadow-md transition-all duration-200"
      >
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Enhanced Search -->
          <div class="md:col-span-2">
            <label
              class="flex items-center text-sm font-medium text-text-700 mb-2"
            >
              <Icon
                icon="lucide:search"
                class="w-4 h-4 mr-2 text-primary-500"
              />
              Search Tasks
            </label>
            <div class="relative group">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <Icon
                  icon="lucide:search"
                  class="h-4 w-4 text-text-400 group-focus-within:text-primary-500 transition-colors duration-200"
                />
              </div>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search by title or description..."
                class="block w-full pl-10 pr-3 py-3 border border-background-200 rounded-xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white focus:bg-white"
              />
            </div>
          </div>

          <!-- Enhanced Status Filter -->
          <div>
            <label
              class="flex items-center text-sm font-medium text-text-700 mb-2"
            >
              <Icon
                icon="lucide:badge-check"
                class="w-4 h-4 mr-2 text-primary-500"
              />
              Status
            </label>
            <select
              v-model="filters.status"
              class="block w-full px-3 py-3 border border-background-200 rounded-xl text-text-900 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white focus:bg-white appearance-none cursor-pointer"
            >
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </div>

          <!-- Enhanced Priority Filter -->
          <div>
            <label
              class="flex items-center text-sm font-medium text-text-700 mb-2"
            >
              <Icon icon="lucide:flag" class="w-4 h-4 mr-2 text-primary-500" />
              Priority
            </label>
            <select
              v-model="filters.priority"
              class="block w-full px-3 py-3 border border-background-200 rounded-xl text-text-900 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white focus:bg-white appearance-none cursor-pointer"
            >
              <option value="">All Priority</option>
              <option value="low">Low Priority</option>
              <option value="medium">Medium Priority</option>
              <option value="high">High Priority</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Enhanced Main Content -->
      <div class="w-full">
        <!-- Enhanced Tasks List -->
        <div class="w-full">
          <div
            ref="tasksContainer"
            class="bg-white/95 backdrop-blur-lg rounded-2xl border border-background-200/50 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200"
          >
            <!-- Enhanced Header -->
            <div
              class="px-6 py-4 border-b border-background-100/50 bg-gradient-to-r from-background-50/30 to-primary-50/20"
            >
              <div class="flex items-center justify-between">
                <h2
                  class="text-xl font-semibold text-text-900 flex items-center gap-1.5"
                >
                  <Icon
                    icon="lucide:clipboard-check"
                    class="w-4 h-4 text-primary"
                  />
                  Tasks
                  <span
                    class="ml-3 px-3 py-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-medium rounded-lg"
                  >
                    {{ filteredTasks.length }}
                  </span>
                </h2>

                <!-- Enhanced View Toggle -->
                <div
                  class="flex items-center space-x-1 bg-background-100/60 p-1 rounded-xl backdrop-blur-sm"
                >
                  <button
                    @click="viewMode = 'list'"
                    :class="[
                      'p-2.5 rounded-lg transition-all duration-200 flex items-center',
                      viewMode === 'list'
                        ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-md transform scale-105'
                        : 'text-text-400 hover:text-primary-500 hover:bg-white/70',
                    ]"
                  >
                    <Icon icon="lucide:list" class="w-4 h-4" />
                  </button>
                  <button
                    @click="viewMode = 'grid'"
                    :class="[
                      'p-2.5 rounded-lg transition-all duration-200 flex items-center',
                      viewMode === 'grid'
                        ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-md transform scale-105'
                        : 'text-text-400 hover:text-primary-500 hover:bg-white/70',
                    ]"
                  >
                    <Icon icon="lucide:grid-3x3" class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Enhanced Loading State -->
            <div
              v-if="taskStore.loading"
              class="flex flex-col items-center justify-center py-16"
            >
              <div class="relative">
                <div
                  class="w-12 h-12 border-3 border-background-200 border-t-primary-500 rounded-full animate-spin"
                ></div>
                <div
                  class="absolute inset-0 w-12 h-12 border-3 border-transparent border-t-accent-400 rounded-full animate-spin animation-delay-150"
                ></div>
              </div>
              <p class="mt-4 text-text-600 font-medium">
                Loading your tasks...
              </p>
            </div>

            <!-- Enhanced Empty State -->
            <div
              v-else-if="filteredTasks.length === 0"
              ref="emptyState"
              class="text-center py-16 px-6"
            >
              <div
                class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-primary-50 to-accent-50 flex items-center justify-center group hover:from-primary-100 hover:to-accent-100 transition-all duration-200"
              >
                <Icon
                  icon="lucide:clipboard-check"
                  class="w-8 h-8 text-primary-400 group-hover:text-primary-500 transition-colors duration-200"
                />
              </div>
              <h3 class="text-xl font-semibold text-text-900 mb-3">
                No tasks found
              </h3>
              <p class="text-text-600 mb-6 max-w-md mx-auto">
                {{
                  filters.search || filters.status || filters.priority
                    ? "No tasks match your current filters. Try adjusting your search criteria."
                    : "Create your first task to get started on your productivity journey!"
                }}
              </p>
              <button
                v-if="!filters.search && !filters.status && !filters.priority"
                @click="openCreateModal"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 transform"
              >
                <Icon icon="lucide:plus" class="w-5 h-5 mr-2" />
                Create Your First Task
              </button>
            </div>

            <!-- Tasks List/Grid -->
            <div v-else class="min-h-[400px]">
              <!-- List View -->
              <div v-if="viewMode === 'list'" class="p-6">
                <VueDraggable
                  v-model="filteredTasks"
                  :animation="200"
                  ghostClass="ghost-task"
                  chosenClass="chosen-task"
                  dragClass="drag-task"
                  @end="onDragEnd"
                  class="task-list-container"
                >
                  <div
                    v-for="(task, index) in filteredTasks"
                    :key="task.id"
                    class="task-item"
                    :class="{ 'mb-6': index < filteredTasks.length - 1 }"
                  >
                    <TaskCard
                      :task="task"
                      :index="index"
                      :view-mode="viewMode"
                      @toggle-status="toggleTaskStatus"
                      @edit="editTask"
                      @delete="deleteTask"
                      ref="taskCards"
                    />
                  </div>
                </VueDraggable>
              </div>

              <!-- Grid View -->
              <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-6"
              >
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
            <div
              v-if="taskStore.pagination.lastPage > 1"
              class="px-6 py-4 border-t border-background-200/40 bg-background-50/30"
            >
              <div class="flex items-center justify-between">
                <div class="text-sm text-text-600">
                  Showing
                  <span class="font-semibold text-text-900">{{
                    taskStore.pagination.from
                  }}</span>
                  to
                  <span class="font-semibold text-text-900">{{
                    taskStore.pagination.to
                  }}</span>
                  of
                  <span class="font-semibold text-text-900">{{
                    taskStore.pagination.total
                  }}</span>
                  tasks
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    @click="previousPage"
                    :disabled="taskStore.pagination.currentPage === 1"
                    class="pagination-btn"
                    :class="{
                      'opacity-50 cursor-not-allowed':
                        taskStore.pagination.currentPage === 1,
                    }"
                  >
                    <Icon icon="lucide:chevron-left" class="w-4 h-4" />
                    Previous
                  </button>
                  <span
                    class="px-3 py-2 text-sm font-medium text-text-700 bg-background-100 rounded-lg"
                  >
                    Page {{ taskStore.pagination.currentPage }} of
                    {{ taskStore.pagination.lastPage }}
                  </span>
                  <button
                    @click="nextPage"
                    :disabled="
                      taskStore.pagination.currentPage ===
                      taskStore.pagination.lastPage
                    "
                    class="pagination-btn"
                    :class="{
                      'opacity-50 cursor-not-allowed':
                        taskStore.pagination.currentPage ===
                        taskStore.pagination.lastPage,
                    }"
                  >
                    Next
                    <Icon icon="lucide:chevron-right" class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Floating Action Button (Mobile/Tablet) -->
    <button
      @click="openCreateModal"
      class="fixed bottom-6 right-6 w-16 h-16 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center hover:scale-110 transform z-40 lg:hidden group animate-pulse"
    >
      <Icon
        icon="lucide:plus"
        class="w-7 h-7 group-hover:rotate-90 transition-transform duration-300"
      />

      <!-- Ripple Effect -->
      <div
        class="absolute inset-0 rounded-full bg-white/20 scale-0 group-hover:scale-100 transition-transform duration-300"
      ></div>

      <!-- Tooltip -->
      <div
        class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-black/80 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap backdrop-blur-sm"
      >
        Create New Task
        <div
          class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black/80"
        ></div>
      </div>
    </button>

    <!-- Enhanced Modals -->
    <TaskModal
      v-if="showEditModal"
      :task="selectedTask"
      :isLoading="modalLoading"
      :externalErrors="modalErrors"
      @close="closeModal"
      @save="handleTaskSave"
    />

    <!-- Create Task Modal -->
    <TaskModal
      v-if="showCreateModal"
      :isLoading="modalLoading"
      :externalErrors="modalErrors"
      @close="closeCreateModal"
      @save="handleTaskCreate"
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
import { ref, computed, onMounted, watch, nextTick } from "vue";
import { useTaskStore } from "../stores/task";
import { VueDraggable } from "vue-draggable-plus";
import { Icon } from "@iconify/vue";
import TaskModal from "../components/TaskModal.vue";
import ConfirmDialog from "../components/ConfirmDialog.vue";
import TaskCard from "../components/TaskCard.vue";
import gsap from "gsap";

const taskStore = useTaskStore();

// Refs for animations
const heroTitle = ref(null);
const heroSubtitle = ref(null);
const statCards = ref([]);
const filterPanel = ref(null);
const tasksContainer = ref(null);
const emptyState = ref(null);
const taskCards = ref([]);

// Modal states
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showCreateModal = ref(false);
const selectedTask = ref(null);
const taskToDelete = ref(null);

// Error handling states
const modalLoading = ref(false);
const modalErrors = ref({});

// View and filter states
const viewMode = ref("list");

// Use the task store's filters directly instead of local filters
const filters = computed({
  get: () => taskStore.filters,
  set: (value) => {
    Object.assign(taskStore.filters, value);
  },
});

// Computed properties
// Use server-side filtered tasks for display
const filteredTasks = computed(() => taskStore.tasks);

// For statistics, we need to compute from all tasks, not just filtered ones
// This requires a separate call to get all tasks for statistics
const taskStats = computed(() => {
  // If any filters are active, we should show stats for ALL tasks, not just filtered
  // But for now, we'll use the filtered results since we don't have separate stats endpoint
  const totalTasks = taskStore.tasks.length;
  const completedTasks = taskStore.tasks.filter(
    (task) => task.status === "completed"
  ).length;
  const inProgressTasks = taskStore.tasks.filter(
    (task) => task.status === "in_progress"
  ).length;
  const pendingTasks = taskStore.tasks.filter(
    (task) => task.status === "pending"
  ).length;

  return [
    {
      label: "Total Tasks",
      value: totalTasks,
    },
    {
      label: "Completed",
      value: completedTasks,
    },
    {
      label: "In Progress",
      value: inProgressTasks,
    },
    {
      label: "Pending",
      value: pendingTasks,
    },
  ];
});

// Animation functions
const animateHeroEntrance = () => {
  const tl = gsap.timeline();

  tl.fromTo(
    heroTitle.value,
    { opacity: 0, y: 15 },
    { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" }
  )
    .fromTo(
      heroSubtitle.value,
      { opacity: 0, y: 10 },
      { opacity: 1, y: 0, duration: 0.4, ease: "power2.out" },
      "-=0.3"
    )
    .fromTo(
      statCards.value,
      { opacity: 0, y: 10, scale: 0.95 },
      {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 0.3,
        stagger: 0.1,
        ease: "power2.out",
      },
      "-=0.2"
    );
};

const animateContentEntrance = () => {
  const tl = gsap.timeline();

  if (filterPanel.value) {
    tl.fromTo(
      filterPanel.value,
      { opacity: 0, y: 20 },
      { opacity: 1, y: 0, duration: 0.4, ease: "power2.out" }
    );
  }

  if (tasksContainer.value) {
    tl.fromTo(
      tasksContainer.value,
      { opacity: 0, y: 15 },
      { opacity: 1, y: 0, duration: 0.4, ease: "power2.out" },
      "-=0.3"
    );
  }
};

const animateTaskCards = () => {
  if (taskCards.value.length > 0) {
    gsap.fromTo(
      taskCards.value,
      { opacity: 0, x: -10 },
      {
        opacity: 1,
        x: 0,
        duration: 0.3,
        stagger: 0.05,
        ease: "power2.out",
      }
    );
  }
};

// Task operations
const toggleTaskStatus = async (task) => {
  try {
    // Optimistic UI update with animation
    const taskElement = document.querySelector(`[data-task-id="${task.id}"]`);
    if (taskElement) {
      gsap.to(taskElement, {
        scale: 0.95,
        duration: 0.1,
        yoyo: true,
        repeat: 1,
        ease: "power2.inOut",
      });
    }

    await taskStore.toggleTaskStatus(task.id);
  } catch (error) {
    console.error("Failed to toggle task status:", error);
  }
};

const editTask = (task) => {
  modalErrors.value = {};
  modalLoading.value = false;
  selectedTask.value = { ...task };
  showEditModal.value = true;
};

const deleteTask = (task) => {
  taskToDelete.value = task;
  showDeleteModal.value = true;
};

const handleDeleteConfirm = async () => {
  try {
    // Animate task removal
    const taskElement = document.querySelector(
      `[data-task-id="${taskToDelete.value.id}"]`
    );
    if (taskElement) {
      await gsap.to(taskElement, {
        opacity: 0,
        x: -100,
        scale: 0.8,
        duration: 0.3,
        ease: "power2.in",
      });
    }

    await taskStore.deleteTask(taskToDelete.value.id);
    showDeleteModal.value = false;
    taskToDelete.value = null;
  } catch (error) {
    console.error("Failed to delete task:", error);
  }
};

const closeModal = () => {
  modalErrors.value = {};
  modalLoading.value = false;
  showEditModal.value = false;
  selectedTask.value = null;
};

const openCreateModal = () => {
  modalErrors.value = {};
  modalLoading.value = false;
  showCreateModal.value = true;
};

const closeCreateModal = () => {
  modalErrors.value = {};
  modalLoading.value = false;
  showCreateModal.value = false;
};

const handleTaskSave = async (taskData) => {
  modalLoading.value = true;
  modalErrors.value = {};

  try {
    if (selectedTask.value?.id) {
      await taskStore.updateTask(selectedTask.value.id, taskData);
    } else {
      await taskStore.createTask(taskData);
    }
    closeModal();
  } catch (error) {
    console.error("Failed to save task:", error);

    // Handle validation errors
    if (error.response?.status === 422) {
      if (error.response.data?.errors) {
        modalErrors.value = error.response.data.errors;
      }
    } else {
      // Handle other errors
      modalErrors.value = {
        general: [
          error.response?.data?.message ||
            error.message ||
            "Failed to save task. Please try again.",
        ],
      };
    }
  } finally {
    modalLoading.value = false;
  }
};

const handleTaskCreate = async (taskData) => {
  modalLoading.value = true;
  modalErrors.value = {};

  try {
    await taskStore.createTask(taskData);
    closeCreateModal();

    // Animate new task appearance
    nextTick(() => {
      animateTaskCards();
    });
  } catch (error) {
    console.error("Failed to create task:", error);

    // Handle validation errors
    if (error.response?.status === 422) {
      if (error.response.data?.errors) {
        modalErrors.value = error.response.data.errors;
      }
    } else {
      // Handle other errors
      modalErrors.value = {
        general: [
          error.response?.data?.message ||
            error.message ||
            "Failed to create task. Please try again.",
        ],
      };
    }
  } finally {
    modalLoading.value = false;
  }
};

const handleTaskCreated = () => {
  // Close the create modal
  showCreateModal.value = false;

  // Animate new task appearance
  nextTick(() => {
    animateTaskCards();
  });
};

// Drag and drop
const onDragEnd = async (event) => {
  try {
    const { oldIndex, newIndex } = event;
    if (oldIndex === newIndex) return;

    // Update task order in backend
    const taskIds = filteredTasks.value.map((task) => task.id);
    await taskStore.updateTaskOrder(taskIds);

    // Success feedback
    gsap.to(event.item, {
      scale: 1.05,
      duration: 0.2,
      yoyo: true,
      repeat: 1,
      ease: "power2.inOut",
    });
  } catch (error) {
    console.error("Failed to update task order:", error);
  }
};

// Pagination
const previousPage = () => {
  if (taskStore.pagination.currentPage > 1) {
    taskStore.fetchTasks({ page: taskStore.pagination.currentPage - 1 });
  }
};

const nextPage = () => {
  if (taskStore.pagination.currentPage < taskStore.pagination.lastPage) {
    taskStore.fetchTasks({ page: taskStore.pagination.currentPage + 1 });
  }
};

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

// Watchers
watch(
  () => taskStore.filters,
  () => {
    // Always fetch page 1 with refresh=true when filters change
    taskStore.fetchTasks(1, true);
  },
  { deep: true }
);

watch(filteredTasks, () => {
  nextTick(() => {
    animateTaskCards();
  });
});

// Lifecycle
onMounted(async () => {
  try {
    await taskStore.fetchTasks();

    // Stagger entrance animations
    setTimeout(() => animateHeroEntrance(), 100);
    setTimeout(() => animateContentEntrance(), 500);
    setTimeout(() => animateTaskCards(), 800);
  } catch (error) {
    console.error("Failed to fetch tasks:", error);
  }
});
</script>

<style scoped>
.filter-group {
  margin-bottom: 1rem;
}

.filter-group label {
  gap: 0.25rem;
  margin-bottom: 0.25rem;
}

/* Enhanced Stat Cards */
.stat-card {
  background: linear-gradient(
    135deg,
    rgba(255, 255, 255, 0.95) 0%,
    rgba(255, 255, 255, 0.8) 100%
  );
  backdrop-filter: blur(12px);
  border-radius: 1.5rem;
  padding: 1.5rem 1rem;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.5);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  min-height: 100px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  overflow: hidden;
  min-width: 120px;
}

.stat-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.05) 0%,
    rgba(16, 185, 129, 0.05) 100%
  );
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: -1;
}

.stat-card:hover {
  transform: translateY(-2px) scale(1.01);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  background: linear-gradient(
    135deg,
    rgba(255, 255, 255, 0.98) 0%,
    rgba(255, 255, 255, 0.85) 100%
  );
}

.stat-card:hover::before {
  opacity: 1;
}

/* Enhanced Pagination Buttons */
.pagination-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, white 0%, rgba(249, 250, 251, 0.95) 100%);
  color: rgb(75 85 99);
  border: 1px solid rgb(229 231 235);
  border-radius: 1rem;
  font-weight: 500;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  font-size: 0.875rem;
  backdrop-filter: blur(8px);
}

.pagination-btn:hover:not(:disabled) {
  background: linear-gradient(
    135deg,
    rgb(240 253 244) 0%,
    rgb(220 252 231) 100%
  );
  color: rgb(21 128 61);
  border-color: rgb(34 197 94);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

/* Enhanced Form Elements */
input:focus,
select:focus {
  transform: translateY(-0.5px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
}

/* Drag and Drop Enhanced Styles */
.ghost-task {
  opacity: 0.5;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.05) 0%,
    rgba(16, 185, 129, 0.05) 100%
  );
  transform: rotate(1deg) scale(0.99);
  border: 1px dashed rgba(59, 130, 246, 0.2);
}

.chosen-task {
  cursor: grabbing !important;
  transform: scale(1.02) rotate(0.5deg);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
  z-index: 1000;
  background: linear-gradient(135deg, white 0%, rgba(249, 250, 251, 0.98) 100%);
}

.drag-task {
  transform: rotate(0.5deg) scale(1.01);
  opacity: 0.95;
  filter: brightness(1.05);
}

.task-list {
  min-height: 300px;
}

/* Task List Spacing */
.task-list-container {
  min-height: 300px;
}

.task-item {
  margin-bottom: 0;
}

.task-item:not(:last-child) {
  margin-bottom: 0.5rem;
}

/* Enhanced Loading Animation */
@keyframes float-up-down {
  0%,
  100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-8px);
  }
}

.floating-element {
  animation: float-up-down 2s ease-in-out infinite;
}

/* Custom Animation Delays */
.animation-delay-150 {
  animation-delay: 150ms;
}

/* Success/Error animations */
@keyframes bounce-in {
  0% {
    transform: scale(0.5);
    opacity: 0;
  }
  50% {
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.95);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.bounce-in {
  animation: bounce-in 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced Custom Scrollbar */
.tasks-container {
  scrollbar-width: thin;
  scrollbar-color: rgba(59, 130, 246, 0.2) transparent;
}

.tasks-container::-webkit-scrollbar {
  width: 6px;
}

.tasks-container::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 8px;
}

.tasks-container::-webkit-scrollbar-thumb {
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.2) 0%,
    rgba(16, 185, 129, 0.2) 100%
  );
  border-radius: 8px;
  border: 1px solid transparent;
  background-clip: content-box;
}

.tasks-container::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.3) 0%,
    rgba(16, 185, 129, 0.3) 100%
  );
  background-clip: content-box;
}

/* Enhanced Glassmorphism Effects */
.glass-effect {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.4);
}

/* Hover Glow Effects */
.hover-glow:hover {
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.15);
}

/* Gradient Text */
.gradient-text {
  background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Enhanced Floating Action Button */
.fixed.bottom-6.right-6 {
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-6px);
  }
}

/* Enhanced Button Animations */
button {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

button:hover {
  transform: translateY(-0.5px);
}

button:active {
  transform: translateY(0);
}

/* Modern Focus Rings */
*:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.08);
}

/* Smooth Page Transitions */
.page-enter-active,
.page-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-enter-from,
.page-leave-to {
  opacity: 0;
  transform: translateY(15px);
}
</style>
