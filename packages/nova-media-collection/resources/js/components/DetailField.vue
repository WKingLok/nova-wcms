<template>
    <PanelItem :index="index" :field="field">
        <template v-slot:value>
            <div class="mc-flex mc-flex-col mc-gap-y-3">
                <div
                    class="mc-rounded-lg mc-overflow-hidden"
                    v-for="(image, uuid) in displayImages"
                    :key="uuid"
                >
                    <img :src="image.preview_url" />
                </div>
            </div>
            <button
                type="button"
                v-if="!shouldShow && count > 3"
                @click="toggle"
                class="link-default"
                :class="{ 'mt-6': expanded }"
                aria-role="button"
                tabindex="0"
            >
                {{ showHideLabel }}
            </button>
        </template>
    </PanelItem>
</template>

<script>
import get from "lodash/get";
import filter from "lodash/filter";
import size from "lodash/size";
export default {
    props: ["resourceName", "field", "index"],
    data: () => ({ expanded: false }),
    methods: {
        toggle() {
            this.expanded = !this.expanded;
        },
    },
    computed: {
        count() {
            return size(this.field.displayedAs || get(this.field, "value", []));
        },
        showHideLabel() {
            return !this.expanded
                ? this.__("Show More")
                : this.__("Hide Images");
        },
        displayImages() {
            let medias = this.field.displayedAs || get(this.field, "value", []);

            if (this.expanded) {
                return medias;
            }

            let index = 0;
            return filter(medias, (o) => {
                index++;
                return index <= 3;
            });
        },
    },
};
</script>
