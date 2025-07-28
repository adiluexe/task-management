<template>
  <div 
    :data-task-id="task.id"
    :class="[
      'task-card',
      viewMode === 'grid' ? 'task-card-grid' : 'task-card-list',
      task.status === 'completed' ? 'task-completed' : '',
      `priority-${task.priority}`
    ]"
  >
    <!-- List View Layout -->
    <div v-if="viewMode === 'list'" class="flex items-center justify-between p-8 hover:bg-background-50/80 transition-all duration-200 group border-l-4" :class="getPriorityBorder(task.priority)">
      <!-- Left Section -->
      <div class="flex items-center space-x-6 flex-1">
        <!-- Status Checkbox -->
        <button
          @click="$emit('toggle-status', task)"
          class="task-checkbox"
          :class="{ 'completed': task.status === 'completed' }"
        >
          <div class="checkbox-inner">
            <svg
              v-if="task.status === 'completed'"
              class="w-4 h-4 text-white"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </button>
        
        <!-- Task Content -->
        <div class="flex-1 min-w-0 space-y-3">
          <div class="flex items-center space-x-4 mb-3">
            <h3 :class="[
              'text-xl font-bold truncate',
              task.status === 'completed' ? 'line-through text-text-400' : 'text-text-900'
            ]">
              {{ task.title }}
            </h3>
            
            <!-- Priority Badge -->
            <span :class="[
              'priority-badge',
              `priority-${task.priority}`
            ]">
              {{ getPriorityIcon(task.priority) }} {{ task.priority }}
            </span>
          </div>
          
          <p :class="[
            'text-base mb-4 line-clamp-2 leading-relaxed',
            task.status === 'completed' ? 'line-through text-text-300' : 'text-text-600'
          ]">
            {{ task.description }}
          </p>
          
          <div class="flex items-center space-x-6 text-sm text-text-500">
            <span class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Created {{ formatDate(task.created_at) }}
            </span>
            <span v-if="task.updated_at !== task.created_at" class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Updated {{ formatDate(task.updated_at) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-all duration-200">
        <button
          @click="$emit('edit', task)"
          class="action-btn action-btn-edit"
          title="Edit task"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </button>
        <button
          @click="$emit('delete', task)"
          class="action-btn action-btn-delete"
          title="Delete task"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
        
        <!-- Drag Handle -->
        <div class="drag-handle cursor-move text-text-300 hover:text-text-500">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Grid View Layout -->
    <div v-else class="task-card-grid-content">
      <!-- Header -->
      <div class="task-card-header">
        <div class="flex items-center justify-between">
          <button
            @click="$emit('toggle-status', task)"
            class="task-checkbox"
            :class="{ 'completed': task.status === 'completed' }"
          >
            <div class="checkbox-inner">
              <svg
                v-if="task.status === 'completed'"
                class="w-3 h-3 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </button>
          
          <span :class="[
            'priority-badge-small',
            `priority-${task.priority}`
          ]">
            {{ getPriorityIcon(task.priority) }}
          </span>
        </div>
      </div>

      <!-- Content -->
      <div class="task-card-body">
        <h3 :class="[
          'text-lg font-semibold mb-2 line-clamp-2',
          task.status === 'completed' ? 'line-through text-text-400' : 'text-text-900'
        ]">
          {{ task.title }}
        </h3>
        
        <p :class="[
          'text-sm mb-4 line-clamp-3',
          task.status === 'completed' ? 'line-through text-text-300' : 'text-text-600'
        ]">
          {{ task.description }}
        </p>
      </div>

      <!-- Footer -->
      <div class="task-card-footer">
        <div class="text-xs text-text-500 mb-3">
          Created {{ formatDate(task.created_at) }}
        </div>
        
        <div class="flex items-center justify-between">
          <span :class="[
            'priority-badge-small',
            `priority-${task.priority}`
          ]">
            {{ task.priority }}
          </span>
          
          <div class="flex items-center space-x-2">
            <button
              @click="$emit('edit', task)"
              class="action-btn action-btn-edit"
              title="Edit task"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button
              @click="$emit('delete', task)"
              class="action-btn action-btn-delete"
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
/* Base Task Card */
.task-card {
  background-color: white;
  border-radius: 0.5rem;
  border: 1px solid rgb(229 231 235);
  transition: all 0.2s ease;
  cursor: pointer;
}

.task-card:hover {
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
  border-color: rgb(187 247 208);
  transform: translateY(-1px);
}

.task-card-list {
  border-bottom: 1px solid rgb(243 244 246);
  border-radius: 0;
}

.task-card-list:last-child {
  border-bottom: none;
}

.task-card-grid {
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
  min-height: 280px;
}

.task-card-grid-content {
  padding: 1.5rem;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.task-card-header {
  margin-bottom: 1rem;
}

.task-card-body {
  flex: 1;
}

.task-card-footer {
  margin-top: auto;
}

/* Task Checkbox */
.task-checkbox {
  width: 1.5rem;
  height: 1.5rem;
  border-radius: 50%;
  border: 2px solid rgb(209 213 219);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.task-checkbox:hover {
  border-color: rgb(34 197 94);
  transform: scale(1.1);
}

.task-checkbox.completed {
  background-color: rgb(34 197 94);
  border-color: rgb(34 197 94);
}

.checkbox-inner {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Priority Badges */
.priority-badge {
  padding: 0.25rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 500;
  border-radius: 9999px;
  transition: all 0.2s ease;
}

.priority-badge-small {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
  border-radius: 9999px;
}

.priority-low {
  background-color: rgb(220 252 231);
  color: rgb(22 101 52);
}

.priority-medium {
  background-color: rgb(254 249 195);
  color: rgb(133 77 14);
}

.priority-high {
  background-color: rgb(254 226 226);
  color: rgb(153 27 27);
  animation: priority-pulse 2s ease-in-out infinite;
}

/* Action Buttons */
.action-btn {
  padding: 0.5rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.action-btn-edit {
  color: rgb(156 163 175);
}

.action-btn-edit:hover {
  color: rgb(34 197 94);
  background-color: rgb(240 253 244);
}

.action-btn-delete {
  color: rgb(156 163 175);
}

.action-btn-delete:hover {
  color: rgb(220 38 38);
  background-color: rgb(254 242 242);
}

.action-btn:hover {
  transform: scale(1.1);
}

/* Task Completed State */
.task-completed {
  opacity: 0.75;
}

.task-completed:hover {
  opacity: 0.9;
}

/* Drag Handle */
.drag-handle {
  transition: all 0.2s ease;
}

.drag-handle:hover {
  transform: scale(1.2);
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

/* Priority Pulse Animation */
@keyframes priority-pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .task-card-grid {
    min-height: 240px;
  }
  
  .task-card-grid-content {
    padding: 1rem;
  }
}
</style>
