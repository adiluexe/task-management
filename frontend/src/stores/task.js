import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { taskService } from "../services/task";

export const useTaskStore = defineStore("task", () => {
  const tasks = ref([]);
  const currentTask = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
  });

  // Filters
  const filters = ref({
    status: "",
    priority: "",
    search: "",
  });

  // Computed
  const completedTasks = computed(() =>
    tasks.value.filter((task) => task.status === "completed")
  );

  const pendingTasks = computed(() =>
    tasks.value.filter((task) => task.status === "pending")
  );

  const taskStats = computed(() => ({
    total: tasks.value.length,
    completed: completedTasks.value.length,
    pending: pendingTasks.value.length,
  }));

  // Actions
  async function fetchTasks(page = 1, refresh = false) {
    loading.value = true;
    error.value = null;

    try {
      const params = {
        page,
        per_page: pagination.value.per_page,
        ...filters.value,
      };

      const response = await taskService.getTasks(params);

      if (refresh) {
        tasks.value = response.data;
      } else {
        tasks.value =
          page === 1 ? response.data : [...tasks.value, ...response.data];
      }

      pagination.value = response.pagination || response.meta;

      return response;
    } catch (err) {
      error.value = err.message || "Failed to fetch tasks";
      console.error("Fetch tasks error:", err);
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function createTask(taskData) {
    loading.value = true;
    error.value = null;

    try {
      const newTask = await taskService.createTask(taskData);

      // Add to the beginning of tasks array
      tasks.value.unshift(newTask);

      // Update stats
      if (pagination.value.total !== undefined) {
        pagination.value.total += 1;
      }

      return newTask;
    } catch (err) {
      error.value = err.message || "Failed to create task";
      console.error("Create task error:", err);
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function updateTask(id, taskData) {
    loading.value = true;
    error.value = null;

    try {
      const updatedTask = await taskService.updateTask(id, taskData);
      const index = tasks.value.findIndex((task) => task.id === id);

      if (index !== -1) {
        tasks.value[index] = updatedTask;
      } else {
        // If task is not in current list, try to refresh
        console.warn("Updated task not found in current list, refreshing...");
        await fetchTasks(pagination.value.current_page, true);
      }

      return updatedTask;
    } catch (err) {
      error.value = err.message || "Failed to update task";
      console.error("Update task error:", err);
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function deleteTask(id) {
    loading.value = true;
    error.value = null;

    try {
      await taskService.deleteTask(id);
      tasks.value = tasks.value.filter((task) => task.id !== id);
    } catch (err) {
      error.value = err.message || "Failed to delete task";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function toggleTaskStatus(id) {
    try {
      const updatedTask = await taskService.toggleTaskStatus(id);
      const index = tasks.value.findIndex((task) => task.id === id);
      if (index !== -1) {
        tasks.value[index] = updatedTask;
      }
      return updatedTask;
    } catch (err) {
      error.value = err.message || "Failed to update task status";
      throw err;
    }
  }

  async function reorderTasks(reorderedTasks) {
    // Optimistically update the UI
    const originalTasks = [...tasks.value];
    tasks.value = reorderedTasks;

    try {
      const tasksWithOrder = reorderedTasks.map((task, index) => ({
        id: task.id,
        order: index,
      }));

      await taskService.reorderTasks(tasksWithOrder);
    } catch (err) {
      // Revert on error
      tasks.value = originalTasks;
      error.value = err.message || "Failed to reorder tasks";
      throw err;
    }
  }

  async function updateTaskOrder(taskIds) {
    try {
      const tasksWithOrder = taskIds.map((id, index) => ({
        id,
        order: index,
      }));

      await taskService.reorderTasks(tasksWithOrder);

      // Update local order
      const reorderedTasks = taskIds
        .map((id) => tasks.value.find((task) => task.id === id))
        .filter(Boolean);

      tasks.value = reorderedTasks;
    } catch (err) {
      error.value = err.message || "Failed to update task order";
      throw err;
    }
  }

  function setFilters(newFilters) {
    filters.value = { ...filters.value, ...newFilters };
  }

  function clearFilters() {
    filters.value = {
      status: "",
      priority: "",
      search: "",
    };
  }

  function setCurrentTask(task) {
    currentTask.value = task;
  }

  function clearError() {
    error.value = null;
  }

  return {
    tasks,
    currentTask,
    loading,
    error,
    pagination,
    filters,
    completedTasks,
    pendingTasks,
    taskStats,
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    toggleTaskStatus,
    reorderTasks,
    updateTaskOrder,
    setFilters,
    clearFilters,
    setCurrentTask,
    clearError,
  };
});
