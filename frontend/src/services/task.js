import api from "./api";

export const taskService = {
  // Get all tasks with filtering and pagination
  async getTasks(params = {}) {
    try {
      const response = await api.get("/tasks", { params });
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Get single task
  async getTask(id) {
    try {
      const response = await api.get(`/tasks/${id}`);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Create new task
  async createTask(taskData) {
    try {
      const response = await api.post("/tasks", taskData);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Update task
  async updateTask(id, taskData) {
    try {
      const response = await api.put(`/tasks/${id}`, taskData);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Delete task
  async deleteTask(id) {
    try {
      const response = await api.delete(`/tasks/${id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Toggle task status
  async toggleTaskStatus(id) {
    try {
      const response = await api.post(`/tasks/${id}/toggle-status`);
      return response.data.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Reorder tasks
  async reorderTasks(tasks) {
    try {
      const response = await api.post("/tasks/reorder", { tasks });
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },
};
