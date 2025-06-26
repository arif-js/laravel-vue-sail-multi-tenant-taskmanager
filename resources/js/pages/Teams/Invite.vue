<template>

  <Head title="Teams" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Invite users to Team {{ props.team.name }}
      </h2>
    </template>

    <BodyLayout>
      <Card class="max-w-md mx-auto">
        <CardHeader>
          Invite a member to your team.

          <p v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</p>
          <p v-if="form.errors.role" class="text-red-600 text-sm">{{ form.errors.role }}</p>
        </CardHeader>

        <CardContent class="flex flex-col gap-4">
          <CardDescription>
            <form class="flex flex-col gap-4" @submit.prevent="submit">
              <div>
                <Label for="email">Email</Label>
                <Input id="email" v-model="form.email" type="email" required placeholder="user@example.com" />
              </div>
              <div>
                <Label for="role">Role</Label>
                <Select v-model="form.role">
                  <SelectTrigger>
                    <SelectValue placeholder="Select role" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Admin">Admin</SelectItem>
                    <SelectItem value="Member">Member</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <Button variant="default" type="submit" class="w-full">Send Invitation</Button>
            </form>
          </CardDescription>

          <CardDescription>
            <Button variant="outline" class="w-full">
              <a :href="route('teams.show', { team: props.team.id })">
                Back to Team
              </a>
            </Button>
          </CardDescription>
        </CardContent>
      </Card>
    </BodyLayout>

  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BodyLayout from '@/Layouts/BodyLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/Components/ui/select'
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
  },
})

const form = useForm({
  email: '',
  role: 'Member', // default role
})

function submit() {
  form.post(route('teams.invite', { team: props.team.id }), {
    onSuccess: () => {
      form.reset()

      // Optionally, show a success message or redirect
    },
    onError: (errors) => {
      // Handle errors if needed
      console.error(errors)
    },
  })
}
</script>
