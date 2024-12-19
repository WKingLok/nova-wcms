<template>
    <PanelItem :index="index" :field="field">
        <template v-slot:value>
            <div class="mf-flex mf-flex-col mf-gap-y-3">
                <div
                    class="mf-rounded-lg mf-overflow-hidden"
                    v-for="(media, uuid) in get(field, 'value', [])"
                    :key="uuid"
                >
                    <a
                        class="link-default"
                        target="_blank"
                        :href="media.original_url"
                        >{{ media.name }}</a
                    >
                </div>
            </div>
        </template>
    </PanelItem>
</template>

<script>
import get from "lodash/get";
export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"],
    methods: {
        get,
    },
};
</script>
