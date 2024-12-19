<template>
    <Head>
        <title>{{ pageTitle }}</title>
        <meta
            v-for="(metaValue, metaKey) in metas"
            :key="metaKey"
            :name="metaKey"
            :content="metaValue"
        />
    </Head>

    <template v-for="widget in widgets" :key="widget">
        <component
            :is="`${widget.key}`"
            v-bind="get(widget, 'data', {})"
        ></component>
    </template>
</template>

<script setup>
import { computed, defineProps } from "vue";
import { Head } from "@inertiajs/vue3";
import get from "lodash/get";

const props = defineProps({
    page: Object,
    widgets: Object,
});

const pageTitle = get(props.page, "data.title");

const metas = get(props.page, "data.seo");

const widgets = computed(() => get(props.widgets, "data", []));
</script>
