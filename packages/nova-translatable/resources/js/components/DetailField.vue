<template>
    <div>
        <component
            :key="index"
            :index="index"
            :is="resolveComponentName(orgField)"
            :resource-name="orgField.resourceName"
            :resource-id="orgField.resourceId"
            :resource="orgField.resource"
            :field="orgField"
        />
    </div>
</template>

<script>
import get from "lodash/get";
import { ref, onMounted, onBeforeUnmount } from "vue";
import omitBy from "lodash/omitBy";
export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"],
    setup(props) {
        const orgField = ref(get(props.field, "field", props.field));

        const values = omitBy(
            get(props.field, "value", {}),
            (value) => value == null || value == "null"
        );

        const currentLocaleChange = (locale, panel) => {
            if (panel == props.field.panel) {
                orgField.value.value = get(values, locale);
            }
        };

        /**
         * Resolve the component name.
         */
        const resolveComponentName = (field) => {
            return field.prefixComponent
                ? "detail-" + field.component
                : field.component;
        };

        onMounted(() => {
            Nova.$on("current-locale-change", currentLocaleChange);
        });

        onBeforeUnmount(() => {
            Nova.$off("current-locale-change", currentLocaleChange);
        });

        return {
            orgField,
            resolveComponentName,
        };
    },
};
</script>
