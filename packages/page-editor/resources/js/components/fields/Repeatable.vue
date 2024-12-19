<template>
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

        <div class="pe-flex pe-items-center pe-bg-white pe-px-3 pe-py-2">
            <div class="pe-font-[700]">{{ label }}</div>
            <div
                class="pe-ml-auto pe-mr-3 pe-w-3 pe-min-w-4 pe-cursor-pointer hover:pe-text-primary-500"
                @click="show = !show"
            >
                <Icon
                    type="chevron-up"
                    class="pe-max-w-full"
                    :style="!show ? `transform: rotate(180deg)` : ''"
                />
            </div>
        </div>
        <div
            class="pe-flex pe-flex-col pe-border pe-border-x-0 pe-border-b-0 pe-border-solid pe-border-gray-200 pe-px-5 pe-py-4"
            v-show="show"
        >
            <draggable
                handle=".btn-handle"
                v-model="value"
                item-key="id"
                force-fallback="false"
            >
                <template #item="{ element, index }">
                    <div class="pe-mb-4 pe-flex">
                        <div
                            class="btn-handle pe-my-auto pe-mr-3 pe-w-4 pe-min-w-4 pe-cursor-move hover:pe-text-primary-500"
                        >
                            <Icon
                                type="switch-vertical"
                                class="pe-max-w-full"
                            />
                        </div>
                        <div class="pe-w-full">
                            <PageEditorFormFields
                                v-model="value[index]"
                                v-bind="{
                                    ...attrs,
                                    repeatableIndex: index,
                                    title: `NO. ${cloneDeep(index + 1)}`,
                                }"
                            />
                        </div>
                        <div class="pe-ml-3">
                            <div
                                class="pe-flex pe-min-h-[38px] pe-w-4 pe-min-w-4 pe-cursor-pointer pe-items-center hover:pe-text-red-500"
                                @click="handleDelete(index)"
                            >
                                <Icon type="trash" class="pe-max-w-full" />
                            </div>
                        </div></div
                ></template>
            </draggable>
            <div class="pe-flex">
                <div
                    class="pe-mx-auto pe-w-4 pe-cursor-pointer hover:pe-text-primary-500"
                    @click="addItem"
                >
                    <Icon type="plus-circle" class="pe-max-w-full" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, useAttrs, ref, watch } from "vue";
import helper from "@/utils/helper";
import draggable from "vuedraggable";
import cloneDeep from "lodash/cloneDeep";
import get from "lodash/get";
import forEach from "lodash/forEach";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    repeatable: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const attrs = useAttrs();

const label = ref(attrs.label ?? startCase(attrs.name));

const addItem = () => {
    value.value.push(helper.repeatableAdd(attrs));
};

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});

if (typeof value.value == "object" && !Array.isArray(value.value)) {
    value.value = [];
}

const handleDelete = (index) => {
    value.value.splice(index, 1);
};

const hasError = ref(false);

watch(
    () => attrs.nested,
    (nested) => {
        hasError.value = false;
        forEach(nested, (el) =>
            findDeepErrors(el, () => (hasError.value = true)),
        );
    },
    { deep: true },
);

const findDeepErrors = (val, callback) => {
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
