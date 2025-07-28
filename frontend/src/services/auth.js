import api from "./api";

export const authService = {
  // Register a new user
  async register(userData) {
    try {
      const response = await api.post("/register", userData);
      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Login user
  async login(credentials) {
    try {
      const response = await api.post("/login", credentials);
      const { user, token } = response.data;

      // Store auth data
      localStorage.setItem("auth_token", token);
      localStorage.setItem("user", JSON.stringify(user));

      return response.data;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Logout user
  async logout() {
    try {
      await api.post("/logout");
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      // Always clear local storage
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
    }
  },

  // Get current user
  async getCurrentUser() {
    try {
      const response = await api.get("/me");
      return response.data.user;
    } catch (error) {
      throw error.response?.data || error;
    }
  },

  // Check if user is authenticated
  isAuthenticated() {
    return !!localStorage.getItem("auth_token");
  },

  // Get stored user data
  getStoredUser() {
    const userData = localStorage.getItem("user");
    return userData ? JSON.parse(userData) : null;
  },
};
