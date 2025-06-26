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
                <Card class="w-full overflow-x-auto">
                    <CardHeader class="flex flex-row items-center w-full justify-between">
                        <div class="flex flex-col gap-2">
                            <CardTitle>Teams Overview</CardTitle>
                            <CardDescription>Summary of your teams and tasks</CardDescription>
                        </div>
                        <Button variant="outline" as-child>
                            <a :href="route('teams.create')">
                                Add New
                            </a>
                        </Button>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <TableHeader class="bg-gray-50 dark:bg-gray-800">
                                <TableRow>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">Team Name</TableHead>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">Total Tasks</TableHead>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">Pending</TableHead>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">In Progress</TableHead>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">Completed</TableHead>
                                    <TableHead class="px-6 py-4 whitespace-nowrap">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(stats, index) in teams" :key="index">
                                    <TableCell class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="stats.team_name">{{ stats.team_name }}</span>
                                        <span v-else class="text-gray-400">No Team</span>
                                    </TableCell>
                                    <TableCell class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="stats.team_name">{{ stats.total }}</span>
                                        <span v-else>-</span>
                                    </TableCell>
                                    <TableCell class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="stats.team_name">{{ stats.pending }}</span>
                                        <span v-else>-</span>
                                    </TableCell>
                                    <TableCell class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="stats.team_name">{{ stats.in_progress }}</span>
                                        <span v-else>-</span>
                                    </TableCell>
                                    <TableCell class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="stats.team_name">{{ stats.completed }}</span>
                                        <span v-else>-</span>
                                    </TableCell>
                                    <TableCell class="px-6 py-4 whitespace-nowrap flex gap-2 items-center">
                                        <template v-if="stats.team_name">
                                            <Button size="sm" as-child>
                                                <a :href="route('teams.show', { team: stats.team_id })">
                                                    View
                                                </a>
                                            </Button>
                                            <Button v-if="currentTeamId !== stats.team_id"
                                                @click="switchTeam(stats.team_id)" size="sm" variant="outline">
                                                Switch
                                            </Button>
                                            <Badge v-if="currentTeamId === stats.team_id" variant="secondary">
                                                Current Team
                                            </Badge>
                                        </template>
                                        <template v-else>
                                            <Button variant="outline" size="sm" as-child>
                                                <a :href="route('teams.create')">
                                                    Create or Join a Team
                                                </a>
                                            </Button>
                                        </template>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
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
} from "@/Components/ui/card"
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { router } from '@inertiajs/vue3';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table'

const page = usePage();
const currentTeamId = page.props.currentTeamId ?? null;

defineProps({
    teams: {
        type: Array<{
            team_id: number;
            team_name: string | null;
            total: number;
            pending: number;
            in_progress: number;
            completed: number;
        }>,
        default: () => [],
    },
})

function switchTeam(id: number) {
    router.visit(route('teams.switch', id), {
        method: 'post',
        preserveScroll: true,
        preserveState: false,
        only: ['teams', 'currentTeamId'],
    });
}

</script>

