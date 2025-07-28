<template>
  <nav class="bg-white/80 backdrop-blur-lg border-b border-background-200/50 sticky top-0 z-50 shadow-sm">
    <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
      <div class="flex justify-between h-20">
        <div class="flex items-center">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center group">
            <router-link 
              to="/" 
              class="flex items-center space-x-4 hover:scale-105 transition-transform duration-200"
              ref="logoRef"
            >
              <div class="relative">
                <img
                  src="/assets/img/taskeasy_logo.svg"
                  alt="TaskEasy"
                  class="h-10 w-auto"
                />
              </div>
              
            </router-link>
          </div>
        </div>

        <!-- Right Side Menu -->
        <div class="hidden sm:ml-8 sm:flex sm:items-center space-x-4">
          <!-- Navigation Links -->
          <router-link
            to="/"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/' }"
          >
            <Icon icon="solar:widget-2-bold-duotone" class="w-5 h-5 mr-3" />
            Dashboard
          </router-link>
          <router-link
            to="/tasks"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/tasks' }"
          >
            <Icon icon="solar:checklist-bold-duotone" class="w-5 h-5 mr-3" />
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="nav-item"
            :class="{ 'nav-active': $route.path === '/admin' }"
          >
            <Icon icon="solar:settings-bold-duotone" class="w-5 h-5 mr-3" />
            Admin
          </router-link>

          <!-- Divider -->
          <div class="w-px h-8 bg-background-300 mx-4"></div>

          <!-- Notifications Bell -->
          <button 
            class="relative p-3 text-text-400 hover:text-text-500 transition-colors rounded-xl hover:bg-background-100"
            @click="showNotifications = !showNotifications"
          >
            <Icon icon="solar:bell-bold-duotone" class="w-6 h-6" />
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-accent-500 rounded-full animate-pulse"></div>
          </button>

          <!-- User Profile -->
          <div class="relative" ref="userMenuRef">
            <button
              @click="userMenuOpen = !userMenuOpen"
              class="flex items-center space-x-4 p-3 rounded-xl hover:bg-background-100 transition-colors group"
            >
              <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-md">
                  {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
                </div>
                <div class="hidden lg:block text-left">
                  <div class="text-sm font-semibold text-text-900">{{ authStore.user?.name }}</div>
                  <div class="text-xs text-text-500">{{ authStore.user?.email }}</div>
                </div>
              </div>
              <Icon icon="solar:alt-arrow-down-bold" class="w-5 h-5 text-text-400 transition-transform group-hover:text-text-600" :class="{ 'rotate-180': userMenuOpen }" />
            </button>

            <!-- User Dropdown Menu -->
            <Transition name="dropdown">
              <div
                v-if="userMenuOpen"
                class="absolute right-0 mt-3 w-64 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/60 py-2 z-50"
              >
                <div class="px-6 py-4 border-b border-background-100">
                  <p class="text-base font-semibold text-text-900">{{ authStore.user?.name }}</p>
                  <p class="text-sm text-text-500">{{ authStore.user?.email }}</p>
                </div>
                <button
                  @click="handleLogout"
                  class="w-full flex items-center px-6 py-3 text-sm font-medium text-text-700 hover:bg-red-50 hover:text-red-800 transition-colors"
                >
                  <Icon icon="solar:logout-3-bold-duotone" class="w-5 h-5 mr-3" />
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
            <Icon v-if="!mobileMenuOpen" icon="solar:hamburger-menu-bold" class="h-7 w-7" />
            <Icon v-else icon="solar:close-circle-bold" class="h-7 w-7" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition name="mobile-menu">
      <div v-if="mobileMenuOpen" class="sm:hidden bg-white/95 backdrop-blur-lg border-t border-background-200">
        <div class="px-6 pt-4 pb-4 space-y-2">
          <router-link
            to="/"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="solar:widget-2-bold-duotone" class="w-6 h-6 mr-4" />
            Dashboard
          </router-link>
          <router-link
            to="/tasks"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/tasks' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="solar:checklist-bold-duotone" class="w-6 h-6 mr-4" />
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/admin' }"
            @click="mobileMenuOpen = false"
          >
            <Icon icon="solar:settings-bold-duotone" class="w-6 h-6 mr-4" />
            Admin
          </router-link>
        </div>
        <div class="pt-4 pb-4 border-t border-background-200">
          <div class="flex items-center px-6 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="ml-4">
              <div class="text-lg font-semibold text-text-800">{{ authStore.user?.name }}</div>
              <div class="text-sm text-text-500">{{ authStore.user?.email }}</div>
            </div>
          </div>
          <button
            @click="handleLogout"
            class="w-full flex items-center px-6 py-3 text-lg font-medium text-red-700 hover:text-red-800 hover:bg-red-50 transition-colors rounded-xl mx-6"
          >
            <Icon icon="solar:logout-3-bold-duotone" class="w-6 h-6 mr-4" />
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
import { Icon } from '@iconify/vue';
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
      scale: 0.8 
    },
    { 
      opacity: 1, 
      y: 0, 
      scale: 1,
      duration: 0.8, 
      ease: "back.out(1.7)" 
    }
  );

  // Navigation items stagger animation
  gsap.fromTo(
    ".nav-item",
    { 
      opacity: 0, 
      y: -10 
    },
    { 
      opacity: 1, 
      y: 0,
      duration: 0.5, 
      stagger: 0.1, 
      delay: 0.3,
      ease: "power2.out" 
    }
  );

  // Click outside to close menus
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    userMenuOpen.value = false;
  }
};

async function handleLogout() {
  try {
    // Logout animation
    await gsap.to("#app", {
      opacity: 0,
      scale: 0.95,
      duration: 0.3,
      ease: "power2.in"
    });
    
    await authStore.logout();
    router.push("/login");
  } catch (error) {
    console.error("Logout error:", error);
  }
}
</script>

<style scoped>
/* Navigation Items */
.nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.25rem;
  border-radius: 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s ease;
  color: rgb(75 85 99);
  position: relative;
  overflow: hidden;
  margin: 0 0.25rem;
}

.nav-item:hover {
  color: rgb(22 163 74);
  background-color: rgb(240 253 244);
  transform: translateY(-1px);
}

.nav-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(86, 179, 106, 0.1), transparent);
  transition: left 0.5s;
}

.nav-item:hover::before {
  left: 100%;
}

.nav-active {
  color: rgb(21 128 61);
  background-color: transparent;
  position: relative;
}

.nav-active::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, transparent, rgb(34 197 94), transparent);
  border-radius: 2px;
  animation: underline-expand 0.3s ease-out;
}

@keyframes underline-expand {
  0% {
    width: 0%;
    opacity: 0;
  }
  100% {
    width: 100%;
    opacity: 1;
  }
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
}

.mobile-nav-item:hover {
  color: rgb(22 163 74);
  background-color: rgb(240 253 244);
  transform: translateY(-1px);
}

.mobile-nav-active {
  color: rgb(21 128 61);
  background-color: rgb(220 252 231);
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

/* Transitions */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease-out;
}

.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-10px) scale(0.95);
}

.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.95);
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

/* Custom scrollbar for mobile menu */
.mobile-menu-content {
  scrollbar-width: thin;
  scrollbar-color: rgba(86, 179, 106, 0.3) transparent;
}

.mobile-menu-content::-webkit-scrollbar {
  width: 4px;
}

.mobile-menu-content::-webkit-scrollbar-track {
  background: transparent;
}

.mobile-menu-content::-webkit-scrollbar-thumb {
  background: rgba(86, 179, 106, 0.3);
  border-radius: 2px;
}

/* User avatar glow effect */
.user-avatar {
  box-shadow: 0 0 0 3px rgba(86, 179, 106, 0.1);
  transition: box-shadow 0.3s ease;
}

.user-avatar:hover {
  box-shadow: 0 0 0 3px rgba(86, 179, 106, 0.2);
}

/* Notification pulse */
@keyframes notification-pulse {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
}

.notification-dot {
  animation: notification-pulse 2s infinite;
}
</style>
