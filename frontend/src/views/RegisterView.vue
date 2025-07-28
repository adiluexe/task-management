<template>
  <div
    class="min-h-screen bg-gradient-to-br from-background-100 via-background-50 to-primary-100 flex items-center justify-center relative overflow-hidden"
  >
    <div class="absolute inset-0 pointer-events-none z-0">
      <div
        class="absolute top-1/4 left-1/3 w-96 h-96 bg-gradient-to-br from-primary-100 via-secondary-100 to-accent-100 rounded-full opacity-20 blur-xl animate-float"
      ></div>
      <div
        class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-tr from-accent-100 via-primary-100 to-secondary-100 rounded-full opacity-10 blur-lg animate-float2"
      ></div>
    </div>
    <div class="w-full max-w-md flex flex-col items-center z-10">
      <img
        src="/assets/img/taskeasy_logo.svg"
        alt="Taskeasy Logo"
        class="h-14 w-auto mb-3 animate-logo-fadein drop-shadow-lg"
      />
      <div
        class="text-center text-base text-text-500 mb-7 font-sans tracking-wide"
      >
        Effortless Task Management
      </div>
      <div
        class="bg-white/60 backdrop-blur-md py-12 px-10 shadow-2xl rounded-2xl border border-background-200 animate-card-fadein w-full flex flex-col items-center"
      >
        <h2
          class="text-center text-3xl font-bold text-text-900 font-sans mb-3 tracking-tight"
        >
          Create your account
        </h2>
        <p class="mb-8 text-center text-sm text-text-600">
          Or
          <router-link
            to="/login"
            class="font-medium text-primary-600 hover:text-primary-500 underline underline-offset-2"
            >sign in to your existing account</router-link
          >
        </p>
        <form @submit.prevent="handleRegister" class="space-y-7 w-full">
          <div>
            <label
              for="name"
              class="block text-sm font-medium text-text-700 mb-1"
              >Full name</label
            >
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              autocomplete="name"
              required
              class="animated-input appearance-none block w-full px-4 py-2 border border-background-300 rounded-lg placeholder-text-400 text-text-900 focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400 bg-white/80 shadow-sm transition-all"
              :class="{ 'border-accent-300': errors.name }"
            />
            <p v-if="errors.name" class="mt-2 text-sm text-accent-600">
              {{ errors.name[0] }}
            </p>
          </div>
          <div>
            <label
              for="email"
              class="block text-sm font-medium text-text-700 mb-1"
              >Email address</label
            >
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="animated-input appearance-none block w-full px-4 py-2 border border-background-300 rounded-lg placeholder-text-400 text-text-900 focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400 bg-white/80 shadow-sm transition-all"
              :class="{ 'border-accent-300': errors.email }"
            />
            <p v-if="errors.email" class="mt-2 text-sm text-accent-600">
              {{ errors.email[0] }}
            </p>
          </div>
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-text-700 mb-1"
              >Password</label
            >
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="new-password"
              required
              class="animated-input appearance-none block w-full px-4 py-2 border border-background-300 rounded-lg placeholder-text-400 text-text-900 focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400 bg-white/80 shadow-sm transition-all"
              :class="{ 'border-accent-300': errors.password }"
            />
            <p v-if="errors.password" class="mt-2 text-sm text-accent-600">
              {{ errors.password[0] }}
            </p>
          </div>
          <div>
            <label
              for="password_confirmation"
              class="block text-sm font-medium text-text-700 mb-1"
              >Confirm password</label
            >
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              required
              class="animated-input appearance-none block w-full px-4 py-2 border border-background-300 rounded-lg placeholder-text-400 text-text-900 focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400 bg-white/80 shadow-sm transition-all"
              :class="{ 'border-accent-300': errors.password_confirmation }"
            />
            <p
              v-if="errors.password_confirmation"
              class="mt-2 text-sm text-accent-600"
            >
              {{ errors.password_confirmation[0] }}
            </p>
          </div>
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-2.5 px-4 rounded-lg shadow-md text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-accent-500 hover:from-primary-600 hover:to-accent-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span v-if="loading" class="flex items-center">
                <svg
                  class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
              <span v-else>Create account</span>
            </button>
          </div>
          <div
            v-if="errorMessage"
            class="rounded-md bg-accent-50 p-4 mt-2 w-full"
          >
            <div class="flex items-center">
              <svg
                class="h-5 w-5 text-accent-400 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="text-sm font-medium text-accent-800">{{
                errorMessage
              }}</span>
            </div>
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
