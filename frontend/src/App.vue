<script setup>
import { onMounted } from 'vue'
import { RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import AppNavigation from './components/AppNavigation.vue'

const authStore = useAuthStore()

onMounted(() => {
  authStore.initializeAuth()
})
</script>

<template>
  <div id="app" class="min-h-screen bg-background-50">
    <AppNavigation v-if="authStore.isAuthenticated" />
    
    <main class="flex-1">
      <RouterView />
    </main>
  </div>
</template>

<style>
/* Global styles */
body {
  margin: 0;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
