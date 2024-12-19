<template>
    <div>
        <HeaderMenuDraggable
            :data-level="1"
            :items="menus"
            :level="1"
            :maxLevel="maxLevel"
        />

        <div
            class="md:hm-space-x-30 hm-mt-8 hm-flex hm-flex-col hm-justify-center hm-gap-3 hm-space-y-2 md:hm-flex-row md:hm-items-center md:hm-justify-end md:hm-space-y-0"
        >
            <NovaUiButton
                dusk="cancel-update-button"
                variant="ghost"
                label="Cancel"
                @click="cancel"
                :disabled="isWorking"
            />

            <NovaUiButton @click="formSubmit" :processing="isWorking"
                >Save</NovaUiButton
            >
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { get } from "lodash";

const props = defineProps({
    menus: Object,
    viaResource: String,
});

const menus = ref(get(props.menus, "data", []));
const isWorking = ref(false);
const maxLevel = ref(2);

/**
 * cancel reorder
 */
const cancel = () => {
    if (window.history.length > 1) {
        window.history.back();
        return;
    }

    Nova.visit(`/resources/${props.viaResource}`, {
        replace: true,
    });
};

/**
 * submit data to server
 */
const formSubmit = async () => {
    isWorking.value = true;

    try {
        await Nova.request().post(`/nova-api/header-menus/reorder`, {
            menus: menus.value,
        });

        isWorking.value = false;

        Nova.success("The Menu was updated!");
    } catch (error) {
        isWorking.value = false;
        Nova.error("There was a problem submitting the form.");
    }
};
</script>
