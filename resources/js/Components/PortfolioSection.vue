<template>
    <section id="portfolio-section" class="px-4 lg:px-8 py-16 relative">
        <div class="flex gap-8 flex-col md:flex-row z-20 relative">
            <h1 class="uppercase text-5xl mb-4 font-semibold">
                {{ title }}
            </h1>
            <p
                class="capitalize xl:w-1/2 text-stone-500 mb-8"
                v-html="description"
            ></p>
        </div>
        <div class="grid-cols-2 md:grid-cols-5 grid mb-8 gap-4 z-20 relative">
            <div class="" v-for="photograph in photographs" :key="photograph">
                <img
                    :src="photograph.image.path"
                    :alt="photograph.image.alt"
                    class="rounded-xl"
                />
            </div>
        </div>
        <a href="#" class="text-end z-20 relative">
            <p class="font-semibold text-lg group relative">
                <span>{{ showMoreLabel }}</span>
                <i class="fa-solid fa-arrow-right"></i>
            </p>
        </a>
        <div
            class="bg-neutral-300 h-64 w-64 rounded-full absolute top-0 left-0 mt-16 z-10"
        ></div>
    </section>
</template>

<script setup>
import { defineProps } from "vue";
const props = defineProps({
    title: String,
    description: String,
    showMoreLabel: String,
    photographs: Array,
});
</script>
