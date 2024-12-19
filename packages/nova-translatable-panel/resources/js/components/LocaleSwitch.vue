<template>
    <div
        class="tp-inline-flex tp-divide-x tp-overflow-hidden tp-rounded-md tp-shadow-md tp-divide-gray dark:tp-divide-gray-700"
        role="group"
    >
        <button
            type="button"
            class="tp-inline-block tp-px-6 tp-py-2.5 tp-uppercase tp-leading-tight tp-transition tp-duration-150 tp-ease-in-out tp-focus:outline-none tp-focus:ring-0 tp-text-sm tp-font-bold"
            :class="
                locale == current
                    ? 'bg-primary-500 hover:bg-primary-400 focus:bg-primary-400 active:bg-primary-400 tp-text-white dark:tp-text-gray-900'
                    : 'tp-bg-white tp-text-gray-400 dark:tp-bg-gray-800 hover:tp-text-gray-300'
            "
            v-for="(label, locale) in locales"
            :key="locale"
            @click="$emit('change', locale)"
        >
            {{ label }}
        </button>
    </div>
</template>

<script>
export default {
    props: {
        locales: Array | Object,
        current: String,
    },
    emits: ["change"],
};
</script>
