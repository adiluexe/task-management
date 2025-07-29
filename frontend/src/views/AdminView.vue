<template>
  <div
    class="min-h-screen bg-gradient-to-br from-primary-50 via-background-50 to-accent-50"
  >
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
      <!-- Enhanced Header -->
      <div class="mb-8 text-center lg:text-left">
        <div
          class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6"
        >
          <div>
            <h1
              class="text-4xl lg:text-5xl font-bold text-text-900 mb-3 tracking-tight"
            >
              Admin Dashboard
            </h1>
            <p class="text-lg text-text-600 max-w-2xl">
              Manage users, monitor system activity, and view comprehensive
              statistics
            </p>
          </div>

          <!-- Quick Actions -->
          <div class="flex flex-wrap gap-3 justify-center lg:justify-end">
            <button
              @click="refreshData"
              :disabled="loading"
              class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/80 backdrop-blur-sm border border-background-200 rounded-xl text-text-700 font-medium hover:bg-white hover:border-primary-300 hover:text-primary-700 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
            >
              <Icon
                icon="lucide:refresh-cw"
                :class="['w-4 h-4', { 'animate-spin': loading }]"
              />
              Refresh
            </button>
            <button
              class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
            >
              <Icon icon="lucide:download" class="w-4 h-4" />
              Export Data
            </button>
          </div>
        </div>
      </div>

      <!-- Enhanced Stats Grid -->
      <div
        class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8"
      >
        <div
          v-for="(stat, index) in stats"
          :key="stat.label"
          class="bg-white/90 backdrop-blur-lg shadow-lg rounded-2xl border border-background-200/50 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-dashboard-card group cursor-pointer"
          :style="{ animationDelay: `${index * 100}ms` }"
        >
          <div class="flex items-center justify-between gap-4 mb-4">
            <div
              class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
              :class="getStatColorClass(stat.label)"
            >
              <Icon
                :icon="getStatIcon(stat.label)"
                class="w-6 h-6 text-white"
              />
            </div>
            <div class="text-right">
              <div
                class="text-3xl font-bold text-text-900 group-hover:text-primary-600 transition-colors duration-300"
              >
                {{ stat.value }}
              </div>
            </div>
          </div>
          <div
            class="text-sm font-semibold text-text-600 group-hover:text-text-700 transition-colors duration-300 mb-2"
          >
            {{ stat.label }}
          </div>
          <div v-if="stat.label === 'Completed Tasks'" class="w-full">
            <div
              class="w-full h-2 bg-background-200 rounded-full overflow-hidden"
            >
              <div
                class="h-2 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 transition-all duration-700"
                :style="{ width: adminStats.completionRate + '%' }"
              ></div>
            </div>
            <div class="text-xs text-text-500 mt-2 text-right font-medium">
              {{ adminStats.completionRate }}% completion rate
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Users Management -->
      <div
        class="bg-white/90 backdrop-blur-lg shadow-lg rounded-2xl border border-background-200/50 mb-8 animate-dashboard-card overflow-hidden"
      >
        <div
          class="px-6 py-6 border-b border-background-100/60 bg-gradient-to-r from-background-50/30 to-primary-50/20"
        >
          <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
          >
            <div>
              <h3 class="text-2xl font-bold text-text-900 mb-2">
                User Management
              </h3>
              <div class="flex items-center gap-4 text-sm text-text-600">
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:users" class="w-4 h-4 text-primary-600" />
                  <span class="font-medium"
                    >{{ users.length }} total users</span
                  >
                </div>
                <div class="flex items-center gap-2">
                  <Icon icon="lucide:crown" class="w-4 h-4 text-yellow-500" />
                  <span class="font-medium"
                    >{{ users.filter((u) => u.is_admin).length }} admins</span
                  >
                </div>
              </div>
            </div>

            <!-- Search and Filter Controls -->
            <div class="flex flex-col sm:flex-row gap-3">
              <div class="relative">
                <Icon
                  icon="lucide:search"
                  class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-text-400"
                />
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search users..."
                  class="pl-10 pr-4 py-2.5 border border-background-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white focus:bg-white min-w-[200px]"
                />
              </div>
              <select
                v-model="roleFilter"
                class="px-4 py-2.5 border border-background-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 bg-white/80 hover:bg-white focus:bg-white"
              >
                <option value="">All Roles</option>
                <option value="admin">Admins</option>
                <option value="user">Users</option>
              </select>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div
            v-if="loading"
            class="flex flex-col items-center justify-center py-16"
          >
            <div class="relative">
              <div
                class="w-12 h-12 border-3 border-background-200 border-t-primary-500 rounded-full animate-spin"
              ></div>
              <div
                class="absolute inset-0 w-12 h-12 border-3 border-transparent border-t-accent-400 rounded-full animate-spin animation-delay-150"
              ></div>
            </div>
            <p class="mt-4 text-text-600 font-medium">Loading users...</p>
          </div>

          <div v-else-if="filteredUsers.length === 0" class="text-center py-16">
            <div
              class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-primary-50 to-accent-50 flex items-center justify-center"
            >
              <Icon icon="lucide:users" class="w-10 h-10 text-primary-400" />
            </div>
            <h3 class="text-xl font-semibold text-text-900 mb-3">
              {{
                searchQuery || roleFilter ? "No users found" : "No users yet"
              }}
            </h3>
            <p class="text-text-600 mb-6">
              {{
                searchQuery || roleFilter
                  ? "Try adjusting your search or filter criteria."
                  : "Users will appear here once they register."
              }}
            </p>
            <button
              v-if="searchQuery || roleFilter"
              @click="clearFilters"
              class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200"
            >
              <Icon icon="lucide:refresh-cw" class="w-5 h-5" />
              Clear Filters
            </button>
          </div>

          <div v-else>
            <!-- Desktop Table View -->
            <div
              class="hidden lg:block overflow-hidden shadow-sm ring-1 ring-background-200/60 rounded-xl"
            >
              <table class="min-w-full divide-y divide-background-200">
                <thead
                  class="bg-gradient-to-r from-background-50 to-primary-50/30"
                >
                  <tr>
                    <th
                      class="px-6 py-4 text-left text-xs font-semibold text-text-500 uppercase tracking-wider"
                    >
                      User
                    </th>
                    <th
                      class="px-6 py-4 text-left text-xs font-semibold text-text-500 uppercase tracking-wider"
                    >
                      Role
                    </th>
                    <th
                      class="px-6 py-4 text-left text-xs font-semibold text-text-500 uppercase tracking-wider"
                    >
                      Tasks
                    </th>
                    <th
                      class="px-6 py-4 text-left text-xs font-semibold text-text-500 uppercase tracking-wider"
                    >
                      Joined
                    </th>
                    <th
                      class="px-6 py-4 text-left text-xs font-semibold text-text-500 uppercase tracking-wider"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white/50 divide-y divide-background-200/50">
                  <tr
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="hover:bg-white/80 transition-all duration-200 group"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-11 w-11">
                          <div
                            class="h-11 w-11 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-200"
                          >
                            <span class="text-sm font-semibold text-white">{{
                              user.name.charAt(0).toUpperCase()
                            }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div
                            class="text-sm font-semibold text-text-900 group-hover:text-primary-700 transition-colors duration-200"
                          >
                            {{ user.name }}
                          </div>
                          <div class="text-sm text-text-500">
                            {{ user.email }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                          'inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full transition-all duration-200',
                          user.is_admin
                            ? 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200'
                            : 'bg-gradient-to-r from-background-100 to-gray-100 text-text-700 border border-background-200',
                        ]"
                      >
                        <Icon
                          :icon="user.is_admin ? 'lucide:crown' : 'lucide:user'"
                          class="w-3 h-3"
                        />
                        {{ user.is_admin ? "Admin" : "User" }}
                      </span>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-text-900"
                    >
                      <div class="flex items-center gap-2">
                        <Icon
                          icon="lucide:list-checks"
                          class="w-4 h-4 text-text-400"
                        />
                        <span class="font-medium">{{
                          user.tasks_count || 0
                        }}</span>
                        <span class="text-text-500">tasks</span>
                      </div>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-text-500 font-medium"
                    >
                      {{ formatDate(user.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex items-center gap-2">
                        <button
                          v-if="
                            !user.is_admin && user.id !== authStore.user?.id
                          "
                          @click="toggleAdminStatus(user)"
                          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 hover:border-yellow-300 transition-all duration-200"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon icon="lucide:crown" class="w-3 h-3" />
                          Make Admin
                        </button>
                        <button
                          v-else-if="
                            user.is_admin && user.id !== authStore.user?.id
                          "
                          @click="toggleAdminStatus(user)"
                          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-700 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 hover:border-orange-300 transition-all duration-200"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon icon="lucide:crown" class="w-3 h-3" />
                          Remove Admin
                        </button>
                        <button
                          v-if="user.id !== authStore.user?.id"
                          @click="deleteUser(user)"
                          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon icon="lucide:trash-2" class="w-3 h-3" />
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden w-full flex flex-col gap-4">
              <div
                v-for="user in filteredUsers"
                :key="user.id"
                class="bg-white/80 backdrop-blur-sm border border-background-200 rounded-xl p-4 hover:bg-white hover:shadow-md transition-all duration-200"
              >
                <div class="flex items-center gap-3 mb-4">
                  <div
                    class="h-12 w-12 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center shadow-md"
                  >
                    <span class="text-sm font-semibold text-white">{{
                      user.name.charAt(0).toUpperCase()
                    }}</span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-text-900 truncate">
                      {{ user.name }}
                    </h4>
                    <p class="text-sm text-text-500 truncate">
                      {{ user.email }}
                    </p>
                  </div>
                  <span
                    :class="[
                      'inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full',
                      user.is_admin
                        ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-background-100 text-text-700',
                    ]"
                  >
                    <Icon
                      :icon="user.is_admin ? 'lucide:crown' : 'lucide:user'"
                      class="w-3 h-3"
                    />
                    {{ user.is_admin ? "Admin" : "User" }}
                  </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                  <div class="flex items-center gap-2">
                    <Icon
                      icon="lucide:list-checks"
                      class="w-4 h-4 text-text-400"
                    />
                    <span class="text-text-600"
                      >{{ user.tasks_count || 0 }} tasks</span
                    >
                  </div>
                  <div class="flex items-center gap-2">
                    <Icon
                      icon="lucide:calendar"
                      class="w-4 h-4 text-text-400"
                    />
                    <span class="text-text-600">{{
                      formatDate(user.created_at)
                    }}</span>
                  </div>
                </div>

                <div
                  v-if="user.id !== authStore.user?.id"
                  class="flex flex-wrap gap-2"
                >
                  <button
                    v-if="!user.is_admin"
                    @click="toggleAdminStatus(user)"
                    class="flex-1 min-w-0 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors duration-200"
                    :disabled="updatingUsers.includes(user.id)"
                  >
                    <Icon icon="lucide:crown" class="w-3 h-3" />
                    Make Admin
                  </button>
                  <button
                    v-else
                    @click="toggleAdminStatus(user)"
                    class="flex-1 min-w-0 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-orange-700 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors duration-200"
                    :disabled="updatingUsers.includes(user.id)"
                  >
                    <Icon icon="lucide:crown" class="w-3 h-3" />
                    Remove Admin
                  </button>
                  <button
                    @click="deleteUser(user)"
                    class="flex-1 min-w-0 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors duration-200"
                    :disabled="updatingUsers.includes(user.id)"
                  >
                    <Icon icon="lucide:trash-2" class="w-3 h-3" />
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Recent Activity -->
      <div
        class="bg-white/90 backdrop-blur-lg shadow-lg rounded-2xl border border-background-200/50 animate-dashboard-card overflow-hidden"
      >
        <div
          class="px-6 py-6 border-b border-background-100/60 bg-gradient-to-r from-background-50/30 to-accent-50/20"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-2xl font-bold text-text-900 mb-2">
                Recent Activity
              </h3>
              <div class="flex items-center gap-2 text-sm text-text-600">
                <Icon icon="lucide:activity" class="w-4 h-4 text-accent-600" />
                <span class="font-medium">Latest system activities</span>
              </div>
            </div>
            <Icon icon="lucide:activity" class="w-8 h-8 text-accent-600/60" />
          </div>
        </div>

        <div class="p-6">
          <div v-if="recentActivity.length === 0" class="text-center py-16">
            <div
              class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-accent-50 to-primary-50 flex items-center justify-center"
            >
              <Icon icon="lucide:activity" class="w-10 h-10 text-accent-400" />
            </div>
            <h3 class="text-xl font-semibold text-text-900 mb-3">
              No activity yet
            </h3>
            <p class="text-text-600">
              System activities will appear here as users interact with the
              platform.
            </p>
          </div>

          <div v-else class="flow-root">
            <ul class="-mb-8">
              <li
                v-for="(activity, index) in recentActivity"
                :key="activity.id"
                class="group"
              >
                <div class="relative pb-8">
                  <span
                    v-if="index !== recentActivity.length - 1"
                    class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gradient-to-b from-background-200 to-background-100"
                  ></span>
                  <div class="relative flex gap-4">
                    <div>
                      <span
                        :class="[
                          'h-10 w-10 rounded-full flex items-center justify-center ring-4 ring-white shadow-md group-hover:shadow-lg transition-all duration-200',
                          getActivityColor(activity.type),
                        ]"
                      >
                        <Icon
                          :icon="getActivityIcon(activity.type)"
                          class="h-5 w-5 text-white"
                        />
                      </span>
                    </div>
                    <div
                      class="min-w-0 flex-1 bg-white/60 backdrop-blur-sm border border-background-200/60 rounded-xl p-4 group-hover:bg-white group-hover:border-background-300/80 group-hover:shadow-md transition-all duration-200"
                    >
                      <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                          <p
                            class="text-sm font-semibold text-text-900 group-hover:text-primary-700 transition-colors duration-200"
                          >
                            {{ activity.description }}
                          </p>
                          <div
                            class="mt-2 flex items-center gap-4 text-sm text-text-600"
                          >
                            <div class="flex items-center gap-1.5">
                              <Icon icon="lucide:user" class="w-3.5 h-3.5" />
                              <span>{{ activity.user?.name || "System" }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                              <span
                                :class="[
                                  'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full',
                                  getActivityTypeColor(activity.type),
                                ]"
                              >
                                {{
                                  activity.type.replace("_", " ").toUpperCase()
                                }}
                              </span>
                            </div>
                          </div>
                        </div>
                        <div
                          class="text-right text-sm whitespace-nowrap text-text-500 font-medium"
                        >
                          {{ formatDate(activity.created_at) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Delete User Confirmation Modal -->
      <ConfirmDialog
        v-if="showDeleteModal"
        title="Delete User"
        message="Are you sure you want to delete this user? This action cannot be undone and will also delete all their tasks."
        @confirm="handleDeleteConfirm"
        @cancel="showDeleteModal = false"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import { useAuthStore } from "../stores/auth";
import { Icon } from "@iconify/vue";
import ConfirmDialog from "../components/ConfirmDialog.vue";
import AdminService from "../services/AdminService";

const authStore = useAuthStore();
const loading = ref(false);
const loadingActivities = ref(false);
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const updatingUsers = ref([]);
const searchQuery = ref("");
const roleFilter = ref("");
const showAllActivities = ref(false);

const adminStats = reactive({
  totalUsers: 0,
  totalTasks: 0,
  pendingTasks: 0,
  completedTasks: 0,
  completionRate: 0,
});

const users = ref([]);
const recentActivity = ref([]);
const activities = ref([]);

// Computed for filtered users
const filteredUsers = computed(() => {
  let filtered = users.value;

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(
      (user) =>
        user.name.toLowerCase().includes(query) ||
        user.email.toLowerCase().includes(query)
    );
  }

  // Apply role filter
  if (roleFilter.value) {
    if (roleFilter.value === "admin") {
      filtered = filtered.filter((user) => user.is_admin);
    } else if (roleFilter.value === "user") {
      filtered = filtered.filter((user) => !user.is_admin);
    }
  }

  return filtered;
});

// Clear filters method
const clearFilters = () => {
  searchQuery.value = "";
  roleFilter.value = "";
};

// Computed stats array for the template
const stats = computed(() => [
  {
    label: "Total Users",
    value: adminStats.totalUsers,
  },
  {
    label: "Total Tasks",
    value: adminStats.totalTasks,
  },
  {
    label: "Pending Tasks",
    value: adminStats.pendingTasks,
  },
  {
    label: "Completed Tasks",
    value: adminStats.completedTasks,
  },
]);

// Icon mapping for stats
const getStatIcon = (label) => {
  switch (label) {
    case "Total Users":
      return "lucide:users";
    case "Total Tasks":
      return "lucide:list-checks";
    case "Pending Tasks":
      return "lucide:clock";
    case "Completed Tasks":
      return "lucide:check-circle";
    default:
      return "lucide:layout-grid";
  }
};

// Color mapping for stats
const getStatColorClass = (label) => {
  switch (label) {
    case "Total Users":
      return "bg-gradient-to-r from-blue-500 to-blue-600";
    case "Total Tasks":
      return "bg-gradient-to-r from-primary-500 to-primary-600";
    case "Pending Tasks":
      return "bg-gradient-to-r from-yellow-500 to-amber-600";
    case "Completed Tasks":
      return "bg-gradient-to-r from-green-500 to-emerald-600";
    default:
      return "bg-gradient-to-r from-text-400 to-text-500";
  }
};

// Activity icon mapping
const getActivityIcon = (type) => {
  switch (type) {
    case "user_created":
      return "lucide:user-plus";
    case "task_created":
      return "lucide:plus-circle";
    case "task_completed":
      return "lucide:check-circle";
    default:
      return "lucide:activity";
  }
};

const toggleAdminStatus = async (user) => {
  if (updatingUsers.value.includes(user.id)) return;

  updatingUsers.value.push(user.id);
  try {
    await AdminService.toggleAdminStatus(user.id);

    // Update local user data
    const userIndex = users.value.findIndex((u) => u.id === user.id);
    if (userIndex !== -1) {
      users.value[userIndex].is_admin = !users.value[userIndex].is_admin;
    }
  } catch (error) {
    console.error("Failed to toggle admin status:", error);
    // You could add a toast notification here
  } finally {
    updatingUsers.value = updatingUsers.value.filter((id) => id !== user.id);
  }
};

const deleteUser = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const handleDeleteConfirm = async () => {
  if (!userToDelete.value) return;

  if (updatingUsers.value.includes(userToDelete.value.id)) return;

  updatingUsers.value.push(userToDelete.value.id);
  try {
    await AdminService.deleteUser(userToDelete.value.id);

    // Remove user from local data
    users.value = users.value.filter((u) => u.id !== userToDelete.value.id);

    // Update stats
    adminStats.totalUsers = Math.max(0, adminStats.totalUsers - 1);

    showDeleteModal.value = false;
    userToDelete.value = null;
  } catch (error) {
    console.error("Failed to delete user:", error);
    // You could add a toast notification here
  } finally {
    updatingUsers.value = updatingUsers.value.filter(
      (id) => id !== userToDelete.value?.id
    );
  }
};

const getActivityColor = (type) => {
  switch (type) {
    case "user_created":
    case "user_registered":
      return "bg-gradient-to-r from-green-500 to-emerald-600";
    case "task_created":
    case "task_added":
      return "bg-gradient-to-r from-blue-500 to-blue-600";
    case "task_completed":
    case "task_finished":
      return "bg-gradient-to-r from-primary-500 to-primary-600";
    case "user_login":
      return "bg-gradient-to-r from-purple-500 to-purple-600";
    case "admin_action":
      return "bg-gradient-to-r from-yellow-500 to-amber-600";
    default:
      return "bg-gradient-to-r from-text-400 to-text-500";
  }
};

const getActivityTypeColor = (type) => {
  switch (type) {
    case "user_created":
    case "user_registered":
      return "bg-green-100 text-green-800";
    case "task_created":
    case "task_added":
      return "bg-blue-100 text-blue-800";
    case "task_completed":
    case "task_finished":
      return "bg-primary-100 text-primary-800";
    case "user_login":
      return "bg-purple-100 text-purple-800";
    case "admin_action":
      return "bg-yellow-100 text-yellow-800";
    default:
      return "bg-gray-100 text-gray-800";
  }
};

const formatActivityTime = (date) => {
  const now = new Date();
  const activityDate = new Date(date);
  const diffInMinutes = Math.floor((now - activityDate) / (1000 * 60));

  if (diffInMinutes < 1) return "Just now";
  if (diffInMinutes < 60) return `${diffInMinutes}m ago`;

  const diffInHours = Math.floor(diffInMinutes / 60);
  if (diffInHours < 24) return `${diffInHours}h ago`;

  const diffInDays = Math.floor(diffInHours / 24);
  if (diffInDays < 7) return `${diffInDays}d ago`;

  return formatDate(date);
};

const loadActivities = async () => {
  loadingActivities.value = true;
  try {
    const activityResponse = await AdminService.getRecentActivity();
    activities.value = activityResponse.activity || [];
    recentActivity.value = activityResponse.activity || [];
  } catch (error) {
    console.error("Failed to load activities:", error);
  } finally {
    loadingActivities.value = false;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const fetchAdminData = async () => {
  loading.value = true;
  try {
    // Fetch dashboard stats
    const statsResponse = await AdminService.getDashboardStats();
    Object.assign(adminStats, statsResponse.stats);

    // Fetch users
    const usersResponse = await AdminService.getUsers();
    users.value = usersResponse.users;

    // Fetch recent activity
    const activityResponse = await AdminService.getRecentActivity();
    recentActivity.value = activityResponse.activity || [];
    activities.value = activityResponse.activity || [];
  } catch (error) {
    console.error("Failed to fetch admin data:", error);
    // You could add error handling UI here
  } finally {
    loading.value = false;
  }
};

// Refresh data method for the refresh button
const refreshData = () => {
  fetchAdminData();
};

onMounted(() => {
  fetchAdminData();
});
</script>

<style scoped>
.animate-dashboard-card {
  animation: dashboard-card-fadein 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes dashboard-card-fadein {
  0% {
    opacity: 0;
    transform: translateY(30px) scale(0.98);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.animation-delay-150 {
  animation-delay: 150ms;
}

.animation-delay-300 {
  animation-delay: 300ms;
}

.animation-delay-450 {
  animation-delay: 450ms;
}

.border-3 {
  border-width: 3px;
}

/* Custom glassmorphism effect */
.backdrop-blur-lg {
  backdrop-filter: blur(16px);
}

.backdrop-blur-sm {
  backdrop-filter: blur(4px);
}

/* Enhanced hover transitions */
.group:hover .group-hover\:shadow-lg {
  --tw-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -2px rgba(0, 0, 0, 0.05);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
    var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
}

/* Improved loading spinner */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Mobile responsiveness helpers */
@media (max-width: 640px) {
  .min-w-0 {
    min-width: 0;
    flex: 1 1 0%;
  }
}
</style>
