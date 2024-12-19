<template>
    <textarea
        class="form-control form-input form-control-bordered pe-block pe-h-auto pe-w-full pe-py-3 pe-shadow-none"
        v-model="value"
        v-bind="$attrs"
        rows="6"
    ></textarea>
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
