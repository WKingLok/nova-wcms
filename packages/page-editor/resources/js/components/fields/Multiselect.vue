<template>
    <multiselect
        :options="options"
        track-by="value"
        label="label"
        v-model="value"
        :multiple="true"
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
import transform from "lodash/transform";
import cloneDeep from "lodash/cloneDeep";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    error: String,
    options: {
        type: Array,
        default: [],
    },
    hideSelected: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get() {
        let _modelValue = transform(
            cloneDeep(props.modelValue),
            (result, value, key) => {
                let _oj = find(props.options, (o) => get(o, "value") == value);
                if (_oj) result.push(_oj);
            },
            [],
        );
        return _modelValue;
    },
    set(value) {
        let _value = transform(
            value,
            (result, value, key) => {
                result.push(get(value, "value"));
                return;
            },
            [],
        );
        emit("update:modelValue", _value);
    },
});
</script>
