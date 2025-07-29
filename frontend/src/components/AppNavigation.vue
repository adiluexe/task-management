<template>
  <nav
    class="bg-white/90 backdrop-blur-lg border-b border-background-100 sticky top-0 z-50"
  >
    <div class="w-full px-4 sm:px-6 lg:px-16 py-2">
      <div class="flex items-center justify-between h-16 w-full">
        <!-- Logo - Left Side -->
        <div class="flex-shrink-0">
          <router-link
            to="/"
            class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200"
            ref="logoRef"
          >
            <div class="relative">
              <img
                src="/assets/img/taskeasy_logo.svg"
                alt="TaskEasy"
                class="h-7 w-auto"
              />
            </div>
          </router-link>
        </div>

        <!-- Right Side Menu -->
        <div
          class="hidden sm:flex sm:items-center space-x-3 lg:space-x-4 xl:space-x-6"
        >
          <!-- Navigation Links -->
          <router-link
            to="/"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/' }"
          >
            <Icon icon="lucide:layout-dashboard" class="w-4 h-4" />
            <span>Dashboard</span>
          </router-link>
          <router-link
            to="/tasks"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/tasks' }"
          >
            <Icon icon="lucide:clipboard-check" class="w-4 h-4" />
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/admin' }"
          >
            <Icon icon="lucide:settings" class="w-4 h-4" />
            Admin
          </router-link>

          <!-- User Profile -->
          <div class="relative" ref="userMenuRef">
            <button
              @click="userMenuOpen = !userMenuOpen"
              class="flex items-center gap-2 p-2 rounded-lg hover:bg-background-50 transition-colors group"
            >
              <div
                class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-medium text-sm"
              >
                {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
              </div>
              <Icon
                icon="lucide:chevron-down"
                class="w-4 h-4 text-text-400 transition-transform group-hover:text-text-600"
                :class="{ 'rotate-180': userMenuOpen }"
              />
            </button>

            <!-- User Dropdown Menu -->
            <Transition name="dropdown">
              <div
                v-if="userMenuOpen"
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-background-100 py-1 z-50"
              >
                <div class="px-4 py-3 border-b border-background-100">
                  <p class="text-sm font-medium text-text-900">
                    {{ authStore.user?.name }}
                  </p>
                  <p class="text-xs text-text-600">
                    {{ authStore.user?.email }}
                  </p>
                </div>
                <button
                  @click="handleLogout"
                  class="w-full flex items-center gap-2 px-4 py-2 text-sm text-text-700 hover:bg-red-50 hover:text-red-600 transition-colors"
                >
                  <Icon icon="lucide:log-out" class="w-4 h-4" />
                  Sign out
                </button>
              </div>
            </Transition>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="sm:hidden flex items-center">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="inline-flex items-center justify-center p-3 rounded-xl text-text-400 hover:text-text-500 hover:bg-background-100 transition-colors"
          >
            <Icon v-if="!mobileMenuOpen" icon="lucide:menu" class="h-7 w-7" />
            <Icon v-else icon="lucide:x" class="h-7 w-7" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition name="mobile-menu">
      <div
        v-if="mobileMenuOpen"
        class="sm:hidden bg-white/95 backdrop-blur-lg border-t border-background-200"
      >
        <div class="px-6 pt-4 pb-4 space-y-2">
          <router-link
            to="/"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="lucide:layout-dashboard" class="w-6 h-6 mr-4" />
            Dashboard
          </router-link>
          <router-link
            to="/tasks"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/tasks' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="lucide:clipboard-check" class="w-6 h-6 mr-4" />
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/admin' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="lucide:settings" class="w-6 h-6 mr-4" />
            Admin
          </router-link>
        </div>
        <div
          class="pt-4 pb-4 border-t border-background-200 flex flex-col gap-1"
        >
          <div class="flex items-center px-6 mb-4 gap-2">
            <div
              class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md"
            >
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="ml-4">
              <div class="text-lg font-semibold text-text-800">
                {{ authStore.user?.name }}
              </div>
              <div class="text-sm text-text-500">
                {{ authStore.user?.email }}
              </div>
            </div>
          </div>
          <button
            @click="handleLogout"
            class="w-full flex items-center px-6 py-3 text-lg font-medium text-red-700 hover:text-red-800 hover:bg-red-50 transition-colors rounded-xl mx-6"
          >
            <Icon icon="lucide:log-out" class="w-6 h-6 mr-4" />
            Sign out
          </button>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { Icon } from "@iconify/vue";
import gsap from "gsap";

const router = useRouter();
const authStore = useAuthStore();
const mobileMenuOpen = ref(false);
const userMenuOpen = ref(false);
const showNotifications = ref(false);
const logoRef = ref(null);
const userMenuRef = ref(null);

onMounted(() => {
  // Logo entrance animation
  gsap.fromTo(
    logoRef.value,
    {
      opacity: 0,
      y: -20,
      scale: 0.8,
    },
    {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.8,
      ease: "power2.out",
    }
  );

  // Navigation items stagger animation
  gsap.fromTo(
    ".nav-item",
    {
      opacity: 0,
      y: -10,
    },
    {
      opacity: 1,
      y: 0,
      duration: 0.5,
      stagger: 0.1,
      delay: 0.3,
      ease: "power2.out",
    }
  );

  // Click outside to close menus
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});

const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    userMenuOpen.value = false;
  }
};

async function handleLogout() {
  try {
    // Clear authentication immediately for faster response
    await authStore.logout();

    // Navigate to login page
    await router.push("/login");

    // Reset app appearance after navigation to prevent black screen
    gsap.set("#app", {
      opacity: 1,
      scale: 1,
      clearProps: "all",
    });
  } catch (error) {
    console.error("Logout error:", error);

    // Ensure app is visible even if logout fails
    gsap.set("#app", {
      opacity: 1,
      scale: 1,
      clearProps: "all",
    });
  }
}
</script>

<style scoped>
/* Navigation Items */
.nav-item {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  color: rgb(107 114 128);
  text-decoration: none;
}

.nav-item:hover {
  color: rgb(22 163 74);
  background-color: rgb(240 253 244);
}

.nav-active {
  color: rgb(21 128 61);
  background-color: rgb(220 252 231);
}

/* Mobile Navigation */
.mobile-nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  font-weight: 500;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  color: rgb(75 85 99);
  gap: 0.5rem;
}

.mobile-nav-item:hover {
  color: rgb(22 163 74);
  background-color: rgb(240 253 244);
}

.mobile-nav-active {
  color: rgb(21 128 61);
  background-color: rgb(220 252 231);
}

/* Transitions */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease-out;
}

.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}

.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}

.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
