<template>

    <Head title="Teams" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit Team
            </h2>
        </template>

        <BodyLayout>
            <div class="flex items-center justify-center">
                <Card class="max-w-md">
                    <CardHeader>
                        Fill in the details below to edit the team.

                        <p v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</p>
                        <p v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</p>
                    </CardHeader>

                    <CardContent class="flex flex-col gap-4">
                        <CardDescription>
                            <form class="flex flex-col gap-4" @submit.prevent="updateTeam">
                                <div>
                                    <Label for="teamName">Team Name</Label>
                                    <Input id="teamName" v-model="form.name" placeholder="Enter team name" required />
                                </div>
                                <div>
                                    <Label for="description">Description</Label>
                                    <Input id="description" v-model="form.description"
                                        placeholder="Enter description" />
                                </div>
                                <Button variant="default" type="submit" class="w-full">Update Team</Button>
                            </form>
                        </CardDescription>

                        <CardDescription>
                            <Button variant="outline" class="w-full">
                                <a :href="route('teams.show', { team: props.team.id })">
                                    Back to Team Details
                                </a>
                            </Button>
                        </CardDescription>
                    </CardContent>
                </Card>
            </div>
        </BodyLayout>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BodyLayout from '@/Layouts/BodyLayout.vue'
import { Head } from '@inertiajs/vue3'
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
} from "@/Components/ui/card"

const props = defineProps({
    team: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.team.name,
    description: props.team.description
})

const updateTeam = () => {
    form.put(route('teams.update', { team: props.team.id }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
}
</script>
