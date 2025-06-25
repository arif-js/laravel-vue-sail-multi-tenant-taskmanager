<template>
  <form @submit.prevent="submit">
    <div class="space-y-4">
      <!-- Email -->
      <div>
        <Label for="email">Email</Label>
        <Input id="email" v-model="form.email" type="email" required placeholder="user@example.com" />
        <p v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</p>
      </div>

      <!-- Role -->
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
        <p v-if="form.errors.role" class="text-red-500 text-sm">{{ form.errors.role }}</p>
      </div>

      <!-- Submit -->
      <Button type="submit">Send Invitation</Button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select'

const props = defineProps({
  team: Object,
})

const form = useForm({
  email: '',
  role: 'Member', // default
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
