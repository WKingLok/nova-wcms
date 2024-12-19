<template>
    <div>
        <template v-if="nested.length == 1">
            <PageEditorFormFields
                v-model="value[nested[0]['name']]"
                v-bind="{
                    ...nested[0],
                    label: null,
                    locale,
                    localeString,
                    repeatableIndex,
                }"
            />
        </template>
        <template v-else>
            <div
                class="pe-relative pe-overflow-hidden pe-rounded pe-border pe-border-solid pe-border-gray-200"
                :class="{
                    'pe-border-red-500': hasError,
                }"
            >
                <div
                    v-if="show"
                    class="pe-l-0 pe-t-0 pe-b-0 pe-absolute pe-h-full pe-w-1 pe-bg-primary-400"
                    :class="{
                        'pe-bg-red-500': hasError,
                    }"
                ></div>
                <div
                    class="pe-flex pe-items-center pe-bg-white pe-px-3 pe-py-2"
                >
                    <div v-if="title" class="pe-font-[700]">{{ title }}</div>
                    <div
                        class="pe-ml-auto pe-mr-3 pe-w-3 pe-min-w-4 pe-cursor-pointer hover:pe-text-primary-500"
                    >
                        <Icon
                            type="chevron-up"
                            class="pe-max-w-full"
                            @click="show = !show"
                            :style="!show ? `transform: rotate(180deg)` : ''"
                        />
                    </div>
                </div>
                <div
                    class="pe-flex pe-flex-col pe-gap-4 pe-border pe-border-x-0 pe-border-b-0 pe-border-solid pe-border-gray-200 pe-px-5 pe-py-4"
                    v-show="show"
                >
                    <template v-for="field in nested">
                        <PageEditorFormFields
                            v-model="value[field['name']]"
                            v-bind="{
                                ...field,
                                locale,
                                localeString,
                                repeatableIndex,
                            }"
                        />
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref, watch } from "vue";
import { isEmpty, get, forEach } from "lodash";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    title: String,
    nested: Array,
    locale: String,
    localeString: String,
    repeatableIndex: Number,
});

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});

const hasError = ref(false);

/**
 * check modelValue is null or empty {}
 */
props.nested.forEach((element) => {
    //if has value return;
    if (!isEmpty(get(value.value, element["name"]))) {
        return;
    }

    if (element["nested"] && element["nested"].length > 0) {
        value.value[element["name"]] = {};
    } else {
        value.value[element["name"]] = null;
    }
});

watch(
    () => props.nested,
    (nested) => {
        hasError.value = false;
        forEach(nested, (el) =>
            findDeepErrors(el, () => (hasError.value = true)),
        );
    },
    { deep: true },
);

const findDeepErrors = (val, callback) => {
    if (props.repeatableIndex || props.repeatableIndex === 0) {
        if (
            get(val, `errors.${props.repeatableIndex}`) &&
            get(val, `errors.${props.repeatableIndex}`).length > 0
        ) {
            callback();
        }

        return;
    }

    if (
        get(val, "errors") ||
        (get(val, "errors") && get(val, "errors").length > 0)
    ) {
        callback();
    }

    if (get(val, "nested")) {
        forEach(get(val, "nested"), (el) => findDeepErrors(el, callback));
    }
};

const show = ref(false);
</script>
