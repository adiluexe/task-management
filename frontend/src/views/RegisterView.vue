<template>
  <div
    class="min-h-screen bg-gradient-to-br from-primary-50 via-background-50 to-accent-50 flex items-center justify-center p-4 relative overflow-hidden"
  >
    <!-- Enhanced Background Elements -->
    <div class="absolute inset-0 pointer-events-none z-0">
      <div
        class="absolute top-1/4 left-1/3 w-96 h-96 bg-gradient-to-br from-primary-200/30 via-secondary-200/20 to-accent-200/30 rounded-full opacity-60 blur-3xl animate-float"
      ></div>
      <div
        class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-tr from-accent-200/20 via-primary-200/30 to-secondary-200/20 rounded-full opacity-40 blur-2xl animate-float2"
      ></div>
      <div
        class="absolute top-0 right-1/4 w-64 h-64 bg-gradient-to-bl from-primary-100/40 to-accent-100/30 rounded-full opacity-50 blur-xl animate-float"
        style="animation-delay: -5s"
      ></div>
    </div>

    <div class="w-full max-w-md flex flex-col items-center z-10">
      <!-- Logo and Header -->
      <div class="flex flex-col items-center gap-4 text-center">
        <img
          src="/assets/img/taskeasy_logo.svg"
          alt="Taskeasy Logo"
          class="h-16 w-auto animate-logo-fadein"
        />
        <div class="flex flex-col gap-2">
          <h2 class="text-3xl font-bold text-text-900 tracking-tight">
            Create your account
          </h2>
          <p class="text-text-600">
            Or
            <router-link
              to="/login"
              class="font-semibold text-primary-600 hover:text-primary-500 underline underline-offset-2 transition-colors"
              >sign in to your existing account</router-link
            >
          </p>
        </div>
      </div>

      <!-- Registration Form -->
      <div
        class="mt-8 w-full bg-white/80 backdrop-blur-lg rounded-2xl border border-background-200/50 p-8 shadow-xl animate-card-fadein"
      >
        <form @submit.prevent="handleRegister" class="flex flex-col gap-6">
          <div class="flex flex-col gap-2">
            <label for="name" class="text-sm font-semibold text-text-700"
              >Full name</label
            >
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              autocomplete="name"
              required
              placeholder="Enter your full name"
              class="w-full px-4 py-3 border border-background-200 rounded-xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white"
              :class="{
                'border-red-300 focus:border-red-500 focus:ring-red-500/20':
                  errors.name,
              }"
            />
            <p v-if="errors.name" class="text-sm text-red-600 font-medium">
              {{ errors.name[0] }}
            </p>
          </div>

          <div class="flex flex-col gap-2">
            <label for="email" class="text-sm font-semibold text-text-700"
              >Email address</label
            >
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              placeholder="Enter your email address"
              class="w-full px-4 py-3 border border-background-200 rounded-xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white"
              :class="{
                'border-red-300 focus:border-red-500 focus:ring-red-500/20':
                  errors.email,
              }"
            />
            <p v-if="errors.email" class="text-sm text-red-600 font-medium">
              {{ errors.email[0] }}
            </p>
          </div>

          <div class="flex flex-col gap-2">
            <label for="password" class="text-sm font-semibold text-text-700"
              >Password</label
            >
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="new-password"
              required
              placeholder="Create a strong password"
              class="w-full px-4 py-3 border border-background-200 rounded-xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white"
              :class="{
                'border-red-300 focus:border-red-500 focus:ring-red-500/20':
                  errors.password,
              }"
            />
            <p v-if="errors.password" class="text-sm text-red-600 font-medium">
              {{ errors.password[0] }}
            </p>
          </div>

          <div class="flex flex-col gap-2">
            <label
              for="password_confirmation"
              class="text-sm font-semibold text-text-700"
              >Confirm password</label
            >
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              required
              placeholder="Confirm your password"
              class="w-full px-4 py-3 border border-background-200 rounded-xl text-text-900 placeholder-text-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white"
              :class="{
                'border-red-300 focus:border-red-500 focus:ring-red-500/20':
                  errors.password_confirmation,
              }"
            />
            <p
              v-if="errors.password_confirmation"
              class="text-sm text-red-600 font-medium"
            >
              {{ errors.password_confirmation[0] }}
            </p>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-accent-700 focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
          >
            <span v-if="loading" class="flex items-center justify-center gap-3">
              <svg
                class="animate-spin h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
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
              Creating account...
            </span>
            <span v-else>Create your account</span>
          </button>

          <!-- Error Message -->
          <div
            v-if="errorMessage"
            class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium flex items-center gap-3"
          >
            <svg
              class="h-5 w-5 text-red-500 flex-shrink-0"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                clip-rule="evenodd"
              />
            </svg>
            {{ errorMessage }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const errorMessage = ref("");
const errors = ref({});

const form = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const handleRegister = async () => {
  loading.value = true;
  errorMessage.value = "";
  errors.value = {};

  try {
    await authStore.register(form);
    router.push("/");
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
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
