<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-100 via-background-50 to-primary-50">
    <div class="container mx-auto px-6 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-900 mb-2">Admin Dashboard</h1>
        <p class="text-text-600">
          Manage users, monitor system activity, and view statistics
        </p>
      </div>

      <!-- Stats Grid -->
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
      >
        <div
          v-for="stat in stats"
          :key="stat.label"
          class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-background-200 p-6 hover:shadow-2xl transition-all duration-300 animate-dashboard-card"
        >
          <div class="flex items-center justify-between mb-4">
            <Icon
              :icon="getStatIcon(stat.label)"
              class="w-8 h-8 text-primary-600"
            />
          </div>
          <div class="text-3xl font-bold text-text-900 mb-2">
            {{ stat.value }}
          </div>
          <div class="text-sm font-medium text-text-600 mb-3">{{ stat.label }}</div>
          <div v-if="stat.label === 'Completed Tasks'" class="w-full">
            <div
              class="w-full h-2 bg-background-200 rounded-full overflow-hidden"
            >
              <div
                class="h-2 rounded-full bg-primary-500 transition-all duration-500"
                :style="{ width: adminStats.completionRate + '%' }"
              ></div>
            </div>
            <div class="text-xs text-text-500 mt-1 text-right">
              {{ adminStats.completionRate }}% completed
            </div>
          </div>
        </div>
      </div>

      <!-- Users Management -->
      <div
        class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-background-200 mb-8 animate-dashboard-card"
      >
        <div class="px-6 py-7">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-text-900">User Management</h3>
            <div class="flex items-center space-x-2">
              <Icon
                icon="solar:users-group-rounded-bold"
                class="w-5 h-5 text-primary-600"
              />
              <span class="text-sm text-text-600">{{ users.length }} users</span>
            </div>
          </div>
          
          <div v-if="loading" class="flex justify-center py-12">
            <div
              class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
            ></div>
          </div>
          
          <div v-else-if="users.length === 0" class="text-center py-12">
            <Icon
              icon="solar:users-group-rounded-bold"
              class="w-12 h-12 text-text-400 mx-auto mb-4"
            />
            <p class="text-text-500">No users found</p>
          </div>
          
          <div v-else>
            <div
              class="overflow-hidden shadow ring-1 ring-background-200 ring-opacity-75 md:rounded-lg"
            >
              <table class="min-w-full divide-y divide-background-300">
                <thead class="bg-background-100">
                  <tr>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-text-500 uppercase tracking-wider"
                    >
                      User
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-text-500 uppercase tracking-wider"
                    >
                      Role
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-text-500 uppercase tracking-wider"
                    >
                      Tasks
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-text-500 uppercase tracking-wider"
                    >
                      Joined
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-text-500 uppercase tracking-wider"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-background-50 divide-y divide-background-200">
                  <tr
                    v-for="user in users"
                    :key="user.id"
                    class="hover:bg-background-100 transition-colors"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div
                            class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center"
                          >
                            <span class="text-sm font-medium text-white">{{
                              user.name.charAt(0).toUpperCase()
                            }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-text-900">
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
                          'px-3 py-1 text-xs font-medium rounded-full flex items-center space-x-1 w-fit',
                          user.is_admin
                            ? 'bg-primary-100 text-primary-800'
                            : 'bg-background-100 text-text-800',
                        ]"
                      >
                        <Icon
                          :icon="user.is_admin ? 'solar:crown-bold' : 'solar:user-rounded-bold'"
                          class="w-3 h-3"
                        />
                        <span>{{ user.is_admin ? "Admin" : "User" }}</span>
                      </span>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-text-900"
                    >
                      <div class="flex items-center space-x-2">
                        <Icon
                          icon="solar:checklist-minimalistic-bold"
                          class="w-4 h-4 text-text-400"
                        />
                        <span>{{ user.tasks_count || 0 }}</span>
                      </div>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-text-500"
                    >
                      {{ formatDate(user.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex items-center space-x-3">
                        <button
                          v-if="!user.is_admin && user.id !== authStore.user?.id"
                          @click="toggleAdminStatus(user)"
                          class="text-primary-600 hover:text-primary-900 transition-colors flex items-center space-x-1"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon
                            icon="solar:crown-bold"
                            class="w-4 h-4"
                          />
                          <span>Make Admin</span>
                        </button>
                        <button
                          v-else-if="user.is_admin && user.id !== authStore.user?.id"
                          @click="toggleAdminStatus(user)"
                          class="text-yellow-600 hover:text-yellow-900 transition-colors flex items-center space-x-1"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon
                            icon="solar:crown-line-duotone"
                            class="w-4 h-4"
                          />
                          <span>Remove Admin</span>
                        </button>
                        <button
                          v-if="user.id !== authStore.user?.id"
                          @click="deleteUser(user)"
                          class="text-red-600 hover:text-red-900 transition-colors flex items-center space-x-1"
                          :disabled="updatingUsers.includes(user.id)"
                        >
                          <Icon
                            icon="solar:trash-bin-trash-bold"
                            class="w-4 h-4"
                          />
                          <span>Delete</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div
        class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-background-200 animate-dashboard-card"
      >
        <div class="px-6 py-7">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-text-900">Recent Activity</h3>
            <Icon
              icon="solar:history-bold"
              class="w-5 h-5 text-primary-600"
            />
          </div>
          
          <div v-if="recentActivity.length === 0" class="text-center py-8">
            <Icon
              icon="solar:history-bold"
              class="w-12 h-12 text-text-400 mx-auto mb-4"
            />
            <p class="text-text-500">No recent activity</p>
          </div>
          
          <div v-else class="flow-root">
            <ul class="-mb-8">
              <li
                v-for="(activity, index) in recentActivity"
                :key="activity.id"
              >
                <div class="relative pb-8">
                  <span
                    v-if="index !== recentActivity.length - 1"
                    class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-background-200"
                  ></span>
                  <div class="relative flex space-x-3">
                    <div>
                      <span
                        :class="[
                          'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white',
                          getActivityColor(activity.type),
                        ]"
                      >
                        <Icon
                          :icon="getActivityIcon(activity.type)"
                          class="h-4 w-4 text-white"
                        />
                      </span>
                    </div>
                    <div
                      class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4"
                    >
                      <div>
                        <p class="text-sm text-text-600">
                          {{ activity.description }}
                        </p>
                      </div>
                      <div
                        class="text-right text-sm whitespace-nowrap text-text-500"
                      >
                        {{ formatDate(activity.created_at) }}
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
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const updatingUsers = ref([]);

const adminStats = reactive({
  totalUsers: 0,
  totalTasks: 0,
  pendingTasks: 0,
  completedTasks: 0,
  completionRate: 0,
});

const users = ref([]);
const recentActivity = ref([]);

// Computed stats array for the template
const stats = computed(() => [
  {
    label: 'Total Users',
    value: adminStats.totalUsers,
  },
  {
    label: 'Total Tasks',
    value: adminStats.totalTasks,
  },
  {
    label: 'Pending Tasks',
    value: adminStats.pendingTasks,
  },
  {
    label: 'Completed Tasks',
    value: adminStats.completedTasks,
  },
]);

// Icon mapping for stats
const getStatIcon = (label) => {
  switch (label) {
    case 'Total Users':
      return 'solar:users-group-rounded-bold';
    case 'Total Tasks':
      return 'solar:checklist-minimalistic-bold';
    case 'Pending Tasks':
      return 'solar:clock-circle-bold';
    case 'Completed Tasks':
      return 'solar:check-circle-bold';
    default:
      return 'solar:widget-add-bold';
  }
};

// Activity icon mapping
const getActivityIcon = (type) => {
  switch (type) {
    case 'user_created':
      return 'solar:user-plus-bold';
    case 'task_created':
      return 'solar:add-circle-bold';
    case 'task_completed':
      return 'solar:check-circle-bold';
    default:
      return 'solar:info-circle-bold';
  }
};

const toggleAdminStatus = async (user) => {
  if (updatingUsers.value.includes(user.id)) return;
  
  updatingUsers.value.push(user.id);
  try {
    await AdminService.toggleAdminStatus(user.id);
    
    // Update local user data
    const userIndex = users.value.findIndex(u => u.id === user.id);
    if (userIndex !== -1) {
      users.value[userIndex].is_admin = !users.value[userIndex].is_admin;
    }
  } catch (error) {
    console.error("Failed to toggle admin status:", error);
    // You could add a toast notification here
  } finally {
    updatingUsers.value = updatingUsers.value.filter(id => id !== user.id);
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
    users.value = users.value.filter(u => u.id !== userToDelete.value.id);
    
    // Update stats
    adminStats.totalUsers = Math.max(0, adminStats.totalUsers - 1);
    
    showDeleteModal.value = false;
    userToDelete.value = null;
  } catch (error) {
    console.error("Failed to delete user:", error);
    // You could add a toast notification here
  } finally {
    updatingUsers.value = updatingUsers.value.filter(id => id !== userToDelete.value?.id);
  }
};

const getActivityColor = (type) => {
  switch (type) {
    case "user_created":
      return "bg-green-500";
    case "task_created":
      return "bg-blue-500";
    case "task_completed":
      return "bg-primary-500";
    default:
      return "bg-text-400";
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
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
    recentActivity.value = activityResponse.activity;
  } catch (error) {
    console.error("Failed to fetch admin data:", error);
    // You could add error handling UI here
  } finally {
    loading.value = false;
  }
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
</style>
