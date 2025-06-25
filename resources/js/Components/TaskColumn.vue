<template>
    <div class="bg-white rounded-lg shadow-sm border">
        <div :class="`px-4 py-3 border-b bg-${color}-50`">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <component :is="icon" :class="`w-5 h-5 mr-2 text-${color}-600`" />
                    {{ title }}
                </h3>
                <span :class="`bg-${color}-100 text-${color}-800 text-xs font-medium px-2.5 py-0.5 rounded-full`">
                    {{ tasks.length }}
                </span>
            </div>
        </div>
        <div class="p-4 space-y-3 min-h-96">
            <div class="mb-4">
                <Label :for="`search-${status}`" class="sr-only">Search Tasks</Label>
                <Input :id="`search-${status}`" type="text" placeholder="Search tasks..." class="w-full"
                    @input="(e) => $emit('search', e.target.value, status)" />
            </div>

            <Container group-name="tasks" :get-child-payload="(i) => tasks[i]" @drop="(e) => $emit('drop', status, e)">
                <Draggable v-for="task in tasks" :key="task.id" :data="task" :class="[
                    'bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer',
                    status === 'completed' ? 'opacity-75' : ''
                ]">
                    <h4 :class="['font-medium text-gray-900 mb-2', status === 'completed' ? 'line-through' : '']">
                        {{ task.title }}
                    </h4>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">{{ formatDate(task.updated_at || task.created_at) }}</span>
                        <div class="flex items-center space-x-2">
                            <button @click.stop="$emit('delete', task.id)" class="text-red-600 hover:text-red-800">
                                <TrashIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </Draggable>

                <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
                    <component :is="icon" class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    <p>No {{ title.toLowerCase() }} tasks</p>
                </div>
            </Container>
        </div>
    </div>
</template>

<script setup>
import { Container, Draggable } from 'vue-dndrop'
import { TrashIcon } from 'lucide-vue-next'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

defineProps({
    title: String,
    icon: [String, Object],
    color: String,
    tasks: Array,
    status: String,
})

const formatDate = (dateStr) =>
    new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
</script>
