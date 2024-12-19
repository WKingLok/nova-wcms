<template>
    <MediaLibraryAttachment
        :key="key"
        name=""
        :initial-value="value"
        :route-prefix="routePrefix"
        :validation-rules="rules"
        :max-size-for-preview-in-bytes="512000"
        @change="imageChange"
        :multiple="false"
    >
        <template
            #fields="{
                getCustomPropertyInputProps,
                getCustomPropertyInputListeners,
                getCustomPropertyInputErrors,
            }"
        >
            <div class="media-library-properties">
                <div class="media-library-field">
                    <label class="media-library-label">Alt Text</label>
                    <input
                        class="media-library-input"
                        v-bind="getCustomPropertyInputProps('alt')"
                        v-on="getCustomPropertyInputListeners('alt')"
                    />
                    <p
                        v-for="error in getCustomPropertyInputErrors('alt')"
                        :key="error"
                        class="media-library-text-error"
                    >
                        {{ error }}
                    </p>
                </div>
            </div>
        </template>
    </MediaLibraryAttachment>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, computed } from "vue";
import { MediaLibraryAttachment } from "media-library-pro-vue3-attachment";
import { v4 as uuidv4 } from "uuid";
import get from "lodash/get";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    error: String,
});

const key = ref(uuidv4());

const emit = defineEmits(["update:modelValue"]);

const routePrefix = "nova-api/page-editors/media";

const value = computed({
    get() {
        if (props.modelValue == null) {
            return null;
        }
        return [props.modelValue];
    },
    set(value) {
        emit("update:modelValue", value);
    },
});

const rules = {
    accept: ["image/jpeg", "image/png", "image/svg+xml"],
    maxSizeInKB: 10240,
};

watch(
    () => props.modelValue,
    (value, old) => {
        if (
            get(value, "name") != get(old, "name") &&
            get(value, "size") != get(old, "size") &&
            get(value, "preview_url") != get(old, "preview_url") &&
            get(value, "original_url") != get(old, "original_url")
        ) {
            key.value = uuidv4();
        }
    },
    { deep: true },
);

const imageChange = (image) => {
    emit("update:modelValue", get(image, Object.keys(image)));
};
</script>
