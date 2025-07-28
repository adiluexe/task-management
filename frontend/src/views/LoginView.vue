<template>
  <div class="min-h-screen bg-background-50 flex items-center justify-center">
    <div class="w-full max-w-md">
      <!-- Logo and Header -->
      <div class="text-center mb-8">
        <img
          src="/assets/img/taskeasy_logo.svg"
          alt="Taskeasy Logo"
          class="h-12 w-auto mx-auto mb-4"
        />
        <h2 class="text-2xl font-semibold text-text-900 mb-2">
          Sign in to your account
        </h2>
        <p class="text-text-600">
          Or
          <router-link
            to="/register"
            class="font-medium text-primary-600 hover:text-primary-500"
            >create a new account</router-link
          >
        </p>
      </div>

      <!-- Login Form -->
      <div class="bg-white rounded-lg border border-background-100 p-6">
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label
              for="email"
              class="block text-sm font-medium text-text-700 mb-2"
              >Email address</label
            >
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="w-full px-3 py-2.5 border border-background-200 rounded-lg text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              :class="{ 'border-red-300': errors.email }"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">
              {{ errors.email[0] }}
            </p>
          </div>
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-text-700 mb-2"
              >Password</label
            >
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="w-full px-3 py-2.5 border border-background-200 rounded-lg text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              :class="{ 'border-red-300': errors.password }"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ errors.password[0] }}
            </p>
          </div>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                v-model="form.remember"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-background-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-text-700"
                >Remember me</label
              >
            </div>
          </div>
          
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2.5 px-4 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <span v-if="loading" class="flex items-center justify-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              Signing in...
            </span>
            <span v-else>Sign in</span>
          </button>
          <!-- Error Message -->
          <div
            v-if="errorMessage"
            class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
          >
            {{ errorMessage }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const errorMessage = ref("");
const errors = ref({});

const form = reactive({
  email: "",
  password: "",
  remember: false,
});

onMounted(() => {
  // Ensure the page is always visible when mounted
  const app = document.getElementById('app');
  if (app) {
    app.style.opacity = '1';
    app.style.transform = 'scale(1)';
  }
});

const handleLogin = async () => {
  loading.value = true;
  errorMessage.value = "";
  errors.value = {};

  try {
    await authStore.login(form);
    router.push("/");
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else if (error.response?.status === 401) {
      errorMessage.value = "Invalid email or password";
    } else {
      errorMessage.value = "An error occurred. Please try again.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
@keyframes float {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-20px);
  }
}
.animate-float {
  animation: float 12s ease-in-out infinite;
}
@keyframes float2 {
  0%,
  100% {
    transform: translateY(0) scale(1);
  }
  50% {
    transform: translateY(30px) scale(1.05);
  }
}
.animate-float2 {
  animation: float2 16s ease-in-out infinite;
}
@keyframes logo-fadein {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-logo-fadein {
  animation: logo-fadein 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes card-fadein {
  0% {
    opacity: 0;
    transform: translateY(30px) scale(0.98);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
.animate-card-fadein {
  animation: card-fadein 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}
.animated-input:focus {
  border-color: var(--color-primary-400);
  box-shadow: 0 0 0 2px var(--color-primary-100);
  background: #fff;
  transition: box-shadow 0.2s, border-color 0.2s, background 0.2s;
}
</style>
