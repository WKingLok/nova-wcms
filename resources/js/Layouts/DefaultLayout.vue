<template>
    <Head>
        <title>Frontend</title>
    </Head>
    <div class="w-full bg-white">
        <WidgetsHeader />
        <main class="pt-[88px]">
            <slot />
        </main>
        <WidgetsFooter />
    </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
const props = defineProps({});
</script>
