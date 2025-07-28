<template>
  <div 
    :data-task-id="task.id"
    :class="[
      'task-card group border border-background-100 rounded-lg hover:border-background-200 hover:shadow-sm transition-all duration-200',
      viewMode === 'grid' ? 'p-4' : 'p-0',
      task.status === 'completed' ? 'opacity-75' : '',
      `priority-${task.priority}`
    ]"
  >
    <!-- List View Layout -->
    <div v-if="viewMode === 'list'" class="flex items-center justify-between p-4 border-l-2" :class="getPriorityBorder(task.priority)">
      <!-- Left Section -->
      <div class="flex items-center space-x-4 flex-1 min-w-0">
        <!-- Status Checkbox -->
        <button
          @click="$emit('toggle-status', task)"
          :class="[
            'flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-all duration-200',
            task.status === 'completed' 
              ? 'bg-primary-500 border-primary-500 text-white' 
              : 'border-background-300 hover:border-primary-400'
          ]"
        >
          <svg
            v-if="task.status === 'completed'"
            class="w-3 h-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </button>
        
        <!-- Task Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center space-x-3 mb-1">
            <h3 :class="[
              'font-medium truncate',
              task.status === 'completed' ? 'line-through text-text-500' : 'text-text-900'
            ]">
              {{ task.title }}
            </h3>
            
            <!-- Priority Badge -->
            <span :class="[
              'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-md',
              task.priority === 'high' ? 'bg-red-50 text-red-600' :
              task.priority === 'medium' ? 'bg-yellow-50 text-yellow-600' :
              'bg-green-50 text-green-600'
            ]">
              {{ task.priority }}
            </span>
          </div>
          
          <p :class="[
            'text-sm truncate',
            task.status === 'completed' ? 'line-through text-text-400' : 'text-text-600'
          ]">
            {{ task.description }}
          </p>
          
          <div class="flex items-center space-x-4 mt-2 text-xs text-text-500">
            <span>Created {{ formatDate(task.created_at) }}</span>
            <span v-if="task.updated_at !== task.created_at">
              Updated {{ formatDate(task.updated_at) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <button
          @click="$emit('edit', task)"
          class="p-2 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors"
          title="Edit task"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </button>
        <button
          @click="$emit('delete', task)"
          class="p-2 text-text-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
          title="Delete task"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
        
        <!-- Drag Handle -->
        <div class="p-2 cursor-move text-text-300 hover:text-text-500">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Grid View Layout -->
    <div v-else class="space-y-3">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <button
          @click="$emit('toggle-status', task)"
          :class="[
            'flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-all duration-200',
            task.status === 'completed' 
              ? 'bg-primary-500 border-primary-500 text-white' 
              : 'border-background-300 hover:border-primary-400'
          ]"
        >
          <svg
            v-if="task.status === 'completed'"
            class="w-3 h-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </button>
        
        <span :class="[
          'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-md',
          task.priority === 'high' ? 'bg-red-50 text-red-600' :
          task.priority === 'medium' ? 'bg-yellow-50 text-yellow-600' :
          'bg-green-50 text-green-600'
        ]">
          {{ task.priority }}
        </span>
      </div>

      <!-- Content -->
      <div>
        <h3 :class="[
          'font-medium mb-2 line-clamp-2',
          task.status === 'completed' ? 'line-through text-text-500' : 'text-text-900'
        ]">
          {{ task.title }}
        </h3>
        
        <p :class="[
          'text-sm line-clamp-3',
          task.status === 'completed' ? 'line-through text-text-400' : 'text-text-600'
        ]">
          {{ task.description }}
        </p>
      </div>

      <!-- Footer -->
      <div class="pt-2 border-t border-background-100">
        <div class="text-xs text-text-500 mb-2">
          Created {{ formatDate(task.created_at) }}
        </div>
        
        <div class="flex items-center justify-end space-x-1">
          <button
            @click="$emit('edit', task)"
            class="p-1.5 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded transition-colors"
            title="Edit task"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </button>
          <button
            @click="$emit('delete', task)"
            class="p-1.5 text-text-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
            title="Delete task"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

defineProps({
  task: {
    type: Object,
    required: true
  },
  viewMode: {
    type: String,
    default: 'list'
  },
  index: {
    type: Number,
    default: 0
  }
})

defineEmits(['toggle-status', 'edit', 'delete'])

const getPriorityIcon = (priority) => {
  const icons = {
    low: '🟢',
    medium: '🟡',
    high: '🔴'
  }
  return icons[priority] || '⚪'
}

const getPriorityBorder = (priority) => {
  const borders = {
    low: 'border-green-400',
    medium: 'border-yellow-400', 
    high: 'border-red-400'
  }
  return borders[priority] || 'border-gray-300'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>

<style scoped>
/* Priority Border */
.priority-low {
  border-left-color: rgb(34 197 94);
}

.priority-medium {
  border-left-color: rgb(245 158 11);
}

.priority-high {
  border-left-color: rgb(239 68 68);
}

/* Line Clamp Utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
