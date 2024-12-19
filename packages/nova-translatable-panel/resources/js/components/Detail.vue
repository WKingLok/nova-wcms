<template>
    <div>
        <slot>
            <div class="tp-justify-between tp-flex tp-items-center">
                <Heading :level="1" v-text="panel.name" />

                <button
                    v-if="panel.collapsable"
                    @click="toggleCollapse"
                    class="focus:ring-primary-200 tp-ml-1 tp-inline-flex tp-h-6 tp-w-6 tp-items-center tp-justify-center tp-rounded tp-border tp-border-transparent focus:tp-outline-none focus:tp-ring"
                    :aria-label="__('Toggle Collapsed')"
                    :aria-expanded="collapsed === false ? 'true' : 'false'"
                >
                    <CollapseButton :collapsed="collapsed" />
                </button>

                <LocaleSwitch
                    :locales="locales"
                    :current="currentLocale"
                    @change="handleLocaleChange"
                />
            </div>

            <p
                v-if="panel.helpText && !collapsed"
                class="tp-text-sm tp-font-semibold tp-italic tp-text-gray-500"
                :class="panel.helpText ? 'tp-mt-2' : 'tp-mt-3'"
                v-html="panel.helpText"
            />
        </slot>

        <Card
            class="dark:tp-divide-gray-700 tp-mt-3 tp-divide-y tp-divide-gray-100 tp-px-6 tp-py-2"
            v-if="!collapsed && fields.length > 0"
        >
            <component
                :key="index"
                v-for="(field, index) in fields"
                :index="index"
                :is="resolveComponentName(field)"
                :resource-name="resourceName"
                :resource-id="resourceId"
                :resource="resource"
                :field="field"
                @actionExecuted="actionExecuted"
            />

            <div
                v-if="shouldShowShowAllFieldsButton"
                class="dark:tp-border-gray-700 tp-mx-6 tp-rounded-b tp-border-t tp-border-gray-100 tp-text-center"
            >
                <button
                    type="button"
                    class="link-default tp-mb-2 tp-block tp-w-full tp-py-2 tp-text-sm tp-font-bold"
                    @click="showAllFields"
                >
                    {{ __("Show All Fields") }}
                </button>
            </div>
        </Card>
    </div>
</template>

<script>
import { Collapsable, BehavesAsPanel } from "@/mixins";
import LocaleSwitch from "./LocaleSwitch";
import { onMounted, ref } from "vue";
import get from "lodash/get";
export default {
    mixins: [Collapsable, BehavesAsPanel],

    methods: {
        /**
         * Resolve the component name.
         */
        resolveComponentName(field) {
            return field.prefixComponent
                ? "detail-" + field.component
                : field.component;
        },

        /**
         * Show all of the Panel's fields.
         */
        showAllFields() {
            return (this.panel.limit = 0);
        },
    },

    computed: {
        localStorageKey() {
            return `nova.panels.${this.panel.name}.collapsed`;
        },

        collapsedByDefault() {
            return this.panel?.collapsedByDefault ?? false;
        },

        /**
         * Limits the visible fields.
         */
        fields() {
            if (this.panel.limit > 0) {
                return this.panel.fields.slice(0, this.panel.limit);
            }

            return this.panel.fields;
        },

        /**
         * Determines if should display the 'Show all fields' button.
         */
        shouldShowShowAllFieldsButton() {
            return this.panel.limit > 0;
        },
    },
    setup(props) {
        console.log(props);
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
