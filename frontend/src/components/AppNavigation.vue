<template>
  <nav class="bg-white/80 backdrop-blur-lg border-b border-background-200/50 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center group">
            <router-link 
              to="/" 
              class="flex items-center space-x-3 hover:scale-105 transition-transform duration-200"
              ref="logoRef"
            >
              <div class="relative">
                <img
                  src="/assets/img/taskeasy_logo.svg"
                  alt="TaskEasy"
                  class="h-9 w-auto drop-shadow-sm"
                />
                <div class="absolute inset-0 bg-primary-200 rounded-full blur-xl opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
              </div>
              <span class="text-xl font-bold text-text-900 hidden sm:block">
                Task<span class="text-primary-600">Easy</span>
              </span>
            </router-link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden sm:ml-8 sm:flex sm:space-x-1">
            <router-link
              to="/"
              class="nav-item"
              :class="{ 'nav-active': $route.path === '/' }"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5v4" />
              </svg>
              Dashboard
            </router-link>
            <router-link
              to="/tasks"
              class="nav-item"
              :class="{ 'nav-active': $route.path === '/tasks' }"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
              Tasks
            </router-link>
            <router-link
              v-if="authStore.isAdmin"
              to="/admin"
              class="nav-item"
              :class="{ 'nav-active': $route.path === '/admin' }"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Admin
            </router-link>
          </div>
        </div>

        <!-- User Menu -->
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <!-- Notifications Bell -->
          <button 
            class="relative p-2 text-text-400 hover:text-text-500 transition-colors rounded-full hover:bg-background-100"
            @click="showNotifications = !showNotifications"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 19.5L9 18v-6a3 3 0 013-3v0a3 3 0 013 3v6l-1.5 1.5M9 13h6" />
            </svg>
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-accent-500 rounded-full animate-pulse"></div>
          </button>

          <!-- User Profile -->
          <div class="ml-4 relative" ref="userMenuRef">
            <button
              @click="userMenuOpen = !userMenuOpen"
              class="flex items-center space-x-3 p-2 rounded-lg hover:bg-background-100 transition-colors group"
            >
              <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-medium text-sm shadow-md">
                  {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
                </div>
                <div class="hidden md:block text-left">
                  <div class="text-sm font-medium text-text-900">{{ authStore.user?.name }}</div>
                  <div class="text-xs text-text-500">{{ authStore.user?.email }}</div>
                </div>
              </div>
              <svg class="w-4 h-4 text-text-400 transition-transform group-hover:text-text-600" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- User Dropdown Menu -->
            <Transition name="dropdown">
              <div
                v-if="userMenuOpen"
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-background-200 py-1 z-50"
              >
                <div class="px-4 py-3 border-b border-background-100">
                  <p class="text-sm font-medium text-text-900">{{ authStore.user?.name }}</p>
                  <p class="text-xs text-text-500">{{ authStore.user?.email }}</p>
                </div>
                <button
                  @click="handleLogout"
                  class="w-full flex items-center px-4 py-2 text-sm text-text-700 hover:bg-accent-50 hover:text-accent-800 transition-colors"
                >
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
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
            class="inline-flex items-center justify-center p-2 rounded-md text-text-400 hover:text-text-500 hover:bg-background-100 transition-colors"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    <Transition name="mobile-menu">
      <div v-if="mobileMenuOpen" class="sm:hidden bg-white/95 backdrop-blur-lg border-t border-background-200">
        <div class="px-4 pt-2 pb-3 space-y-1">
          <router-link
            to="/"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/' }"
            @click="mobileMenuOpen = false"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
            </svg>
            Dashboard
          </router-link>
          <router-link
            to="/tasks"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/tasks' }"
            @click="mobileMenuOpen = false"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Tasks
          </router-link>
          <router-link
            v-if="authStore.isAdmin"
            to="/admin"
            class="mobile-nav-item"
            :class="{ 'mobile-nav-active': $route.path === '/admin' }"
            @click="mobileMenuOpen = false"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            </svg>
            Admin
          </router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-background-200">
          <div class="flex items-center px-4 mb-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-medium shadow-md">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-text-800">{{ authStore.user?.name }}</div>
              <div class="text-sm text-text-500">{{ authStore.user?.email }}</div>
            </div>
          </div>
          <button
            @click="handleLogout"
            class="w-full flex items-center px-4 py-2 text-base font-medium text-accent-700 hover:text-accent-800 hover:bg-accent-50 transition-colors"
          >
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
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
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.2s ease;
  color: rgb(75 85 99);
  position: relative;
  overflow: hidden;
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
  background-color: rgb(220 252 231);
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
  position: relative;
}

.nav-active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgb(34 197 94), transparent);
  border-radius: 1px;
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
