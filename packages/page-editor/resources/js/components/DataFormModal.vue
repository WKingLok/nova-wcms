<template>
    <PageEditorModal :modalId="id">
        <div class="pe-mb-4 pe-flex pe-items-center pe-justify-between">
            <div class="pe-text-xl pe-font-[700]">{{ title }}</div>
            <PageEditorLocaleSwitch v-model="currentLocale" />
        </div>

        <div class="pe-flex pe-flex-col pe-gap-4">
            <template v-for="(field, index) in get(data, 'fields', [])">
                <PageEditorFormFields
                    v-bind="{
                        ...bindField(field),
                        locale: currentLocale,
                        localeString: locales[currentLocale],
                    }"
                    v-model="data['fields'][index]['value']"
                />
            </template>
            <template v-for="(callback, index) in get(data, 'callback', [])">
                <div
                    class="pe-flex pe-flex-col"
                    v-if="get(callback, 'edit_url')"
                >
                    <label>
                        <span class="pe-font-[700]">{{
                            get(callback, "label")
                        }}</span>
                    </label>
                    <div>
                        <a
                            target="_blank"
                            :href="get(callback, 'edit_url')"
                            class="btn btn-secondary text-white text-decoration-none"
                        >
                            Edit</a
                        >
                    </div>
                </div>
            </template>
        </div>

        <div class="pe-mt-3 pe-flex pe-justify-end pe-gap-2">
            <NovaUiButton label="Cancel" variant="ghost" @click="cancel" />
            <NovaUiButton @click="save" label="Save" />
        </div>
    </PageEditorModal>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, onBeforeMount } from "vue";
import { useVfm } from "vue-final-modal";
import pick from "lodash/pick";
import get from "lodash/get";
import set from "lodash/set";
import cloneDeep from "lodash/cloneDeep";
import head from "lodash/head";
import helper from "@/utils/helper";

const props = defineProps({
    config: String | Number | Boolean | Array | Object,
    onSave: Function,
});
const emit = defineEmits([]);
const vfm = useVfm();

// vue-final-modal unique id
const id = "pe-edit-modal";

//locales
const locales = get(Nova, "app.config.locales", []);

// current locale
const currentLocale = ref(head(Object.keys(locales)) ?? "en");

//clone the config object
const data = ref(cloneDeep(props.config));

const title = ref(get(props.config, "label"));

const bindField = (config) =>
    pick(config, [
        "type",
        "label",
        "name",
        "options",
        "translatable",
        "repeatable",
        "nested",
        "helpText",
        "errors",
    ]);

const save = () => {
    let result = helper.validateData(data.value.fields);

    set(data.value, "fields", get(result, "data"));

    if (result.hasError) {
        return;
    }

    if (props.onSave) {
        props.onSave(data.value);
    }
    vfm.close(id);
};

const cancel = () => vfm.close(id);

// const focusinTox = (event) => {
//     if ($(event.target).closest(".tox-dialog").length) {
//         event.stopImmediatePropagation();
//     }
// };

// onMounted(() => {
//     document.addEventListener("focusin", focusinTox, true);
// });

// onBeforeMount(() => {
//     document.removeEventListener("focusin", focusinTox, true);
// });
</script>
