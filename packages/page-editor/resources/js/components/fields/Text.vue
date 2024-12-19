<template>
    <input
        v-model="value"
        class="form-control form-input form-control-bordered pe-w-full pe-shadow-none"
        type="text"
        v-bind="$attrs"
    />
</template>

<script setup>
import { computed, defineProps, defineEmits } from "vue";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    error: String,
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>
