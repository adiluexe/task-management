import { defineStore } from "pinia";
import { authService } from "../services/auth";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: JSON.parse(localStorage.getItem("user")) || null,
    token: localStorage.getItem("auth_token"),
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => state.user?.is_admin || false,
  },

  actions: {
    async login(credentials) {
      this.loading = true;
      try {
        const response = await authService.login(credentials);
        this.token = response.token;
        this.user = response.user;
        localStorage.setItem("auth_token", response.token);
        localStorage.setItem("user", JSON.stringify(response.user));
        return response;
      } finally {
        this.loading = false;
      }
    },

    async register(userData) {
      this.loading = true;
      try {
        const response = await authService.register(userData);
        this.token = response.token;
        this.user = response.user;
        localStorage.setItem("auth_token", response.token);
        localStorage.setItem("user", JSON.stringify(response.user));
        return response;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        await authService.logout();
      } catch (error) {
        console.error("Logout failed:", error);
      } finally {
        this.token = null;
        this.user = null;
        localStorage.removeItem("auth_token");
        localStorage.removeItem("user");
      }
    },

    async fetchUser() {
      if (!this.token) return;

      try {
        const user = await authService.getCurrentUser();
        this.user = user;
        localStorage.setItem("user", JSON.stringify(user));
        return user;
      } catch (error) {
        console.error("Failed to fetch user:", error);
        this.logout();
        throw error;
      }
    },

    initializeAuth() {
      const token = localStorage.getItem("auth_token");
      const user = localStorage.getItem("user");

      if (token && user) {
        this.token = token;
        this.user = JSON.parse(user);
        // Verify token is still valid by fetching user
        this.fetchUser().catch(() => {
          // Token is invalid, logout
          this.logout();
        });
      }
    },
  },
});
