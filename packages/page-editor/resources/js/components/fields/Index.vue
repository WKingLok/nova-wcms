<template>
    <div>
        <label :class="{ required }" :for="name" v-if="label">
            <span class="pe-font-[700]">{{ label }}</span>
            <span
                class="pe-ml-1 pe-inline-flex pe-items-center pe-rounded pe-bg-blue-50 pe-px-2 pe-py-1 pe-text-xs pe-font-medium pe-text-blue-700 pe-ring-1 pe-ring-inset pe-ring-blue-700/10"
                v-if="translatable"
                >{{ localeString }}</span
            >
        </label>

        <div class="pe-mt-2">
            <PageEditorMultiselect
                v-if="type == 'multiselect'"
                v-model="value"
                :options="options"
            />
            <PageEditorSelect
                v-else-if="type == 'select'"
                v-model="value"
                :options="options"
            />
            <PageEditorImage v-else-if="type == 'image'" v-model="value" />
            <PageEditorTinymce
                v-else-if="type == 'tinymce'"
                v-model="value"
                :locale="localeString"
            />
            <PageEditorTextarea
                v-else-if="type == 'textarea'"
                v-model="value"
            />
            <PageEditorText v-else v-model="value" />
        </div>
        <div v-if="helpText" class="pe-mt-2 pe-text-xs pe-text-gray-600">
            {{ helpText }}
        </div>
    </div>
</template>

<script setup>
import { computed, ref, defineProps, defineEmits } from "vue";
import startCase from "lodash/startCase";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    type: {
        type: String,
        default: "text",
    },
    label: String,
    name: String,
    translatable: Boolean,
    localeString: String,
    validate: String,
    options: Array,
    helpText: String,
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

const label = props.label;

const required = computed(() => {
    return false;
});
</script>
