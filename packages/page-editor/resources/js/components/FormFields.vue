<template>
    <div
        :class="{
            'has-error': hasError,
        }"
    >
        <PageEditorRepeatable
            v-if="repeatable"
            v-bind="{
                ...props,
            }"
            v-model="value"
        />
        <PageEditorNested
            v-else-if="nested != null"
            v-bind="{
                ...props,
                title: title ?? props.label,
            }"
            v-model="value"
        />
        <template v-else>
            <PageEditorIndex
                v-if="translatable"
                v-bind="props"
                v-model="value[locale]"
            />
            <PageEditorIndex v-else v-bind="props" v-model="value" />
        </template>
        <div v-if="hasError">
            <div v-for="msg in errorsMsg">
                <span class="tw-text-red-500">{{ msg }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, defineEmits } from "vue";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    type: String,
    label: String,
    name: String,
    options: Array,
    translatable: Boolean,
    repeatable: Boolean,
    nested: Array,
    validate: String,
    locale: String,
    localeString: String,
    title: String,
    helpText: String,
    errors: Array,
    repeatableIndex: Number,
});

const emit = defineEmits(["update:modelValue"]);

const hasError = computed(
    () =>
        (props.errors && !props.locale) ||
        (props.errors && props.locale && props.locale == "en"),
);

const errorsMsg = computed(() => {
    if (props.repeatableIndex || props.repeatableIndex === 0) {
        return props.errors[props.repeatableIndex];
    }

    return props.errors;
});

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>
