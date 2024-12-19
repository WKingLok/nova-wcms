<template>
    <PageEditorModal :modalId="id">
        <div class="pe-min-h-[70vh]">
            <div class="pe-flex pe-justify-between">
                <h5 class="pe-text-xl pe-font-bold pe-leading-normal">
                    Components List
                </h5>
                <IconButton iconType="x" @click="closeModal" />
            </div>

            <div class="pe-mt-5 pe-flex pe-h-full pe-gap-4">
                <div class="pe-flex pe-flex-col pe-gap-4">
                    <template v-for="group in list" :key="group.key">
                        <div
                            class="pe-cursor-pointer pe-text-sm pe-font-bold hover:pe-text-primary-400"
                            :class="{
                                'pe-text-primary-500': group.key == activeTab,
                            }"
                            @click="activeTab = group.key"
                        >
                            {{ group.label }}
                        </div>
                    </template>
                </div>
                <div class="pe-w-full">
                    <div class="pe-grid pe-grid-cols-3 pe-gap-4">
                        <template
                            v-for="component in get(
                                activeGroup,
                                'components',
                                [],
                            )"
                            :key="component"
                        >
                            <div
                                class="pe-cursor-pointer pe-rounded-lg pe-bg-white pe-px-4 pe-py-2 pe-shadow-lg hover:pe-bg-primary-400 hover:pe-text-white dark:pe-bg-gray-700 hover:dark:pe-bg-primary-400"
                                @click="onSelect(component)"
                            >
                                {{ component.label }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </PageEditorModal>
</template>

<script setup>
import { defineProps, defineEmits, ref, computed } from "vue";
import { useVfm } from "vue-final-modal";
import get from "lodash/get";
import find from "lodash/find";

const props = defineProps({
    list: Array | Object,
});
const emit = defineEmits(["onSelect", "update:show"]);

const vfm = useVfm();

// vue-final-modal unique id
const id = "pe-component-list";

// active tab
const activeTab = ref(get(props.list, "0.key"));
const activeGroup = computed(() => find(props.list, ["key", activeTab.value]));

// when component is selected
const onSelect = (config) => {
    emit("onSelect", config);
    closeModal();
};

const closeModal = () => vfm.close(id);
</script>
