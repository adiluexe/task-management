<template>
  <div class="min-h-screen bg-background-50">
    <div class="max-w-full px-6 sm:px-8 lg:px-12 xl:px-16 py-12">
      <!-- Page header -->
      <div class="dashboard-header text-left max-w-7xl">
        <h1 class="text-4xl font-semibold text-text-900 mb-4">Dashboard</h1>
        <p class="text-text-600 text-xl">
          Welcome back! Here's an overview of your tasks.
        </p>
      </div>

      <!-- Stats overview -->
      <div
        class="dashboard-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-full mx-auto"
      >
        <div
          v-for="(stat, idx) in [
            {
              label: 'Total Tasks',
              value: Math.round(animatedStats.total),
              color: 'primary',
            },
            {
              label: 'Completed',
              value: Math.round(animatedStats.completed),
              color: 'accent',
            },
            {
              label: 'Pending',
              value: Math.round(animatedStats.pending),
              color: 'secondary',
            },
            {
              label: 'Completion Rate',
              value: Math.round(animatedStats.completionRate) + '%',
              color: 'primary',
            },
          ]"
          :key="stat.label"
          class="bg-white rounded-2xl border border-background-200/60 p-10 hover:border-primary-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-dashboard-card group cursor-pointer"
        >
          <div class="flex items-center justify-between mb-6">
            <div
              :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110',
                stat.color === 'primary'
                  ? 'bg-primary-50 text-primary-600 group-hover:bg-primary-100'
                  : stat.color === 'accent'
                  ? 'bg-accent-50 text-accent-600 group-hover:bg-accent-100'
                  : 'bg-secondary-50 text-secondary-600 group-hover:bg-secondary-100',
              ]"
            >
              <svg
                v-if="stat.label === 'Total Tasks'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                />
              </svg>
              <svg
                v-else-if="stat.label === 'Completed'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <svg
                v-else-if="stat.label === 'Pending'"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <svg
                v-else
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <div
              class="text-3xl font-black text-text-900 group-hover:text-primary-600 transition-colors duration-300"
            >
              {{ stat.value }}
            </div>
          </div>
          <div
            class="text-base text-text-600 font-medium group-hover:text-text-700 transition-colors duration-300"
          >
            {{ stat.label }}
          </div>
          <div v-if="stat.label === 'Completion Rate'" class="w-full mt-3">
            <div
              class="w-full h-1.5 bg-background-100 rounded-full overflow-hidden"
            >
              <div
                class="h-1.5 rounded-full bg-primary-500 transition-all duration-500"
                :style="{
                  width: Math.round(animatedStats.completionRate) + '%',
                }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent tasks -->
      <div class="max-w-full mx-auto">
        <div
          class="bg-white rounded-2xl border border-background-200/60 shadow-sm hover:shadow-lg transition-shadow duration-300 animate-dashboard-card"
        >
          <div class="px-10 py-8 border-b border-background-100">
            <h3 class="text-2xl font-semibold text-text-900">Recent Tasks</h3>
            <p class="text-text-600 mt-2">Your latest task activity</p>
          </div>
          <div class="p-10">
            <div v-if="taskStore.loading" class="flex justify-center py-16">
              <div
                class="animate-spin rounded-full h-10 w-10 border-2 border-background-200 border-t-primary-600"
              ></div>
            </div>
            <div
              v-else-if="taskStore.tasks.length === 0"
              class="w-full min-h-[50vh] flex items-center justify-center py-20"
            >
              <div
                class="w-full max-w-4xl mx-auto text-center flex flex-col items-center justify-center gap-4"
              >
                <!-- Animated Illustration -->
                <div class="relative mb-12">
                  <!-- Main Container -->
                  <div
                    class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-primary-50 via-white to-accent-50 flex items-center justify-center relative overflow-hidden group shadow-lg"
                  >
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                      <div
                        class="absolute top-4 left-6 w-4 h-4 bg-primary-300 rounded-full animate-pulse"
                      ></div>
                      <div
                        class="absolute bottom-6 right-4 w-3 h-3 bg-accent-300 rounded-full animate-pulse animation-delay-500"
                      ></div>
                      <div
                        class="absolute top-1/2 left-4 w-2 h-2 bg-primary-400 rounded-full animate-pulse animation-delay-1000"
                      ></div>
                      <div
                        class="absolute bottom-4 left-1/2 w-2.5 h-2.5 bg-accent-400 rounded-full animate-pulse animation-delay-700"
                      ></div>
                    </div>

                    <!-- Central Icon -->
                    <svg
                      class="w-16 h-16 text-primary-400 group-hover:text-primary-500 transition-all duration-500 group-hover:scale-110"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                      />
                    </svg>

                    <!-- Floating Task Icons -->
                    <div
                      class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center floating-element shadow-sm"
                    >
                      <svg
                        class="w-4 h-4 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                    </div>
                    <div
                      class="absolute -bottom-2 -left-2 w-7 h-7 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center floating-element animation-delay-300 shadow-sm"
                    >
                      <svg
                        class="w-3.5 h-3.5 text-blue-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div
                      class="absolute top-1/4 -left-3 w-6 h-6 bg-gradient-to-br from-purple-100 to-violet-100 rounded-full flex items-center justify-center floating-element animation-delay-600 shadow-sm"
                    >
                      <svg
                        class="w-3 h-3 text-purple-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                        />
                      </svg>
                    </div>
                  </div>

                  <!-- Orbiting Elements -->
                  <div class="absolute inset-0 pointer-events-none">
                    <div
                      class="absolute top-8 left-8 w-3 h-3 bg-primary-200 rounded-full animate-bounce animation-delay-200"
                    ></div>
                    <div
                      class="absolute bottom-8 right-8 w-2 h-2 bg-accent-200 rounded-full animate-bounce animation-delay-800"
                    ></div>
                  </div>
                </div>

                <!-- Welcome Content -->
                <div class="mb-10 w-full max-w-2xl mx-auto">
                  <h3
                    class="text-3xl font-bold text-text-900 mb-4 gradient-text"
                  >
                    Ready to Get Started?
                  </h3>
                  <p class="text-text-600 mb-2 text-lg leading-relaxed">
                    No tasks yet! Create your first task and start your
                    productivity journey.
                  </p>
                  <p class="text-text-500 text-sm">
                    Organize your work, set priorities, and achieve your goals.
                  </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-6 w-full max-w-xl mx-auto">
                  <!-- Primary Action -->
                  <div class="flex justify-center">
                    <router-link
                      to="/tasks"
                      class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-2xl hover:from-primary-600 hover:to-primary-700 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 transform text-lg shadow-lg group"
                    >
                      <svg
                        class="w-6 h-6 mr-3 group-hover:rotate-90 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"
                        />
                      </svg>
                      Create Your First Task
                      <svg
                        class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 5l7 7-7 7"
                        />
                      </svg>
                    </router-link>
                  </div>
                </div>

                <!-- Tips Section -->
                <div
                  class="mt-12 p-6 bg-gradient-to-r from-gray-50/50 to-blue-50/30 rounded-2xl border border-gray-200/50 backdrop-blur-sm w-full max-w-3xl mx-auto flex flex-col items-center gap-1"
                >
                  <h4
                    class="text-md font-semibold text-gray-700 mb-3 flex items-center justify-center"
                  >
                    <svg
                      class="w-4 h-4 mr-2 text-yellow-500"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                      />
                    </svg>
                    Quick Tips
                  </h4>
                  <div
                    class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600"
                  >
                    <div
                      class="flex items-center justify-center sm:justify-start"
                    >
                      <svg
                        class="w-3 h-3 mr-2 text-red-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"
                        />
                      </svg>
                      Set priorities to stay focused
                    </div>
                    <div
                      class="flex items-center justify-center sm:justify-start"
                    >
                      <svg
                        class="w-3 h-3 mr-2 text-blue-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                      </svg>
                      Track deadlines effectively
                    </div>
                    <div
                      class="flex items-center justify-center sm:justify-start"
                    >
                      <svg
                        class="w-3 h-3 mr-2 text-green-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                      </svg>
                      Boost your productivity
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="space-y-4 flex flex-col gap-2">
              <div
                v-for="task in recentTasks"
                :key="task.id"
                @click="openTaskModal(task)"
                class="group relative overflow-hidden bg-white border border-background-100/80 rounded-2xl hover:border-primary-200/60 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 animate-dashboard-task cursor-pointer"
                style="backdrop-filter: blur(10px)"
              >
                <!-- Priority indicator with improved styling -->
                <div
                  class="absolute top-0 left-0 w-1 h-full transition-all duration-300"
                  :class="[
                    task.priority === 'high'
                      ? 'bg-gradient-to-b from-red-500 to-red-600 group-hover:from-red-400 group-hover:to-red-500'
                      : task.priority === 'medium'
                      ? 'bg-gradient-to-b from-accent-500 to-accent-600 group-hover:from-accent-400 group-hover:to-accent-500'
                      : 'bg-gradient-to-b from-primary-500 to-primary-600 group-hover:from-primary-400 group-hover:to-primary-500',
                  ]"
                ></div>

                <!-- Card content with proper alignment -->
                <div class="p-6 pl-8">
                  <!-- Header section with title and priority badge -->
                  <div class="flex items-center justify-between mb-4">
                    <div
                      class="flex items-center space-x-4 flex-1 min-w-0 gap-2"
                    >
                      <!-- Priority dot indicator -->
                      <div class="flex-shrink-0">
                        <div class="relative">
                          <div
                            :class="[
                              'w-3 h-3 rounded-full transition-all duration-300 group-hover:scale-125',
                              task.priority === 'high'
                                ? 'bg-red-500 group-hover:bg-red-600 shadow-red-200'
                                : task.priority === 'medium'
                                ? 'bg-accent-500 group-hover:bg-accent-600 shadow-accent-200'
                                : 'bg-primary-500 group-hover:bg-primary-600 shadow-primary-200',
                            ]"
                          ></div>
                          <div
                            :class="[
                              'absolute inset-0 rounded-full opacity-0 group-hover:opacity-30 transition-all duration-300 animate-ping',
                              task.priority === 'high'
                                ? 'bg-red-400'
                                : task.priority === 'medium'
                                ? 'bg-accent-400'
                                : 'bg-primary-400',
                            ]"
                          ></div>
                        </div>
                      </div>

                      <!-- Task title -->
                      <h4
                        class="text-lg font-semibold text-text-900 truncate group-hover:text-primary-700 transition-colors duration-300 leading-tight flex-1"
                      >
                        {{ task.title }}
                      </h4>
                    </div>

                    <!-- Priority badge aligned to the right -->
                    <div class="flex items-center space-x-2 ml-4">
                      <!-- Quick action buttons -->
                      <div
                        class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                      >
                        <!-- Mark as complete button -->
                        <button
                          v-if="task.status !== 'completed'"
                          @click.stop="markAsComplete(task)"
                          class="p-2 text-text-400 hover:text-accent-600 hover:bg-accent-50 rounded-lg transition-all duration-200 tooltip"
                          title="Mark as complete"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"
                            />
                          </svg>
                        </button>

                        <!-- Edit button -->
                        <button
                          @click.stop="openTaskModal(task)"
                          class="p-2 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 tooltip"
                          title="Edit task"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                          </svg>
                        </button>
                      </div>

                      <span
                        :class="[
                          'px-3 py-1.5 text-xs font-medium rounded-full transition-all duration-300 whitespace-nowrap',
                          task.priority === 'high'
                            ? 'bg-red-50 text-red-700 group-hover:bg-red-100 border border-red-100'
                            : task.priority === 'medium'
                            ? 'bg-accent-50 text-accent-700 group-hover:bg-accent-100 border border-accent-100'
                            : 'bg-primary-50 text-primary-700 group-hover:bg-primary-100 border border-primary-100',
                        ]"
                      >
                        {{
                          task.priority.charAt(0).toUpperCase() +
                          task.priority.slice(1)
                        }}
                      </span>

                      <!-- Action hint -->
                      <div
                        class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex-shrink-0"
                      >
                        <svg
                          class="w-4 h-4 text-text-400 group-hover:text-primary-500 transition-colors duration-300"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                          />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <!-- Description section -->
                  <div class="mb-4 ml-7">
                    <p
                      class="text-sm text-text-600 line-clamp-2 group-hover:text-text-700 transition-colors duration-300 leading-relaxed"
                    >
                      {{ task.description }}
                    </p>
                  </div>

                  <!-- Footer section with status badge -->
                  <div class="flex items-center justify-between ml-7">
                    <div class="flex items-center">
                      <!-- Status badge with improved styling -->
                      <span
                        :class="[
                          'inline-flex gap-1 items-center px-3 py-1.5 text-xs font-semibold rounded-full transition-all duration-300 shadow-sm',
                          task.status === 'completed'
                            ? 'bg-gradient-to-r from-accent-50 to-accent-100 text-accent-800 group-hover:from-accent-100 group-hover:to-accent-200 border border-accent-200'
                            : task.status === 'in_progress'
                            ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 group-hover:from-blue-100 group-hover:to-blue-200 border border-blue-200'
                            : 'bg-gradient-to-r from-background-50 to-background-100 text-text-700 group-hover:from-background-100 group-hover:to-background-200 border border-background-200',
                        ]"
                      >
                        <div
                          :class="[
                            'w-1.5 h-1.5 rounded-full',
                            task.status === 'completed'
                              ? 'bg-accent-500'
                              : task.status === 'in_progress'
                              ? 'bg-blue-500'
                              : 'bg-text-400',
                          ]"
                        ></div>
                        {{
                          task.status.charAt(0).toUpperCase() +
                          task.status.slice(1).replace("_", " ")
                        }}
                      </span>
                    </div>

                    <!-- Additional metadata could go here -->
                    <div
                      class="text-xs text-text-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    >
                      Click to view details
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-10 pt-8 border-t border-background-100">
              <router-link
                to="/tasks"
                class="w-full flex justify-center items-center px-8 py-4 text-base font-semibold text-text-700 bg-background-50 border border-background-200 rounded-2xl hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300"
                >View All Tasks</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Task Modal -->
    <TaskModal
      v-if="showTaskModal"
      :task="selectedTask"
      @close="closeTaskModal"
      @save="handleTaskSave"
    />
  </div>
</template>
<script setup>
import { computed, onMounted, ref, nextTick } from "vue";
import { useTaskStore } from "../stores/task";
import TaskModal from "../components/TaskModal.vue";
import gsap from "gsap";

const taskStore = useTaskStore();
const showTaskModal = ref(false);
const selectedTask = ref(null);

const animatedStats = ref({
  total: 0,
  completed: 0,
  pending: 0,
  completionRate: 0,
});

const completionRate = computed(() => {
  if (taskStore.taskStats.total === 0) return 0;
  return Math.round(
    (taskStore.taskStats.completed / taskStore.taskStats.total) * 100
  );
});

const recentTasks = computed(() => {
  return taskStore.tasks.slice(0, 5);
});

const animateStats = () => {
  gsap.to(animatedStats.value, {
    duration: 1.2,
    total: taskStore.taskStats.total,
    completed: taskStore.taskStats.completed,
    pending: taskStore.taskStats.pending,
    completionRate: completionRate.value,
    ease: "power2.out",
    onUpdate: () => {
      animatedStats.value = { ...animatedStats.value };
    },
  });
};

// Modal functionality
const openTaskModal = (task) => {
  selectedTask.value = task;
  showTaskModal.value = true;
};

const closeTaskModal = () => {
  showTaskModal.value = false;
  selectedTask.value = null;
};

const handleTaskSave = async (taskData) => {
  try {
    if (selectedTask.value) {
      // Update existing task
      await taskStore.updateTask(selectedTask.value.id, taskData);
    } else {
      // Create new task (though this won't be used from dashboard)
      await taskStore.createTask(taskData);
    }
    closeTaskModal();
    // Refresh stats animation
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to save task:", error);
    // You could add a toast notification here
  }
};

const markAsComplete = async (task) => {
  try {
    await taskStore.updateTask(task.id, {
      ...task,
      status: "completed",
    });
    // Refresh stats animation
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to mark task as complete:", error);
  }
};

onMounted(async () => {
  try {
    await taskStore.fetchTasks();
    await nextTick();
    animateStats();
  } catch (error) {
    console.error("Failed to fetch tasks:", error);
  }
});
</script>

<style scoped>
.dashboard-header,
.dashboard-stats {
  margin-bottom: 1rem;
}

.animate-dashboard-card {
  animation: dashboard-card-fadein 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dashboard-card-fadein {
  0% {
    opacity: 0;
    transform: translateY(40px) scale(0.96);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.animate-dashboard-task {
  animation: dashboard-task-fadein 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dashboard-task-fadein {
  0% {
    opacity: 0;
    transform: translateY(25px) scale(0.98);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Elegant hover animations */
.group:hover {
  transform: translateY(-2px);
}

.group:active {
  transform: translateY(0);
}

/* Line clamp utility for description text */
.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
}

/* Enhanced card shadows and effects */
.group:hover {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08),
    0 0 0 1px rgba(59, 130, 246, 0.05);
}

/* Subtle glassmorphism effect */
.group::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.4),
    transparent
  );
  opacity: 0;
  transition: opacity 0.3s ease;
}

.group:hover::before {
  opacity: 1;
}

/* Tooltip styles for action buttons */
.tooltip {
  position: relative;
}

.tooltip:hover::after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  white-space: nowrap;
  z-index: 10;
  opacity: 1;
  pointer-events: none;
}

.tooltip:hover::before {
  content: "";
  position: absolute;
  bottom: 94%;
  left: 50%;
  transform: translateX(-50%);
  border: 4px solid transparent;
  border-top-color: rgba(0, 0, 0, 0.8);
  z-index: 10;
}

/* Enhanced Empty State Animations */
.floating-element {
  animation: float-gentle 4s ease-in-out infinite;
}

@keyframes float-gentle {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  25% {
    transform: translateY(-4px) rotate(1deg);
  }
  50% {
    transform: translateY(-8px) rotate(0deg);
  }
  75% {
    transform: translateY(-4px) rotate(-1deg);
  }
}

.animation-delay-300 {
  animation-delay: 300ms;
}

.animation-delay-500 {
  animation-delay: 500ms;
}

.animation-delay-600 {
  animation-delay: 600ms;
}

.animation-delay-700 {
  animation-delay: 700ms;
}

.animation-delay-800 {
  animation-delay: 800ms;
}

.animation-delay-1000 {
  animation-delay: 1000ms;
}

.animation-delay-200 {
  animation-delay: 200ms;
}

/* Enhanced Gradient Text for Empty State */
.gradient-text {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #10b981 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  background-size: 200% 200%;
  animation: gradient-shift 6s ease-in-out infinite;
}

@keyframes gradient-shift {
  0%,
  100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

/* Improved Animation Performance */
.floating-element,
.gradient-text {
  will-change: transform;
  backface-visibility: hidden;
  perspective: 1000px;
}
</style>
