<script setup lang="ts">
import { Container, Draggable } from 'vue-dndrop'
import { TrashIcon } from 'lucide-vue-next'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { computed } from 'vue'

const props = defineProps<{
    title: string,
    color: string,
    tasks: Array<{
        id: number,
        title: string,
        description: string,
        user_id: number,
        updated_at?: string,
        created_at: string,
    }>,
    status: string,
}>();

const formatDate = (dateStr: string) =>
    new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })

const bgColorClass = computed(() => `bg-${props.color}-200`)

</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border">
        <div :class="['px-4', 'py-3', 'border-b', bgColorClass, 'flex', 'items-center', 'justify-between']">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    {{ title }}
                </h3>
                <span :class="`text-xs font-medium px-2.5 py-0.5 rounded-full`">
                    {{ tasks.length }}
                </span>
            </div>
        </div>
        <div class="p-4 space-y-3 min-h-96">
            <div class="mb-4">
                <Label :for="`search-${status}`" class="sr-only">Search Tasks</Label>
                <Input :id="`search-${status}`" type="text" placeholder="Search tasks..." class="w-full"
                    @input="(e: any) => $emit('search', e.target.value, status)" />
            </div>

            <Container class="h-96" group-name="tasks" :get-child-payload="(i: number) => tasks[i]"
                @drop="(e: any) => $emit('drop', status, e)">
                <Draggable v-for="task in tasks" :key="task.id" :data="task" :class="[
                    'bg-gray-50 rounded-lg mb-4 p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer',
                    status === 'completed' ? 'opacity-75' : ''
                ]">
                    <h4 :class="['font-medium text-gray-900 mb-2', status === 'completed' ? 'line-through' : '']">
                        {{ task.title }}
                    </h4>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ task.description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">{{ formatDate(task.updated_at || task.created_at) }}</span>
                        <div class="flex items-center space-x-2">
                            <Button variant="ghost" class="text-red-600 hover:text-red-800"
                                @click.stop="$emit('delete', task.id)">
                                <TrashIcon class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </Draggable>

                <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
                    <p>No {{ title.toLowerCase() }} tasks</p>
                </div>
            </Container>
        </div>
    </div>
</template>