<script setup lang="ts">
import { Checkbox } from '@/Components/ui/checkbox';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const page = usePage();
const query = page.url.split('?')[1];
const params = new URLSearchParams(query);

const invite_token = params.get('invite_token') || '';
const team_id = params.get('team_id') || '';

const form = useForm({
    email: '',
    password: '',
    remember: false,
    ...(invite_token && { invite_token }),
    ...(team_id && { team_id }),
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout title="Log in">

        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="email">Email</Label>

                <Input id="email" type="email" v-model="form.email" required autofocus autocomplete="username" />

                <InputError :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <Label for="password">Password</Label>

                <Input id="password" type="password" v-model="form.password" required autocomplete="current-password" />

                <InputError :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <Label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </Label>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                Forgot your password?
                </Link>

                <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
