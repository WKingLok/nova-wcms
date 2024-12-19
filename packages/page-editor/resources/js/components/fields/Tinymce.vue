<template>
    <Editor
        :api-key="key"
        v-model="value"
        :init="{
            plugins: 'lists code wordcount visualblocks link template',
            menubar: '',
            toolbar:
                'undo redo | blocks | bold italic underline | align numlist bullist | link code visualblocks template',
            block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
            templates: templates,
        }"
        :id="id"
    />
</template>

<script setup>
import { computed, defineProps, defineEmits, watch, ref } from "vue";
import Editor from "@tinymce/tinymce-vue";
import { v4 as uuidv4 } from "uuid";
import get from "lodash/get";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    locale: String,
    error: String,
});

const emit = defineEmits(["update:modelValue"]);

const id = ref(uuidv4());

const key = get(Nova, "app.config.tinymce.key");

const templates = get(Nova, "app.config.tinymce.templates", []);

watch(
    () => props.locale,
    () => {
        window.tinymce.get(id.value).setContent("");
    },
);

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>

<style>
.tox-tinymce-aux {
    z-index: 3000 !important;
}
</style>
