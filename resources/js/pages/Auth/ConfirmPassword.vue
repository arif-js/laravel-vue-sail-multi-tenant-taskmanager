<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="password">Password</Label>
                <Input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                    autofocus />
                <InputError :message="form.errors.password" />
            </div>

            <div class="mt-4 flex justify-end">
                <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                    Confirm
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
