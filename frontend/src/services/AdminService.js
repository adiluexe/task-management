import axios from "axios";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000/api";

// Create axios instance with default config
const api = axios.create({
  baseURL: API_URL,
  timeout: 10000,
});

// Add auth token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("auth_token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
      window.location.href = "/login";
    }
    return Promise.reject(error);
  }
);

export class AdminService {
  /**
   * Get dashboard statistics
   */
  static async getDashboardStats() {
    try {
      const response = await api.get("/admin/dashboard-stats");
      return response.data;
    } catch (error) {
      console.error("Failed to fetch dashboard stats:", error);
      throw error;
    }
  }

  /**
   * Get all users with pagination
   */
  static async getUsers(page = 1, perPage = 15) {
    try {
      const response = await api.get("/admin/users", {
        params: { page, per_page: perPage },
      });
      return response.data;
    } catch (error) {
      console.error("Failed to fetch users:", error);
      throw error;
    }
  }

  /**
   * Toggle admin status for a user
   */
  static async toggleAdminStatus(userId) {
    try {
      const response = await api.patch(`/admin/users/${userId}/toggle-admin`);
      return response.data;
    } catch (error) {
      console.error("Failed to toggle admin status:", error);
      throw error;
    }
  }

  /**
   * Delete a user
   */
  static async deleteUser(userId) {
    try {
      const response = await api.delete(`/admin/users/${userId}`);
      return response.data;
    } catch (error) {
      console.error("Failed to delete user:", error);
      throw error;
    }
  }

  /**
   * Get recent activity
   */
  static async getRecentActivity() {
    try {
      const response = await api.get("/admin/recent-activity");
      return response.data;
    } catch (error) {
      console.error("Failed to fetch recent activity:", error);
      throw error;
    }
  }
}

export default AdminService;
