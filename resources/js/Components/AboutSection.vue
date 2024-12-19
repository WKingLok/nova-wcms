<template>
    <section
        id="about-section"
        class="px-12 lg:px-32 py-16 border-t border-stone-500 relative"
    >
        <h1 class="uppercase text-5xl mb-4 font-semibold z-20 relative">
            {{ title }}
        </h1>
        <p
            class="capitalize xl:w-1/2 text-stone-500 mb-8 z-20 relative"
            v-html="content"
        ></p>
        <a href="#" class="text-end z-20 relative">
            <p class="font-semibold text-lg group relative">
                <span>{{ readMoreLabel }}</span>
                <i class="fa-solid fa-arrow-right"></i>
            </p>
        </a>
        <div
            class="bg-neutral-200 h-44 w-44 md:h-52 md:w-52 rounded-full absolute top-0 -left-20 mt-16 z-10"
        ></div>
    </section>
</template>

<script setup>
import { defineProps } from "vue";
const props = defineProps({
    title: String,
    content: String,
    readMoreLabel: String,
});
</script>
