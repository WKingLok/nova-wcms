<template>
    <div
        class="pe-item-center pe-hidden pe-h-full pe-w-full pe-overflow-auto pe-bg-gray-200 md:pe-flex"
        :class="deviceView"
    >
        <pre class="pe-h-full pe-w-full" v-if="previewCodeView">{{ data }}</pre>
        <iframe
            v-if="previewEnabled"
            class="pe-h-full pe-w-full pe-bg-white"
            id="editor-preview-iframe"
            ref="previewIframeRef"
            src="/wcms/resources/page-editors/preview"
            sandbox="allow-scripts allow-same-origin allow-popups"
        ></iframe>
    </div>
</template>
<script setup>
import { defineProps, ref, onMounted, watch, computed } from "vue";
import emitter from "@/utils/mitt.js";
import get from "lodash/get";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    locale: String,
    config: Object,
});

const data = computed(() => props.modelValue);

const previewIframeRef = ref(null);
const previewEnabled = get(props, "config.enabled", true);
const previewCodeView = get(props, "config.code_view", false);

const deviceView = ref("");

/**
 * handle iframe switch device view
 */
const handleDeviceView = (data) => {
    deviceView.value = `view-${data}`;
};

/**
 * handle preview data update
 */
const updatePreview = () => {
    try {
        previewIframeRef.value.contentWindow.postMessage(
            JSON.parse(
                JSON.stringify({
                    sender: "nova-cms",
                    key: "page-preview-data",
                    layout: get(props.config, "type") == "page" ? "full" : null,
                    value: JSON.stringify(data.value),
                }),
            ),
            origin,
        );
        previewIframeRef.value.contentWindow.document.addEventListener(
            "drag",
            function (event) {
                event.preventDefault();
            },
        );
    } catch (e) {}
};

/**
 * when iframe loaded,
 * update iframe data
 */
const onIframeLoaded = () => {
    updatePreview();
};

/**
 * handle message from iframe
 */
const handleMessage = (event) => {
    if (event.origin !== origin || get(event, "data.sender") !== "nova-cms") {
        return;
    }

    switch (get(event, "data.key")) {
        case "preview-ready":
            onIframeLoaded(event.data);
            break;
        default:
    }
};

onMounted(() => {
    window.addEventListener("message", handleMessage);
});

/**
 * receive event from control
 */
emitter.on("page-editor-device", (val) => {
    handleDeviceView(val.deviceView);
});

watch(
    () => data.value,
    () => {
        updatePreview();
    },
    { deep: true },
);
</script>

<style scoped>
[class*="view-"] iframe {
    margin: auto;
    transition:
        width 0.3s ease 0s,
        height 0.3s ease 0s;
    position: relative;
}

.view-desktop,
.view-mobile {
    padding: 16px;
}

.view-desktop iframe {
    width: 1440px;
    min-width: 1440px;
    height: 800px;
    min-height: 800px;
    border: 6px solid #d1d5db;
    border-radius: 2rem;
}

.view-mobile iframe {
    width: 390px;
    min-width: 390px;
    height: 844px;
    min-height: 844px;
    border: 4px solid #d1d5db;
    border-radius: 2rem;
}
</style>
