<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Page header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Manage users and system-wide tasks.</p>
      </div>

      <!-- Admin Stats -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ adminStats.totalUsers }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ adminStats.totalTasks }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Pending Tasks</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ adminStats.pendingTasks }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Completed Tasks</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ adminStats.completedTasks }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Management -->
      <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">User Management</h3>
          
          <div v-if="loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          </div>
          
          <div v-else>
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="user in users" :key="user.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">{{ user.name.charAt(0).toUpperCase() }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                          <div class="text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="[
                        'px-2 py-1 text-xs font-medium rounded-full',
                        user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'
                      ]">
                        {{ user.is_admin ? 'Admin' : 'User' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ user.tasks_count || 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(user.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button
                        v-if="!user.is_admin"
                        @click="toggleAdminStatus(user)"
                        class="text-primary-600 hover:text-primary-900 mr-3"
                      >
                        Make Admin
                      </button>
                      <button
                        v-else-if="user.id !== authStore.user?.id"
                        @click="toggleAdminStatus(user)"
                        class="text-yellow-600 hover:text-yellow-900 mr-3"
                      >
                        Remove Admin
                      </button>
                      <button
                        v-if="user.id !== authStore.user?.id"
                        @click="deleteUser(user)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
          
          <div class="flow-root">
            <ul class="-mb-8">
              <li v-for="(activity, index) in recentActivity" :key="activity.id">
                <div class="relative pb-8">
                  <span v-if="index !== recentActivity.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                  <div class="relative flex space-x-3">
                    <div>
                      <span :class="[
                        'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white',
                        getActivityColor(activity.type)
                      ]">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                      </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                      <div>
                        <p class="text-sm text-gray-500">{{ activity.description }}</p>
                      </div>
                      <div class="text-right text-sm whitespace-nowrap text-gray-500">
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
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import ConfirmDialog from '../components/ConfirmDialog.vue'

const authStore = useAuthStore()
const loading = ref(false)
const showDeleteModal = ref(false)
const userToDelete = ref(null)

const adminStats = reactive({
  totalUsers: 0,
  totalTasks: 0,
  pendingTasks: 0,
  completedTasks: 0
})

const users = ref([])
const recentActivity = ref([])

const toggleAdminStatus = async (user) => {
  try {
    // TODO: Implement admin status toggle API call
    console.log('Toggle admin status for user:', user.id)
  } catch (error) {
    console.error('Failed to toggle admin status:', error)
  }
}

const deleteUser = (user) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const handleDeleteConfirm = async () => {
  try {
    // TODO: Implement delete user API call
    console.log('Delete user:', userToDelete.value.id)
    showDeleteModal.value = false
    userToDelete.value = null
  } catch (error) {
    console.error('Failed to delete user:', error)
  }
}

const getActivityColor = (type) => {
  switch (type) {
    case 'user_created':
      return 'bg-green-500'
    case 'task_created':
      return 'bg-blue-500'
    case 'task_completed':
      return 'bg-purple-500'
    default:
      return 'bg-gray-500'
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const fetchAdminData = async () => {
  loading.value = true
  try {
    // TODO: Implement API calls to fetch admin data
    // Mock data for now
    adminStats.totalUsers = 15
    adminStats.totalTasks = 127
    adminStats.pendingTasks = 34
    adminStats.completedTasks = 93

    users.value = [
      {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        is_admin: false,
        tasks_count: 12,
        created_at: '2024-01-15T10:00:00Z'
      },
      {
        id: 2,
        name: 'Jane Smith',
        email: 'jane@example.com',
        is_admin: true,
        tasks_count: 8,
        created_at: '2024-01-10T14:30:00Z'
      }
    ]

    recentActivity.value = [
      {
        id: 1,
        type: 'user_created',
        description: 'New user John Doe registered',
        created_at: '2024-01-20T09:00:00Z'
      },
      {
        id: 2,
        type: 'task_completed',
        description: 'Task "Setup project" completed by Jane Smith',
        created_at: '2024-01-19T16:45:00Z'
      }
    ]
  } catch (error) {
    console.error('Failed to fetch admin data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchAdminData()
})
</script>
