<template>
    <div v-if="panel.fields.length > 0" v-show="visibleFieldsCount > 0">
        <Heading :level="1" :class="panel.helpText ? 'tp-mb-2' : 'tp-mb-3'">
            <div class="tp-flex tp-justify-between tp-items-center">
                {{ panel.name }}
                <LocaleSwitch
                    :locales="locales"
                    :current="currentLocale"
                    @change="handleLocaleChange"
                />
            </div>
        </Heading>

        <p
            v-if="panel.helpText"
            class="tp-mb-3 tp-text-sm tp-font-semibold tp-italic tp-text-gray-500"
            v-html="panel.helpText"
        />

        <Card class="dark:tp-divide-gray-700 tp-divide-gray-100 tp-divide-y">
            <component
                v-for="(field, index) in fields"
                :index="index"
                :key="field.dependentComponentKey"
                :is="`form-${field.component}`"
                :errors="validationErrors"
                :resource-id="resourceId"
                :resource-name="resourceName"
                :related-resource-name="relatedResourceName"
                :related-resource-id="relatedResourceId"
                :field="field"
                :via-resource="viaResource"
                :via-resource-id="viaResourceId"
                :via-relationship="viaRelationship"
                :shown-via-new-relation-modal="shownViaNewRelationModal"
                :form-unique-id="formUniqueId"
                :mode="mode"
                @field-shown="handleFieldShown"
                @field-hidden="handleFieldHidden"
                @field-changed="$emit('field-changed')"
                @file-deleted="$emit('update-last-retrieved-at-timestamp')"
                @file-upload-started="$emit('file-upload-started')"
                @file-upload-finished="$emit('file-upload-finished')"
                :show-help-text="showHelpText"
            />
        </Card>
    </div>
</template>

<script>
import LocaleSwitch from "./LocaleSwitch";
import { HandlesPanelVisibility, mapProps } from "@/mixins";
import { onMounted, ref } from "vue";
import get from "lodash/get";
export default {
    mixins: [HandlesPanelVisibility],

    emits: [
        "field-changed",
        "update-last-retrieved-at-timestamp",
        "file-upload-started",
        "file-upload-finished",
    ],

    props: {
        shownViaNewRelationModal: {
            type: Boolean,
            default: false,
        },

        showHelpText: {
            type: Boolean,
            default: false,
        },

        panel: {
            type: Object,
            required: true,
        },

        ...mapProps(["mode"]),

        fields: {
            type: Array,
            default: [],
        },

        formUniqueId: {
            type: String,
        },

        validationErrors: {
            type: Object,
            required: true,
        },

        resourceName: {
            type: String,
            required: true,
        },

        resourceId: {
            type: [Number, String],
        },

        relatedResourceName: {
            type: String,
        },

        relatedResourceId: {
            type: [Number, String],
        },

        viaResource: {
            type: String,
        },

        viaResourceId: {
            type: [Number, String],
        },

        viaRelationship: {
            type: String,
        },
    },
    setup(props) {
        const locales = get(props.panel, "locales", []);
        const currentLocale = ref(null);

        const handleLocaleChange = (locale) => {
            currentLocale.value = locale;
            Nova.$emit("current-locale-change", locale, props.panel.name);
        };

        onMounted(() => {
            handleLocaleChange(Object.keys(locales)[0]);
        });

        return {
            locales,
            currentLocale,
            handleLocaleChange,
        };
    },
    components: { LocaleSwitch },
};
</script>
