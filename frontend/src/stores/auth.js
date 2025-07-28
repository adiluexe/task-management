import { defineStore } from "pinia";
import { authService } from "../services/auth";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("token"),
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
        localStorage.setItem("token", response.token);
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
        localStorage.setItem("token", response.token);
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
        localStorage.removeItem("token");
      }
    },

    async fetchUser() {
      if (!this.token) return;

      try {
        const user = await authService.getUser();
        this.user = user;
        return user;
      } catch (error) {
        console.error("Failed to fetch user:", error);
        this.logout();
        throw error;
      }
    },

    initializeAuth() {
      const token = localStorage.getItem("token");
      if (token) {
        this.token = token;
        this.fetchUser();
      }
    },
  },
});
