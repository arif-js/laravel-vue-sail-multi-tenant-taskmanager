<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <BodyLayout>
            <div class="flex gap-2 flex-wrap">
                <Card v-for="(stats, index) in teams" :key="index" class="max-w-md min-w-[300px] w-full">
                    <CardHeader>
                        <CardTitle v-if="stats.team_name" class="flex justify-between items-center">
                            {{ stats.team_name }}

                            <div class="flex space-x-2">
                                <Button>
                                    <a :href="route('teams.show', { team: stats.team_id })">
                                        View
                                    </a>
                                </Button>
                                <Button v-if="currentTeamId !== stats.team_id" @click="switchTeam(stats.team_id)"
                                    variant="outline">
                                    Switch
                                </Button>
                                <Badge v-if="currentTeamId === stats.team_id" variant="secondary">
                                    Current Team
                                </Badge>
                            </div>
                        </CardTitle>
                        <CardTitle v-else>You are not a part of a team</CardTitle>
                    </CardHeader>

                    <CardContent v-if="!stats.team_name">
                        <CardDescription>
                            <Button variant="outline" class="w-auto">
                                <a :href="route('teams.create')">
                                    Create or Join a Team
                                </a>
                            </Button>
                        </CardDescription>
                    </CardContent>

                    <CardContent v-else>
                        <CardDescription>
                            Total Tasks: {{ stats.total }}
                        </CardDescription>
                        <CardDescription>
                            Pending: {{ stats.pending }}
                        </CardDescription>
                        <CardDescription>
                            In Progress: {{ stats.in_progress }}
                        </CardDescription>
                        <CardDescription>
                            Completed: {{ stats.completed }}
                        </CardDescription>
                    </CardContent>
                </Card>
            </div>
        </BodyLayout>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BodyLayout from '@/Layouts/BodyLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { router } from '@inertiajs/vue3';

const page = usePage();
const currentTeamId = page.props.currentTeamId ?? null;

const props = defineProps({
    teams: {
        type: Array,
        default: () => [],
    },
})

function switchTeam(id) {
    router.visit(route('teams.switch', id), {
        method: 'post',
        preserveScroll: true,
        preserveState: false,
        only: ['teams', 'currentTeamId'],
    });
}

</script>
