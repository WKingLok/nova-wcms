<template>
    <div>
        <div
            class="pe-overflow-hidden pe-rounded-lg pe-bg-white pe-p-4 pe-shadow dark:pe-bg-gray-800"
        >
            <div
                class="pe-grid pe-grid-cols-3 pe-justify-items-center pe-gap-x-4 pe-gap-y-2"
            >
                <div
                    class="hover:text-primary-500 pe-flex pe-h-10 pe-w-10 pe-cursor-pointer pe-items-center pe-justify-center pe-rounded-full hover:pe-bg-gray-200 dark:hover:pe-bg-gray-700"
                    @click="deviceToggle(`desktop`)"
                    :class="
                        deviceView == 'desktop'
                            ? 'text-primary-500'
                            : 'pe-text-gray-500'
                    "
                >
                    <Icon type="desktop-computer" />
                </div>

                <div
                    class="hover:text-primary-500 pe-flex pe-h-10 pe-w-10 pe-cursor-pointer pe-items-center pe-justify-center pe-rounded-full hover:pe-bg-gray-200 dark:hover:pe-bg-gray-700"
                    @click="deviceToggle(`mobile`)"
                    :class="
                        deviceView == 'mobile'
                            ? 'text-primary-500'
                            : 'pe-text-gray-500'
                    "
                >
                    <Icon type="device-mobile" />
                </div>

                <div
                    class="hover:text-primary-500 pe-flex pe-h-10 pe-w-10 pe-cursor-pointer pe-items-center pe-justify-center pe-rounded-full hover:pe-bg-gray-200 dark:hover:pe-bg-gray-700"
                    @click="$emit('preview')"
                >
                    <Icon type="external-link" />
                </div>

                <div class="pe-col-span-3 pe-flex pe-w-full pe-gap-2">
                    <div
                        class="pe-flex pe-w-full pe-items-center pe-justify-center"
                    >
                        <div
                            class="pe-flex pe-h-10 pe-w-10 pe-items-center pe-justify-center"
                        >
                            <Icon type="switch-horizontal" />
                        </div>
                        <div>{{ previewWidth }}px</div>
                    </div>
                    <div
                        class="pe-flex pe-w-full pe-items-center pe-justify-center"
                    >
                        <div
                            class="pe-flex pe-h-10 pe-w-10 pe-items-center pe-justify-center"
                        >
                            <Icon type="switch-vertical" />
                        </div>
                        <div>{{ previewHeight }}px</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, watch, onMounted, onUnmounted, onBeforeUnmount } from "vue";
import emitter from "@/utils/mitt.js";

const deviceView = ref(null);

const deviceToggle = (view) =>
    (deviceView.value =
        deviceView.value && deviceView.value == view ? null : view);

const previewWidth = ref(0);
const previewHeight = ref(0);
const resizeObserver = ref(null);

const updatePreviewSize = () => {
    const element = document.getElementById("editor-preview-iframe");
    if (element) {
        previewWidth.value = element.offsetWidth;
        previewHeight.value = element.offsetHeight;
    }
};

onMounted(() => {
    if (document.getElementById("editor-preview-iframe")) {
        updatePreviewSize();
        resizeObserver.value = new ResizeObserver(updatePreviewSize).observe(
            document.getElementById("editor-preview-iframe"),
        );
    }
});

onBeforeUnmount(() => {
    if (resizeObserver.value) {
        resizeObserver.value.unobserve(
            document.getElementById("editor-preview-iframe"),
        );
    }
});
watch(
    () => deviceView.value,
    (value) => {
        emitter.emit("page-editor-device", { deviceView: value });
    },
);
</script>
