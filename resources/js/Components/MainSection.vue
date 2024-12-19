<template>
    <section class="h-screen px-12 lg:px-32 py-32 mb-8">
        <div
            class="w-full md:w-1/2 h-full flex flex-col md:justify-center relative z-20"
        >
            <!-- Main text start -->
            <h1 class="uppercase text-5xl xl:text-7xl mb-4 font-semibold">
                {{ title }}
            </h1>
            <p class="capitalize xl:w-1/2 text-stone-500">
                {{ description }}
            </p>
            <!-- Main text end -->

            <!-- Social links start -->
            <div class="flex mt-3 gap-8">
                <a href="#">
                    <i class="fa-brands fa-instagram socialIcon"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-linkedin socialIcon"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-facebook socialIcon"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-dribbble socialIcon"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-pinterest socialIcon"></i>
                </a>
            </div>
            <!-- Social links end -->
        </div>
        <div
            class="absolute bottom-0 right-0 overflow-hidden h-2/5 sm:h-1/2 md:h-3/5 lg:h-4/6"
        >
            <img v-if="image" :src="image.path" alt="img" class="h-full" />
        </div>
        <div
            class="w-full h-20 flex justify-start md:justify-center items-center relative z-20"
        >
            <a href="#about-section">
                <i
                    class="fa-solid fa-arrow-down transition text-stone-100 text-xl bg-stone-700 rounded-full shadow p-4 hover:-translate-y-2 hover:shadow-xl"
                ></i>
            </a>
        </div>
    </section>
</template>

<script setup>
import { defineProps } from "vue";
const props = defineProps({
    title: String,
    description: String,
    image: Object,
});
</script>
