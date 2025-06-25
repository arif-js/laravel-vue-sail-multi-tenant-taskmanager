<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Tasks Board</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your tasks across different stages</p>
          </div>
          <button
            @click="showCreateModal = true"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
          >
            <PlusIcon class="w-4 h-4 mr-2" />
            Add Task
          </button>
        </div>
      </div>
    </div>

    <!-- Board -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pending Column -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="px-4 py-3 border-b bg-yellow-50">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <ClockIcon class="w-5 h-5 mr-2 text-yellow-600" />
                Pending
              </h3>
              <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ pendingTasks.length }}
              </span>
            </div>
          </div>
          <div class="p-4 space-y-3 min-h-96">
            <div
              v-for="task in pendingTasks"
              :key="task.id"
              class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer"
              @click="selectTask(task)"
            >
              <h4 class="font-medium text-gray-900 mb-2">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.description }}</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ formatDate(task.created_at) }}</span>
                <div class="flex items-center space-x-2">
                  <button
                    @click.stop="updateTaskStatus(task.id, 'in_progress')"
                    class="text-blue-600 hover:text-blue-800 text-xs font-medium"
                  >
                    Start
                  </button>
                  <button
                    @click.stop="deleteTask(task.id)"
                    class="text-red-600 hover:text-red-800"
                  >
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
            <div v-if="pendingTasks.length === 0" class="text-center py-8 text-gray-500">
              <ClockIcon class="w-12 h-12 mx-auto mb-3 text-gray-300" />
              <p>No pending tasks</p>
            </div>
          </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="px-4 py-3 border-b bg-blue-50">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <PlayIcon class="w-5 h-5 mr-2 text-blue-600" />
                In Progress
              </h3>
              <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ inProgressTasks.length }}
              </span>
            </div>
          </div>
          <div class="p-4 space-y-3 min-h-96">
            <div
              v-for="task in inProgressTasks"
              :key="task.id"
              class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer"
              @click="selectTask(task)"
            >
              <h4 class="font-medium text-gray-900 mb-2">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.description }}</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ formatDate(task.created_at) }}</span>
                <div class="flex items-center space-x-2">
                  <button
                    @click.stop="updateTaskStatus(task.id, 'completed')"
                    class="text-green-600 hover:text-green-800 text-xs font-medium"
                  >
                    Complete
                  </button>
                  <button
                    @click.stop="updateTaskStatus(task.id, 'pending')"
                    class="text-yellow-600 hover:text-yellow-800 text-xs font-medium"
                  >
                    Pause
                  </button>
                  <button
                    @click.stop="deleteTask(task.id)"
                    class="text-red-600 hover:text-red-800"
                  >
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
            <div v-if="inProgressTasks.length === 0" class="text-center py-8 text-gray-500">
              <PlayIcon class="w-12 h-12 mx-auto mb-3 text-gray-300" />
              <p>No tasks in progress</p>
            </div>
          </div>
        </div>

        <!-- Completed Column -->
        <div class="bg-white rounded-lg shadow-sm border">
          <div class="px-4 py-3 border-b bg-green-50">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <CheckCircleIcon class="w-5 h-5 mr-2 text-green-600" />
                Completed
              </h3>
              <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ completedTasks.length }}
              </span>
            </div>
          </div>
          <div class="p-4 space-y-3 min-h-96">
            <div
              v-for="task in completedTasks"
              :key="task.id"
              class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer opacity-75"
              @click="selectTask(task)"
            >
              <h4 class="font-medium text-gray-900 mb-2 line-through">{{ task.title }}</h4>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.description }}</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ formatDate(task.updated_at) }}</span>
                <div class="flex items-center space-x-2">
                  <button
                    @click.stop="updateTaskStatus(task.id, 'in_progress')"
                    class="text-blue-600 hover:text-blue-800 text-xs font-medium"
                  >
                    Reopen
                  </button>
                  <button
                    @click.stop="deleteTask(task.id)"
                    class="text-red-600 hover:text-red-800"
                  >
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
            <div v-if="completedTasks.length === 0" class="text-center py-8 text-gray-500">
              <CheckCircleIcon class="w-12 h-12 mx-auto mb-3 text-gray-300" />
              <p>No completed tasks</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Task Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b">
          <h3 class="text-lg font-semibold text-gray-900">Create New Task</h3>
        </div>
        <form @submit.prevent="createTask" class="p-6">
          <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input
              id="title"
              v-model="newTask.title"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Enter task title"
            />
          </div>
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea
              id="description"
              v-model="newTask.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Enter task description"
            ></textarea>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="showCreateModal = false"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200"
            >
              Create Task
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { PlusIcon, ClockIcon, PlayIcon, CheckCircleIcon, TrashIcon } from 'lucide-vue-next'

// Props from Laravel controller
const props = defineProps({
  tasks: {
    type: Array,
    default: () => []
  }
})

// Reactive data
const showCreateModal = ref(false)
const newTask = ref({
  title: '',
  description: ''
})

// Computed properties to filter tasks by status
const pendingTasks = computed(() => 
  props.tasks.filter(task => task.status === 'pending')
)

const inProgressTasks = computed(() => 
  props.tasks.filter(task => task.status === 'in_progress')
)

const completedTasks = computed(() => 
  props.tasks.filter(task => task.status === 'completed')
)

// Methods
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric',
    year: 'numeric'
  })
}

const createTask = () => {
  router.post('/tasks', {
    title: newTask.value.title,
    description: newTask.value.description,
    status: 'pending'
  }, {
    onSuccess: () => {
      newTask.value = { title: '', description: '' }
      showCreateModal.value = false
    }
  })
}

const updateTaskStatus = (taskId, newStatus) => {
  router.patch(`/tasks/${taskId}`, {
    status: newStatus
  }, {
    preserveScroll: true
  })
}

const deleteTask = (taskId) => {
  if (confirm('Are you sure you want to delete this task?')) {
    router.delete(`/tasks/${taskId}`, {
      preserveScroll: true
    })
  }
}

const selectTask = (task) => {
  router.visit(`/tasks/${task.id}`)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>