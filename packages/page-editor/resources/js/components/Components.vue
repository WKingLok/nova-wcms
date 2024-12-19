<template>
    <div class="">
        <draggable
            handle=".btn-handle"
            v-model="list"
            item-key="uuid"
            force-fallback="false"
        >
            <template #item="{ element }">
                <div
                    class="click-handle pe-mb-4 pe-flex pe-items-center pe-rounded-lg pe-border-2 pe-border-solid pe-py-2 pe-pl-4 pe-pr-3 pe-shadow-lg"
                    @click="(e) => handleClick(element.uuid, e)"
                    :class="{
                        'pe-border-primary-500 pe-bg-primary-500 pe-text-white':
                            highlight == element.uuid,
                        'pe-border-white pe-bg-white dark:pe-border-gray-800 dark:pe-bg-gray-800':
                            highlight != element.uuid,
                    }"
                >
                    <div
                        class="btn-handle pe-mr-3 pe-w-4 pe-min-w-4 pe-cursor-move hover:pe-text-primary-500"
                        :class="{
                            'hover:pe-text-primary-200':
                                highlight == element.uuid,
                        }"
                    >
                        <Icon class="pe-h-5 pe-w-5" type="switch-vertical" />
                    </div>

                    <div class="click-handle pe-mr-auto">
                        {{ element.label }}
                        <span
                            class="pe-ml-2 pe-inline-flex pe-items-center pe-rounded pe-bg-primary-50 pe-px-2 pe-py-1 pe-text-xs pe-font-bold pe-text-primary-700 pe-ring-1 pe-ring-inset pe-ring-primary-700/30 dark:pe-bg-primary-500 dark:pe-text-gray-900 dark:pe-ring-gray-900/10"
                            v-if="element.type == 'share_widget'"
                            >Shared</span
                        >
                    </div>

                    <div
                        class="pe-ml-3 pe-w-4 pe-min-w-4 pe-cursor-pointer hover:pe-text-primary-500"
                        :class="{
                            'hover:pe-text-primary-200':
                                highlight == element.uuid,
                        }"
                        @click="handleEdit(element)"
                    >
                        <Icon class="pe-h-[19px] pe-w-[19px]" type="pencil" />
                    </div>

                    <div
                        class="pe-ml-3 pe-w-4 pe-min-w-4 pe-cursor-pointer hover:pe-text-red-500"
                        @click="handleDelete(element.uuid)"
                        v-if="previewConfig.type == 'page'"
                    >
                        <Icon class="pe-h-5 pe-w-5" type="trash" />
                    </div>
                </div>
            </template>
        </draggable>

        <div v-if="isEmptyList" class="pe-w-full pe-text-center">
            List Empty
        </div>

        <PageEditorDeleteModal
            :show="deleteModalOpen"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
        />
    </div>
</template>

<script setup>
import { defineProps, computed, ref } from "vue";
import draggable from "vuedraggable";
import remove from "lodash/remove";
import findIndex from "lodash/findIndex";
import get from "lodash/get";
import { useModal } from "vue-final-modal";
import DataFormModal from "./DataFormModal.vue";

const props = defineProps({
    modelValue: String | Number | Boolean | Array | Object,
    previewConfig: Object,
});
const emit = defineEmits(["update:modelValue"]);

const list = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});

const isEmptyList = computed(() => list.value.length <= 0);

const locales = get(Nova, "app.config.locales", []);

const highlight = ref(null);

/**
 * handle click
 */

const handleClick = (uuid, e) => {
    if (!e.target.classList.contains("click-handle")) {
        return;
    }
    highlight.value = highlight.value != uuid ? uuid : null;
    try {
        document
            .getElementById("editor-preview-iframe")
            .contentWindow.postMessage(
                JSON.parse(
                    JSON.stringify({
                        sender: "nova-cms",
                        key: "page-component-highlight",
                        value: {
                            uuid: highlight.value,
                        },
                    }),
                ),
                origin,
            );
    } catch (e) {
        console.log(e.message);
    }
};

/**
 * edit modal
 */
const editModal = useModal({
    defaultModelValue: false,
    keepAlive: false,
    component: DataFormModal,
    attrs: {
        onSave: (data) => handleEditModalSave(data),
        clickToClose: false,
    },
});

const handleEdit = (config) => {
    if (config.type == "share_widget") {
        window.open(
            `/wcms/resources/share-widgets/${config.id}/editor`,
            "_blank",
        );
        return;
    }

    editModal.open();
    editModal.patchOptions({
        attrs: {
            config: config,
        },
    });
};

/**
 * handle edit modal save
 */
const handleEditModalSave = (data) => {
    const _index = findIndex(list.value, (o) => o.uuid == data.uuid);
    if (_index > -1) {
        list.value[_index] = data;
    }
};

/**
 * delete modal
 */
const deleteModalOpen = ref(false);
const deleteUUID = ref();

const handleDelete = (uuid) => {
    deleteUUID.value = uuid;
    deleteModalOpen.value = true;
};

const confirmDelete = () => {
    remove(list.value, (n) => n.uuid == deleteUUID.value);
    closeDeleteModal();
};

const closeDeleteModal = () => {
    deleteUUID.value = null;
    deleteModalOpen.value = false;
};
</script>

<style>
.sortable-ghost {
    background-color: rgb(187, 187, 187);
    opacity: 0.2;
}
</style>
