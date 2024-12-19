<template>
    <component
        :key="orgField.uniqueKey"
        :is="`form-${orgField.component}`"
        :errors="validationErrors"
        :resource-id="resourceId"
        :resource-name="resourceName"
        :related-resource-name="relatedResourceName"
        :related-resource-id="relatedResourceId"
        :field="orgField"
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
        @input="handleValueChange"
    />
</template>

<script>
import { FormField, HandlesValidationErrors, mapProps } from "laravel-nova";
import { onBeforeUnmount, onMounted, reactive, ref } from "vue";
import get from "lodash/get";
import forEach from "lodash/forEach";
import omitBy from "lodash/omitBy";
export default {
    mixins: [FormField, HandlesValidationErrors],
    props: {
        ...mapProps(["mode"]),
        shownViaNewRelationModal: { type: Boolean, default: false },
        showHelpText: { type: Boolean, default: false },
        dusk: { type: String },
        fields: { type: Object },
        formUniqueId: { type: String },
        validationErrors: { type: Object, required: true },
        resourceName: { type: String, required: true },
        resourceId: { type: [Number, String] },
        relatedResourceName: { type: String },
        relatedResourceId: { type: [Number, String] },
        viaResource: { type: String },
        viaResourceId: { type: [Number, String] },
        viaRelationship: { type: String },
    },
    setup(props) {
        const orgField = reactive(props.field.field);
        const currentLocale = ref(null);
        const data = ref(get(props.field, "value"));

        if (!data.value) {
            data.value = {};
        } else {
            data.value = omitBy(
                data.value,
                (value) => value == null || value == "null"
            );
        }

        const currentLocaleChange = (locale, panel) => {
            if (panel == props.field.panel) {
                currentLocale.value = locale;
                orgField.uniqueKey = `${orgField.component}.${orgField.attribute}.${locale}`;
                orgField.value = get(data.value, locale, "");
            }
        };

        const handleValueChange = (e) => {
            data.value[currentLocale.value] = get(e, "target.value", e);
        };

        onMounted(() => {
            Nova.$on("current-locale-change", currentLocaleChange);
        });

        onBeforeUnmount(() => {
            Nova.$off("current-locale-change", currentLocaleChange);
        });

        return {
            orgField,
            currentLocaleChange,
            data,
            handleValueChange,
        };
    },
    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            forEach(this.data, (value, locale) => {
                formData.append(`${this.orgField.attribute}:${locale}`, value);
            });
        },
    },
};
</script>
