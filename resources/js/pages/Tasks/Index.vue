<template>

  <Head title="Tasks" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
          Tasks
        </h2>
        <Button @click="showCreateModal = true">
          <PlusIcon class="w-4 h-4 mr-2" />
          Add Task
        </Button>
      </div>
    </template>

    <BodyLayout>
      <Card class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <TaskColumn title="Pending" :color="'yellow'" :tasks="pendingTasks" status="pending" @drop="onDrop"
            @delete="deleteTask" @update="updateTaskStatus" />

          <TaskColumn title="In Progress" :color="'blue'" :tasks="inProgressTasks" status="in_progress" @drop="onDrop"
            @delete="deleteTask" @update="updateTaskStatus" />

          <TaskColumn title="Completed" :color="'green'" :tasks="completedTasks" status="completed" @drop="onDrop"
            @delete="deleteTask" @update="updateTaskStatus" />
        </div>
      </Card>

      <!-- Create Task Modal -->
      <Dialog v-model:open="showCreateModal">
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Create New Task</DialogTitle>
          </DialogHeader>
          <form class="space-y-5" @submit.prevent="createTask">
            <div>
              <Label for="title">Title</Label>
              <Input id="title" v-model="newTask.title" required placeholder="Enter task title" />
            </div>
            <div>
              <Label for="description">Description</Label>
              <Input id="description" as="textarea" v-model="newTask.description" rows="3"
                placeholder="Enter task description" />
            </div>
            <div>
              <Label for="assignee">Assignee</Label>
              <Select v-model="newTask.user_id">
                <SelectTrigger>
                  <SelectValue placeholder="Select Assignee" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem v-for="user in currentTeam.users" :key="user.id" :value="user.id">
                    {{ user.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
              <Button type="button" variant="secondary" @click="showCreateModal = false">Cancel</Button>
              <Button type="submit">Create Task</Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>
    </BodyLayout>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { computed, ref, reactive, ComputedRef } from 'vue'
import { onMounted, onBeforeUnmount } from 'vue';

import { router, Head, usePage } from '@inertiajs/vue3'
import {
  PlusIcon,
} from 'lucide-vue-next'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BodyLayout from '@/Layouts/BodyLayout.vue'
import TaskColumn from '@/Components/tasks/TaskColumn.vue'

import { Button } from '@/Components/ui/button'
import { Card } from '@/Components/ui/card'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/Components/ui/dialog'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select'

const props = defineProps({
  tasks: { type: Array, default: () => [] },
})

export interface Task {
  id: number;
  title: string;
  description: string;
  user_id: number;
  status: 'pending' | 'in_progress' | 'completed';
  created_at: string;
  updated_at?: string;
}

const page = usePage()
const currentTeamId = page.props.currentTeamId ?? null
const currentTeam: any = page.props.currentTeam ?? null

const showCreateModal = ref(false)
const newTask = ref({ title: '', description: '', user_id: null, status: 'pending' })
const localTasks = reactive([...props.tasks]) as Task[]

const filterByStatus = (status: string): ComputedRef<Task[]> => {
  return computed(() => localTasks.filter((task: Task) => task.status === status))
}

const pendingTasks: ComputedRef<Task[]> = filterByStatus('pending')
const inProgressTasks: ComputedRef<Task[]> = filterByStatus('in_progress')
const completedTasks: ComputedRef<Task[]> = filterByStatus('completed')

const createTask = () => {
  router.post(
    route('tasks.store', { team: currentTeamId }),
    {
      title: newTask.value.title,
      description: newTask.value.description,
      user_id: newTask.value.user_id || currentTeam?.users[0]?.id, // Default to first user if none selected
      status: 'pending',
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        const newTaskFromServer = (page.props.tasks as any[]).at(-1) // Get last task
        localTasks.push(newTaskFromServer)

        showCreateModal.value = false
        newTask.value = { title: '', description: '', user_id: null, status: 'pending' }
      },
      onError: (errors) => {
        console.error('Error creating task:', errors)
      },
    }
  )
}

const updateTaskStatus = (taskId: number, newStatus: string) => {
  router.patch(
    route('tasks.update', { team: currentTeamId, task: taskId }),
    { status: newStatus },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        const updatedTask = page.props.updatedTask // make sure backend returns it
        replaceTaskInList(updatedTask)
      }
    }
  )
}

const deleteTask = (taskId: number) => {
  if (confirm('Are you sure you want to delete this task?')) {

    router.delete(route('tasks.destroy', { team: currentTeamId, task: taskId }), {
      preserveScroll: true,
    })

    console.log(`Task with ID ${taskId} deleted.`);

    removeTaskFromList(taskId)
  }
}

const onDrop = (newStatus: string, dropResult: any) => {
  const { addedIndex, removedIndex, payload: task } = dropResult

  if (addedIndex === null && removedIndex === null) return;
  if (!task || task.status === newStatus) return

  const updatedTask = { ...task, status: newStatus }

  router.patch(route('tasks.update', { team: currentTeamId, task: task.id }), updatedTask, {
    preserveScroll: true,
  })

  removeTaskFromList(task.id)
  addTaskToList(updatedTask)
}

const replaceTaskInList = (updatedTask: any) => {
  const index = localTasks.findIndex(t => t.id === updatedTask.id)
  if (index !== -1) {
    localTasks.splice(index, 1, updatedTask)
  } else {
    localTasks.push(updatedTask)
  }
}

const removeTaskFromList = (id: number) => {
  const index = localTasks.findIndex((t: any) => t.id === id)
  if (index !== -1) localTasks.splice(index, 1)
}

const addTaskToList = (task: any) => localTasks.push(task)

onMounted(() => {
  window.addEventListener('task:assigned', handleNewTask as EventListener);
});

onBeforeUnmount(() => {
  window.removeEventListener('task:assigned', handleNewTask as EventListener);
});

const handleNewTask = (e: Event) => {
  const customEvent = e as CustomEvent;
  const newTask = customEvent.detail;

  localTasks.push(newTask);
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
