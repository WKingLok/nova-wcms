<template>
    <div>
        <div
            class="pe-relative pe-flex pe-h-screen pe-max-h-[calc(100vh_-_244px)] pe-gap-4"
        >
            <PageEditorPreview
                v-model="previewData"
                :config="preview"
                v-model:locale="currentLocale"
            />
            <div
                class="pe-flex pe-w-full pe-flex-col pe-gap-4 md:pe-max-w-[300px]"
            >
                <PageEditorPreviewControl @preview="previewNewTab" />

                <PageEditorLocaleSwitch v-model="currentLocale" />

                <div class="pe-mt-4 pe-flex pe-items-center">
                    <div class="pe-font-bold">Widgets</div>
                    <div
                        class="pe-ml-auto pe-w-4 pe-cursor-pointer hover:pe-text-primary-500"
                        @click="openComponentList"
                        v-if="preview.type == 'page'"
                    >
                        <Icon type="plus" />
                    </div>
                </div>

                <div class="pe-overflow-y-auto">
                    <PageEditorComponents
                        v-model="data"
                        :preview-config="preview"
                    />
                </div>

                <div
                    class="pe-mt-auto pe-flex pe-flex-col pe-justify-center pe-space-y-2 md:pe-flex-row md:pe-items-center md:pe-justify-end md:pe-space-x-3 md:pe-space-y-0"
                >
                    <NovaUiButton
                        dusk="cancel-update-button"
                        variant="ghost"
                        label="Cancel"
                        @click="cancelUpdate"
                        :disabled="isWorking"
                    />

                    <NovaUiButton
                        @click="formSubmit"
                        :processing="isWorking"
                        label="Update"
                    />
                </div>
            </div>
        </div>

        <PageEditorComponentList
            :list="componentList"
            @onSelect="addComponent"
        />

        <ModalsContainer />

        <form
            method="post"
            ref="previewFormRef"
            action="preview"
            target="_blank"
        >
            <input type="hidden" name="_token" :value="csrfToken" />
            <input
                type="hidden"
                name="preview"
                :value="
                    JSON.stringify({
                        data: previewData,
                        locale: currentLocale,
                    })
                "
            />
        </form>
    </div>
</template>
<script setup>
import { defineProps, ref, computed } from "vue";
import helper from "@/utils/helper";
import { set } from "lodash";
import { useVfm, ModalsContainer } from "vue-final-modal";

const props = defineProps({
    resourceName: String,
    resourceId: String,
    relatedResourceName: String,
    relatedResourceId: String,
    viaResource: String,
    viaResourceId: String,
    csrfToken: String,
    locales: Object,
    config: Object,
    tinymceTemplates: Array,
    componentList: Array,
    data: Array,
    preview: Object,
    tinymceKey: String,
    tinymceTemplates: Array,
});

const vfm = useVfm();

const currentLocale = ref("en");

/**
 * preview new tab from ref
 */
const previewFormRef = ref(null);

/**
 * page widget data
 */
const data = ref(helper.checkDataStructure(props.data, props.locales));

/**
 * handle data for submit
 */
const formData = computed(() => {
    return helper.dataFormatting(
        data.value,
        props.locales,
        currentLocale.value,
        false,
    );
});

/**
 * handle data for preview
 */
const previewData = computed(() => {
    return helper.dataFormatting(
        data.value,
        props.locales,
        currentLocale.value,
        true,
    );
});

/**
 * form submit loading status
 */
const isWorking = ref(false);

/**
 * set tinymce
 */
set(Nova, "app.config.tinymce", props.tinymceConfig);

/**
 * set locales options
 */
set(Nova, "app.config.locales", props.locales);

/**
 * handle preview new tab click
 */
const previewNewTab = () => previewFormRef.value.submit();

/**
 * open edit component modal
 */
const openComponentList = () => {
    vfm.open("pe-component-list");
};

/**
 * add new component
 * @param {*} config
 */
const addComponent = (config) => {
    helper.newComponent(config).then((component) => {
        data.value.push(component);
    });
};

/**
 * cancel update
 */
const cancelUpdate = () => {
    if (window.history.length > 1) {
        window.history.back();
        return;
    }

    Nova.visit(`/resources/${props.viaResource}/${props.viaResourceId}`, {
        replace: true,
    });
};

/**
 * handle submit
 */
const formSubmit = async () => {
    isWorking.value = true;

    try {
        let url = `/nova-api/page-editors/${props.resourceId}/editor`;
        let message = "The page was updated!";

        if (props.preview.type == "share-widget") {
            url = `/nova-api/page-editors/${props.resourceId}/share-widget-editor`;
            message = "The share widget was updated";
        }

        await Nova.request().post(url, {
            data: formData.value,
        });

        isWorking.value = false;

        Nova.success(message);
    } catch (error) {
        isWorking.value = false;
        Nova.error("There was a problem submitting the form.");
    }
};
</script>
