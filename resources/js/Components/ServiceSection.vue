<template>
    <!-- Services section start -->
    <section
        id="services-section"
        class="px-12 lg:px-32 py-16 relative overflow-hidden"
    >
        <div class="flex gap-8 flex-col md:flex-row z-20 relative">
            <h1 class="uppercase text-5xl mb-4 font-semibold">{{ title }}</h1>
            <p
                class="capitalize xl:w-1/2 text-stone-500 mb-8"
                v-html="description"
            ></p>
        </div>
        <div class="flex flex-col md:flex-row md:gap-8 z-20 relative">
            <div
                class="flex flex-row md:flex-col justify-between md:justify-start items-center md:items-start my-8 md:w-1/3"
                v-for="service in services"
                :key="service"
            >
                <img
                    v-if="service.icon"
                    :src="service.icon.path"
                    :alt="service.icon.alt"
                    class="md:mb-4"
                />
                <div class="w-2/3 text-right md:text-left md:w-full">
                    <h1 class="uppercase text-2xl font-semibold">
                        {{ service.title }}
                    </h1>
                    <p class="capitalize text-stone-500">
                        {{ service.description }}
                    </p>
                </div>
            </div>
        </div>
        <div
            class="bg-neutral-300 h-64 w-64 rounded-full absolute top-0 -right-20 mt-16 z-10"
        ></div>
    </section>
</template>

<script setup>
import { defineProps } from "vue";
const props = defineProps({
    title: String,
    description: String,
    services: Object,
});
</script>
