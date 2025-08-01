<template>
  <div
    :data-task-id="task.id"
    :class="[
      'task-card group bg-white/95 backdrop-blur-sm border border-background-200/60 rounded-xl hover:border-primary-200/60 hover:shadow-lg transition-all duration-300 overflow-hidden',
      viewMode === 'grid' ? 'p-6' : 'p-0',
      task.status === 'completed' ? 'opacity-80' : '',
      `priority-${task.priority}`,
    ]"
  >
    <!-- List View Layout -->
    <div
      v-if="viewMode === 'list'"
      class="flex items-center justify-between p-6 border-l-4 hover:bg-gradient-to-r hover:from-background-50/40 hover:to-transparent transition-all duration-200"
      :class="getPriorityBorder(task.priority)"
    >
      <!-- Left Section -->
      <div class="flex items-center space-x-6 flex-1 min-w-0 gap-2">
        <!-- Status Checkbox -->
        <button
          @click="$emit('toggle-status', task)"
          :class="[
            'flex-shrink-0 w-7 h-7 rounded-lg border-2 flex items-center justify-center transition-all duration-200 hover:scale-110',
            task.status === 'completed'
              ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 border-emerald-500 text-white shadow-md'
              : 'border-background-300 hover:border-primary-400 hover:bg-primary-50',
          ]"
        >
          <Icon
            v-if="task.status === 'completed'"
            icon="lucide:check"
            class="w-4 h-4"
          />
        </button>

        <!-- Task Content -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-3">
            <h3
              :class="[
                'font-semibold text-lg flex pr-4',
                task.status === 'completed'
                  ? 'line-through text-text-500'
                  : 'text-text-900 group-hover:text-primary-700',
              ]"
            >
              {{ task.title }}
            </h3>
            <!-- Status Group -->
            <div class="flex items-center gap-2">
              <div class="flex items-center">
                <!-- Status Badge -->
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full',
                    task.status === 'completed'
                      ? 'bg-emerald-50 text-emerald-700'
                      : task.status === 'in_progress'
                      ? 'bg-blue-50 text-blue-700'
                      : 'bg-gray-50 text-gray-700',
                  ]"
                >
                  {{
                    task.status === "in_progress"
                      ? "In Progress"
                      : task.status.charAt(0).toUpperCase() +
                        task.status.slice(1)
                  }}
                </span>
                <!-- Priority Badge -->
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full',
                    task.priority === 'high'
                      ? 'bg-red-50 text-red-700'
                      : task.priority === 'medium'
                      ? 'bg-yellow-50 text-yellow-700'
                      : 'bg-green-50 text-green-700',
                  ]"
                >
                  {{
                    task.priority.charAt(0).toUpperCase() +
                    task.priority.slice(1)
                  }}
                </span>

                <!-- Priority Dot -->
                <div
                  :class="[
                    'w-3 h-3 rounded-full',
                    task.priority === 'high'
                      ? 'bg-red-500'
                      : task.priority === 'medium'
                      ? 'bg-yellow-500'
                      : 'bg-green-500',
                  ]"
                ></div>
              </div>
            </div>
          </div>

          <p
            :class="[
              'text-sm leading-relaxed mb-4',
              task.status === 'completed'
                ? 'line-through text-text-400'
                : 'text-text-600',
            ]"
          >
            {{ task.description }}
          </p>

          <div class="flex items-center gap-2 text-xs text-text-500">
            <div class="flex items-center gap-1">
              <Icon icon="lucide:calendar" class="w-3 h-3" />
              <span>Created {{ formatDate(task.created_at) }}</span>
            </div>
            <div
              v-if="task.updated_at !== task.created_at"
              class="flex items-center gap-1"
            >
              <Icon icon="lucide:edit-3" class="w-3 h-3" />
              <span>Modified {{ formatDate(task.updated_at) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Action Buttons -->
      <div
        class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-200 transform translate-x-2 group-hover:translate-x-0"
      >
        <button
          @click="$emit('edit', task)"
          class="p-2.5 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 hover:scale-110"
          title="Edit task"
        >
          <Icon icon="lucide:edit-2" class="w-4 h-4" />
        </button>
        <button
          @click="$emit('delete', task)"
          class="p-2.5 text-text-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 hover:scale-110"
          title="Delete task"
        >
          <Icon icon="lucide:trash-2" class="w-4 h-4" />
        </button>

        <!-- Enhanced Drag Handle -->
        <div
          class="p-2.5 cursor-move text-text-300 hover:text-text-500 transition-all duration-200 hover:scale-110"
        >
          <Icon icon="lucide:grip-vertical" class="w-4 h-4" />
        </div>
      </div>
    </div>

    <!-- Enhanced Grid View Layout -->
    <div v-else class="space-y-4">
      <!-- Enhanced Header -->
      <div class="flex items-center justify-between">
        <button
          @click="$emit('toggle-status', task)"
          :class="[
            'flex-shrink-0 w-7 h-7 rounded-lg border-2 flex items-center justify-center transition-all duration-200 hover:scale-110',
            task.status === 'completed'
              ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 border-emerald-500 text-white shadow-md'
              : 'border-background-300 hover:border-primary-400 hover:bg-primary-50',
          ]"
        >
          <Icon
            v-if="task.status === 'completed'"
            icon="lucide:check"
            class="w-4 h-4"
          />
        </button>

        <div class="flex items-center space-x-2">
          <!-- Priority Dot -->
          <div
            :class="[
              'w-3 h-3 rounded-full',
              task.priority === 'high'
                ? 'bg-red-500'
                : task.priority === 'medium'
                ? 'bg-yellow-500'
                : 'bg-green-500',
            ]"
          ></div>
          <!-- Priority Badge -->
          <span
            :class="[
              'inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full',
              task.priority === 'high'
                ? 'bg-red-50 text-red-700'
                : task.priority === 'medium'
                ? 'bg-yellow-50 text-yellow-700'
                : 'bg-green-50 text-green-700',
            ]"
          >
            {{ task.priority.charAt(0).toUpperCase() + task.priority.slice(1) }}
          </span>
        </div>
      </div>

      <!-- Enhanced Content -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h3
            :class="[
              'font-semibold line-clamp-2 text-lg flex-1',
              task.status === 'completed'
                ? 'line-through text-text-500'
                : 'text-text-900',
            ]"
          >
            {{ task.title }}
          </h3>
        </div>

        <!-- Status Badge -->
        <div class="mb-3">
          <span
            :class="[
              'inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full',
              task.status === 'completed'
                ? 'bg-emerald-50 text-emerald-700'
                : task.status === 'in_progress'
                ? 'bg-blue-50 text-blue-700'
                : 'bg-gray-50 text-gray-700',
            ]"
          >
            {{
              task.status === "in_progress"
                ? "In Progress"
                : task.status.charAt(0).toUpperCase() + task.status.slice(1)
            }}
          </span>
        </div>

        <p
          :class="[
            'text-sm line-clamp-3 leading-relaxed mb-4',
            task.status === 'completed'
              ? 'line-through text-text-400'
              : 'text-text-600',
          ]"
        >
          {{ task.description }}
        </p>
      </div>

      <!-- Enhanced Footer -->
      <div class="pt-3 border-t border-background-100">
        <div class="flex items-center space-x-3 text-xs text-text-500 mb-3">
          <div class="flex items-center space-x-1">
            <Icon icon="lucide:calendar" class="w-3 h-3" />
            <span>Created {{ formatDate(task.created_at) }}</span>
          </div>
        </div>

        <div class="flex items-center justify-end space-x-2">
          <button
            @click="$emit('edit', task)"
            class="p-2 text-text-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200 hover:scale-110"
            title="Edit task"
          >
            <Icon icon="lucide:edit-2" class="w-4 h-4" />
          </button>
          <button
            @click="$emit('delete', task)"
            class="p-2 text-text-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 hover:scale-110"
            title="Delete task"
          >
            <Icon icon="lucide:trash-2" class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { Icon } from "@iconify/vue";

defineProps({
  task: {
    type: Object,
    required: true,
  },
  viewMode: {
    type: String,
    default: "list",
  },
  index: {
    type: Number,
    default: 0,
  },
});

defineEmits(["toggle-status", "edit", "delete"]);

const getPriorityIcon = (priority) => {
  const icons = {
    low: "🟢",
    medium: "🟡",
    high: "🔴",
  };
  return icons[priority] || "⚪";
};

const getPriorityBorder = (priority) => {
  const borders = {
    low: "border-green-400",
    medium: "border-yellow-400",
    high: "border-red-400",
  };
  return borders[priority] || "border-gray-300";
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
    year: "numeric",
  });
};
</script>

<style scoped>
/* Enhanced Priority Border Colors - Only for dynamic classes */
.task-card {
  position: relative;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.task-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.02) 0%,
    rgba(16, 185, 129, 0.02) 100%
  );
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: inherit;
  z-index: -1;
}

.task-card:hover::before {
  opacity: 1;
}

/* Enhanced Line Clamp Utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Enhanced Animation for Action Buttons */
.task-card .action-btn {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-card .action-btn:hover {
  transform: scale(1.1) translateY(-1px);
}
</style>
