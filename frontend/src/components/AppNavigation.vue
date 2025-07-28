<template>
  <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <router-link to="/" class="text-xl font-bold text-primary-600">
              TaskManager
            </router-link>
          </div>
          
          <!-- Navigation Links -->
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link
              to="/"
              class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors"
              active-class="border-primary-500 text-gray-900"
            >
              Dashboard
            </router-link>
            <router-link
              to="/tasks"
              class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors"
              active-class="border-primary-500 text-gray-900"
            >
              Tasks
            </router-link>
            <router-link
              v-if="authStore.isAdmin"
              to="/admin"
              class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors"
              active-class="border-primary-500 text-gray-900"
            >
              Admin
            </router-link>
          </div>
        </div>
        
        <!-- User Menu -->
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <!-- User Profile Dropdown -->
          <div class="ml-3 relative">
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-700">
                {{ authStore.user?.name }}
              </span>
              <button
                @click="handleLogout"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors"
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
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Mobile Menu -->
    <div v-if="mobileMenuOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <router-link
          to="/"
          class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300"
          active-class="border-primary-500 text-primary-700 bg-primary-50"
          @click="mobileMenuOpen = false"
        >
          Dashboard
        </router-link>
        <router-link
          to="/tasks"
          class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300"
          active-class="border-primary-500 text-primary-700 bg-primary-50"
          @click="mobileMenuOpen = false"
        >
          Tasks
        </router-link>
        <router-link
          v-if="authStore.isAdmin"
          to="/admin"
          class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300"
          active-class="border-primary-500 text-primary-700 bg-primary-50"
          @click="mobileMenuOpen = false"
        >
          Admin
        </router-link>
      </div>
      <div class="pt-4 pb-3 border-t border-gray-200">
        <div class="flex items-center px-4">
          <div class="text-base font-medium text-gray-800">{{ authStore.user?.name }}</div>
          <div class="text-sm text-gray-500">{{ authStore.user?.email }}</div>
        </div>
        <div class="mt-3 space-y-1">
          <button
            @click="handleLogout"
            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100"
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/counter'

const router = useRouter()
const authStore = useAuthStore()
const mobileMenuOpen = ref(false)

async function handleLogout() {
  try {
    await authStore.logout()
    router.push('/login')
  } catch (error) {
    console.error('Logout error:', error)
  }
}
</script>
