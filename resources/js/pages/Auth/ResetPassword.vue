<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Import shadcn-vue components
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <Label for="email">Email</Label>
                <Input id="email" type="email" v-model="form.email" required autofocus autocomplete="username" />
                <InputError :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <Label for="password">Password</Label>
                <Input id="password" type="password" v-model="form.password" required autocomplete="new-password" />
                <InputError :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" required
                    autocomplete="new-password" />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Reset Password
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
