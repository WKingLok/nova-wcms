<template>
    <div
        class="pe-divide-gray pe-flex pe-min-h-[36px] pe-min-w-[250px] pe-divide-x pe-overflow-hidden pe-rounded-md pe-shadow dark:pe-divide-gray-700"
    >
        <div
            v-for="(locale, key) in locales"
            :key="locale.id"
            class="pe-focus:outline-none pe-focus:ring-0 pe-flex pe-flex-1 pe-items-center pe-justify-center pe-px-2.5 pe-py-2 pe-text-sm pe-font-bold pe-leading-tight pe-transition pe-duration-150 pe-ease-in-out"
            :class="{
                'bg-primary-500 hover:bg-primary-400 focus:bg-primary-400 active:bg-primary-400 pe-text-white dark:pe-text-gray-900':
                    key == modelValue,
                'hover:text-primary-500 pe-bg-white dark:pe-bg-gray-800':
                    key != modelValue,
            }"
            @click="$emit('update:modelValue', key)"
        >
            {{ locale }}
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { get } from "lodash";

const props = defineProps({
    modelValue: String,
});

const locales = get(Nova, "app.config.locales", ["en", "tc", "sc"]);

const emit = defineEmits(["update:modelValue"]);

if (!props.modelValue) {
    emit("update:modelValue", locales[0]);
}
</script>
