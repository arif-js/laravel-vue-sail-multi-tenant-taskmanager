<template>

    <Head title="Teams" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Team Details
                </h2>
                <div class="flex space-x-2">
                    <Button v-if="canEdit" :href="route('teams.edit', { team: team.id })">
                        <a :href="route('teams.edit', { team: team.id })">
                            Edit Team
                        </a>
                    </Button>

                    <Button v-if="canDelete" variant="destructive" @click="confirmDelete">
                        Delete Team
                    </Button>
                </div>
            </div>
        </template>

        <BodyLayout>
            <div class=" bg-card rounded-lg shadow p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <Avatar class="h-8 w-8">
                        <AvatarImage src="/team-avatar.png" alt="Team Avatar" />
                        <AvatarFallback>{{ team.name.slice(0, 2).toUpperCase() }}</AvatarFallback>
                    </Avatar>
                    <div>
                        <h2 class="text-xl font-semibold">{{ team.name }}</h2>
                        <p class="text-muted-foreground text-sm">
                            Owner: {{ team.owner.name }} ({{ team.owner.email }})
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <Label class="block mb-1">Description</Label>
                    <p class="text-muted-foreground">
                        {{ team.description ?? 'No description provided.' }}
                    </p>
                    <p class="text-muted-foreground text-sm">
                        Tasks Count: {{ tasksCount }}
                    </p>
                </div>
            </div>

            <div class="bg-card rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">Team Members</h3>
                    <Button v-if="canInvite" size="sm">
                        <a :href="route('teams.inviteForm', { team: team.id })">
                            Invite Members
                        </a>
                    </Button>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="member in team.users" :key="member.id">
                            <TableCell>
                                <div class="flex items-center space-x-2">
                                    <Avatar class="h-8 w-8">
                                        <AvatarImage src="/user.png" alt="User" />
                                        <AvatarFallback>{{ member.name.slice(0, 2).toUpperCase() }}</AvatarFallback>
                                    </Avatar>
                                    <span>{{ member.name }}</span>
                                </div>
                            </TableCell>
                            <TableCell>{{ member.email }}</TableCell>
                            <TableCell>
                                <span>{{ member.pivot.role }}</span>
                            </TableCell>
                            <TableCell>
                                <Button size="icon" variant="ghost">
                                    <!-- Optional actions -->
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </BodyLayout>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/Components/ui/button'
import { Avatar, AvatarImage, AvatarFallback } from '@/Components/ui/avatar'
import { Label } from '@/Components/ui/label'
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BodyLayout from '@/Layouts/BodyLayout.vue'
import { router } from '@inertiajs/vue3';

const props = defineProps(['team', 'role', 'tasksCount']);
const team = props.team;
const userRole = props.role;
const tasksCount = props.tasksCount;

// console.log('Team:', JSON.stringify(team), userRole, tasksCount);

const canInvite = computed(() => {
    return userRole === 'Owner' || userRole === 'Admin';
});

const canDelete = computed(() => {
    return userRole === 'Owner';
});

const canEdit = computed(() => {
    return userRole === 'Owner' || userRole === 'Admin';
});

const confirmDelete = () => {
    if (confirm('Are you sure you want to delete this team? This action cannot be undone.')) {
        router.delete(route('teams.destroy', { team: team.id }));
    }
};

</script>