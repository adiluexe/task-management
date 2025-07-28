<template>
  <nav class="bg-background-50 shadow-sm border-b border-background-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center taskeasy-logo">
            <router-link to="/">
              <img
                src="/assets/img/taskeasy_logo.svg"
                alt="Taskeasy Logo"
                class="h-8 w-auto"
              />
            </router-link>
          </div>
          <!-- Navigation Links -->
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link
              to="/"
              class="nav-link"
              active-class="border-primary-500 text-text-900"
            >
              Dashboard
            </router-link>
            <router-link
              to="/tasks"
              class="nav-link"
              active-class="border-primary-500 text-text-900"
            >
              Tasks
            </router-link>
            <router-link
              v-if="authStore.isAdmin"
              to="/admin"
              class="nav-link"
              active-class="border-primary-500 text-text-900"
            >
              Admin
            </router-link>
          </div>
        </div>
        <!-- User Menu -->
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <div class="ml-3 relative">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-text-700">
                {{ authStore.user?.name }}
              </span>
              <button
                @click="handleLogout"
                class="bg-background-100 hover:bg-background-200 text-text-700 px-3 py-2 rounded-md text-sm font-medium transition-colors"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
        <!-- Mobile menu button -->
        <div class="sm:hidden flex items-center">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="inline-flex items-center justify-center p-2 rounded-md text-text-400 hover:text-text-500 hover:bg-background-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!mobileMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <!-- Mobile Menu -->
    <transition name="slide-fade">
      <div v-if="mobileMenuOpen" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <router-link
            to="/"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-text-500 hover:text-text-700 hover:bg-background-50 hover:border-background-300"
            active-class="border-primary-500 text-primary-700 bg-primary-50"
            @click="mobileMenuOpen = false"
          >
            Dashboard
          </router-link>
          <router-link
            to="/tasks"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-text-500 hover:text-text-700 hover:bg-background-50 hover:border-background-300"
            active-class="border-primary-500 text-primary-700 bg-primary-50"
            @click="mobileMenuOpen = false"
          >
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-text-500 hover:text-text-700 hover:bg-background-50 hover:border-background-300"
            active-class="border-primary-500 text-primary-700 bg-primary-50"
            @click="mobileMenuOpen = false"
          >
            Admin
          </router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-background-200">
          <div class="flex items-center px-4">
            <div class="text-base font-medium text-text-800">
              {{ authStore.user?.name }}
            </div>
            <div class="text-sm text-text-500">{{ authStore.user?.email }}</div>
          </div>
          <div class="mt-3 space-y-1">
            <button
              @click="handleLogout"
              class="block w-full text-left px-4 py-2 text-base font-medium text-text-500 hover:text-text-700 hover:bg-background-100"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import gsap from "gsap";

const router = useRouter();
const authStore = useAuthStore();
const mobileMenuOpen = ref(false);

onMounted(() => {
  gsap.from(".taskeasy-logo", {
    opacity: 0,
    y: -30,
    duration: 1,
    ease: "power2.out",
  });
});

async function handleLogout() {
  try {
    await authStore.logout();
    router.push("/login");
  } catch (error) {
    console.error("Logout error:", error);
  }
}
</script>

<style scoped>
.nav-link {
  border-bottom: 2px solid transparent;
  color: var(--color-text-500);
  padding: 0.5rem 0.25rem;
  font-weight: 500;
  font-size: 1rem;
  transition: color 0.2s, border-color 0.2s;
}
.nav-link:hover {
  color: var(--color-primary-600);
  border-bottom: 2px solid var(--color-primary-500);
}
.slide-fade-enter-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(-20px);
}
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
