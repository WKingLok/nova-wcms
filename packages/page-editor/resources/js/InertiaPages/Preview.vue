<template>
    <WidgetsHeader v-if="layoutView == 'full'" />
    <main class="pt-[88px]">
        <div
            :data-uuid="item.uuid"
            v-for="item in previewData"
            :key="item.id"
            :class="{
                'component-highlight': item.uuid == highlight,
                full: layoutView,
            }"
        >
            <component :is="item.key" v-bind="item.data" />
        </div>
    </main>
    <WidgetsFooter v-if="layoutView == 'full'" />
</template>

<script setup>
import {
    defineProps,
    ref,
    onMounted,
    onBeforeUnmount,
    watch,
    computed,
} from "vue";

import get from "lodash/get";
import head from "lodash/head";

import Layout from "./Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    preview: Object,
});

const previewData = ref(props.preview ?? []);
const layoutView = ref(false);
const highlight = ref(false);

/**
 * ssr
 */
const handlePagePreview = (data) => {
    layoutView.value = data.layout;
    previewData.value = JSON.parse(data.value);
};

const handleComponentHighlight = (data) => {
    highlight.value = get(data, "value.uuid");

    try {
        let el = head(
            document.querySelectorAll(`[data-uuid='${highlight.value}']`),
        );

        if (el === undefined) {
            return;
        }

        let headerHeight =
            document.getElementsByClassName("header-wrap")[0].offsetHeight;
        let elPosition =
            el.getBoundingClientRect().top + window.pageYOffset - headerHeight;

        window.scrollTo({
            top: elPosition,
            behavior: "smooth",
        });
    } catch (error) {}
};

/**
 * handle postMessage data
 */
const handleMessage = (event) => {
    /**
     * check origin
     * check sender nova-cms
     */
    if (event.origin !== origin || get(event, "data.sender") !== "nova-cms") {
        return;
    }

    switch (get(event, "data.key")) {
        case "page-preview-data":
            handlePagePreview(event.data);
            break;
        case "page-component-highlight":
            handleComponentHighlight(event.data);
            break;
        default:
    }
};

onMounted(async () => {
    window.addEventListener("message", handleMessage);

    /**
     *  message parent preview iframe ready
     */
    window.parent.postMessage(
        JSON.parse(
            JSON.stringify({
                sender: "nova-cms",
                key: "preview-ready",
            }),
        ),
        origin,
    );

    if (window.parent.length == 0) {
        throw createError({
            statusCode: 404,
            statusMessage: "404 Not Found",
            fatal: true,
        });
    }

    if (document.getElementsByTagName("html").length > 0) {
        document.getElementsByTagName("html")[0].classList.add("is-ready");
    }
});

onBeforeUnmount(() => {
    window.removeEventListener("message", handleMessage);
});
</script>

<style lang="scss" scoped>
.component-highlight {
    position: relative;
}

.component-highlight::before {
    content: "";
    top: 0;
    left: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    border: 2px solid #2071c4;
    z-index: 1;
}
</style>
