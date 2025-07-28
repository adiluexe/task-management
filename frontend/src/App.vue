<script setup>
import { onMounted, watch } from "vue";
import { RouterView, useRoute } from "vue-router";
import { useAuthStore } from "./stores/auth";
import AppNavigation from "./components/AppNavigation.vue";
import gsap from "gsap";

const authStore = useAuthStore();
const route = useRoute();

onMounted(() => {
  authStore.initializeAuth();
});

watch(
  () => route.fullPath,
  () => {
    gsap.fromTo(
      "#page-content",
      { opacity: 0, y: 30 },
      { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" }
    );
  }
);
</script>

<template>
  <div id="app" class="min-h-screen bg-background-50 font-sans">
    <AppNavigation v-if="authStore.isAuthenticated" />
    <main class="flex-1">
      <div id="page-content">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<style>
/* Global styles */
body {
  margin: 0;
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    sans-serif;
}

#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
