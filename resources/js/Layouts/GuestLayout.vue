<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="h-[100vh] flex flex-col items-center bg-gray-100 pt-[100px]">
        <div>
            <Link :href="route('login')">
            <img src="/images/icons/Facebooklogo.png" width="200">
            </Link>
        </div>

        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
