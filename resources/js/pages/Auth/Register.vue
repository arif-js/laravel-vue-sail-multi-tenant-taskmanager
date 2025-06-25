<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';

const page = usePage();
const query = page.url.split('?')[1]; // get the query part
const params = new URLSearchParams(query);

const invite_token = params.get('invite_token') || '';
const team_id = params.get('team_id') || '';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    ...(invite_token && { invite_token }),
    ...(team_id && { team_id }),
});

const submit = () => {
    // Remove empty invite_token and team_id before submit
    if (!form.invite_token) delete form.invite_token;
    if (!form.team_id) delete form.team_id;

    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout title="Register">

        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <Label for="name" value="Name">Name</Label>

                <Input id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />

                <InputError :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <Label for="email" value="Email" />

                <Input id="email" type="email" v-model="form.email" required autocomplete="username" />

                <InputError :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <Label for="password" value="Password">Password</Label>>

                <Input id="password" type="password" v-model="form.password" required autocomplete="new-password" />

                <InputError :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <Label for="password_confirmation" value="Confirm Password">Confirm Password</Label>

                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" required
                    autocomplete="new-password" />

                <InputError :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4 flex items-center justify-end gap-2">
                <Link :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800">
                Already registered?
                </Link>

                <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
