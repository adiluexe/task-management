import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { authService } from "../services/auth";

export const useAuthStore = defineStore("auth", () => {
  const user = ref(null);
  const token = ref(null);
  const loading = ref(false);
  const error = ref(null);

  // Computed
  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.is_admin || false);

  // Actions
  async function login(credentials) {
    loading.value = true;
    error.value = null;

    try {
      const response = await authService.login(credentials);
      user.value = response.user;
      token.value = response.token;
      return response;
    } catch (err) {
      error.value = err.message || "Login failed";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function register(userData) {
    loading.value = true;
    error.value = null;

    try {
      const response = await authService.register(userData);
      return response;
    } catch (err) {
      error.value = err.message || "Registration failed";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    loading.value = true;

    try {
      await authService.logout();
    } catch (err) {
      console.error("Logout error:", err);
    } finally {
      user.value = null;
      token.value = null;
      loading.value = false;
    }
  }

  async function fetchCurrentUser() {
    if (!authService.isAuthenticated()) return;

    loading.value = true;
    try {
      const userData = await authService.getCurrentUser();
      user.value = userData;
    } catch (err) {
      // If fetching current user fails, clear auth data
      await logout();
    } finally {
      loading.value = false;
    }
  }

  function initializeAuth() {
    // Load stored auth data on app initialization
    if (authService.isAuthenticated()) {
      token.value = localStorage.getItem("auth_token");
      user.value = authService.getStoredUser();
      // Optionally fetch fresh user data
      fetchCurrentUser();
    }
  }

  function clearError() {
    error.value = null;
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    login,
    register,
    logout,
    fetchCurrentUser,
    initializeAuth,
    clearError,
  };
});
