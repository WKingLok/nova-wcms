<template>
    <multiselect
        v-model="value"
        :options="options"
        track-by="value"
        label="label"
        :hideSelected="true"
    >
        <template v-slot:noResult>
            <span>Oops! No elements found.</span>
        </template>
        <template v-slot:noOptions>
            <span>Oops! No elements found.</span>
        </template>
    </multiselect>
</template>

<script setup>
import { computed, defineProps, defineEmits } from "vue";
import multiselect from "vue-multiselect";
import get from "lodash/get";
import find from "lodash/find";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    error: String,
    options: {
        type: Array,
        default: [],
    },
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get() {
        return find(
            props.options,
            (option) => option.value == props.modelValue
        );
    },
    set(value) {
        emit("update:modelValue", get(value, "value"));
    },
});
</script>
