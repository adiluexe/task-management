<script setup>
import { onMounted, watch, nextTick } from "vue";
import { RouterView, useRoute } from "vue-router";
import { useAuthStore } from "./stores/auth";
import AppNavigation from "./components/AppNavigation.vue";
import gsap from "gsap";

const authStore = useAuthStore();
const route = useRoute();

onMounted(() => {
  authStore.initializeAuth();
  
  // Initial page load animation - ensure app is always visible
  gsap.set("#app", { opacity: 1 });
  gsap.fromTo(
    "#app",
    { opacity: 0 },
    { opacity: 1, duration: 0.6, ease: "power2.out" }
  );
});

watch(
  () => route.fullPath,
  async () => {
    // Ensure app stays visible during route transitions
    gsap.set("#app", { opacity: 1, scale: 1 });
    
    // Smooth page transitions
    await nextTick();
    
    gsap.fromTo(
      "#page-content",
      { opacity: 0, y: 20, scale: 0.98 },
      { 
        opacity: 1, 
        y: 0, 
        scale: 1,
        duration: 0.4, 
        ease: "power2.out",
        clearProps: "all"
      }
    );
  }
);
</script>

<template>
  <div id="app" class="min-h-screen bg-gradient-to-br from-background-50 via-background-100 to-background-200 font-sans antialiased">
    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-accent-50 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>
    </div>

    <!-- Main app content -->
    <div class="relative z-10">
      <AppNavigation v-if="authStore.isAuthenticated" />
      
      <main class="flex-1">
        <div id="page-content" class="will-change-transform">
          <RouterView v-slot="{ Component, route }">
            <Transition
              name="page"
              mode="out-in"
              @enter="onPageEnter"
              @leave="onPageLeave"
            >
              <component :is="Component" :key="route.path" />
            </Transition>
          </RouterView>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    onPageEnter(el, done) {
      gsap.fromTo(
        el,
        { opacity: 0, y: 30, scale: 0.95 },
        { 
          opacity: 1, 
          y: 0, 
          scale: 1,
          duration: 0.5, 
          ease: "power2.out",
          onComplete: done
        }
      );
    },
    onPageLeave(el, done) {
      gsap.to(el, {
        opacity: 0,
        y: -20,
        scale: 1.02,
        duration: 0.3,
        ease: "power2.in",
        onComplete: done
      });
    }
  }
}
</script>

<style>
/* Global styles */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  font-feature-settings: 'cv11', 'ss01';
  font-variation-settings: 'opsz' 32;
}

#app {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgb(203 213 225 / 0.5);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgb(148 163 184 / 0.8);
}

/* Page transitions */
.page-enter-active,
.page-leave-active {
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.page-enter-from {
  opacity: 0;
  transform: translateY(20px) scale(0.98);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(1.02);
}

/* Glass morphism utility */
.glass {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Floating animation */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.float {
  animation: float 6s ease-in-out infinite;
}

/* Pulse glow effect */
@keyframes pulse-glow {
  0%, 100% { box-shadow: 0 0 20px rgba(86, 179, 106, 0.3); }
  50% { box-shadow: 0 0 30px rgba(86, 179, 106, 0.6); }
}

.pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}
</style>
