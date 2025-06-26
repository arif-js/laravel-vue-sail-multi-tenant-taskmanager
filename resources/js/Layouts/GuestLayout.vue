<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

import {
    Card,
    CardContent,
    CardHeader,
    CardTitle
} from "@/Components/ui/card"

defineProps<{
    title?: string;
}>();

</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900">
        <div>
            <Link href="/">
            <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div class="mt-6 w-full overflow-hidden">
            <Card class="w-full max-w-md mx-auto">
                <CardHeader class="text-center">
                    <CardTitle>
                        {{ title || 'Welcome' }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <slot />
                </CardContent>
            </Card>
        </div>
    </div>
</template>
s